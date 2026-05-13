<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class FoxoQuizService {

    /**
     * Evaluate submitted answers against stored correct answers.
     * $submitted: [ question_uid => answer_uid | [answer_uid, ...] ]
     */
    public static function evaluate( int $quiz_id, array $submitted, int $user_id = 0 ): FoxoQuizResult {
        $post = get_post( $quiz_id );
        if ( ! $post || $post->post_type !== 'foxo_quiz' ) {
            return new FoxoQuizResult();
        }

        $quiz       = FoxoQuiz::from_post( $post );
        $attempt_uid = 'a_' . bin2hex( random_bytes( 8 ) );

        $result              = new FoxoQuizResult();
        $result->quizId      = $quiz_id;
        $result->userId      = $user_id;
        $result->attemptUid  = $attempt_uid;
        $result->maxScore    = count( $quiz->questions );
        $result->completedAt = current_time( 'mysql' );

        foreach ( $quiz->questions as $question ) {
            $is_correct     = false;
            $submitted_uids = [];

            if ( isset( $submitted[ $question->uid ] ) ) {
                $raw = $submitted[ $question->uid ];
                $submitted_uids = is_array( $raw ) ? $raw : [ $raw ];
            }

            $correct_uids = array_map(
                fn( $a ) => $a->uid,
                array_filter( $question->answers, fn( $a ) => $a->isCorrect )
            );

            if ( $question->type === 'single_choice' ) {
                $is_correct = count( $submitted_uids ) === 1
                    && count( $correct_uids ) === 1
                    && reset( $submitted_uids ) === reset( $correct_uids );
            } else {
                sort( $submitted_uids );
                sort( $correct_uids );
                $is_correct = $submitted_uids === $correct_uids;
            }

            if ( $is_correct ) {
                $result->score++;
            }

            $result->questionResults[ $question->uid ] = [
                'is_correct'    => $is_correct,
                'submitted'     => $submitted_uids,
                'correct'       => $correct_uids,
                'explanation'   => $question->explanation,
                'answer_feedback' => self::get_feedback( $question, $submitted_uids ),
            ];

            if ( $user_id ) {
                FoxoLearningDB::save_quiz_answer( [
                    'user_id'      => $user_id,
                    'quiz_id'      => $quiz_id,
                    'question_uid' => $question->uid,
                    'answer_uid'   => implode( ',', $submitted_uids ),
                    'answer_value' => $submitted_uids,
                    'attempt_uid'  => $attempt_uid,
                    'is_correct'   => $is_correct,
                ] );
            }
        }

        $result->percentage = $result->maxScore > 0
            ? (int) round( ( $result->score / $result->maxScore ) * 100 )
            : 0;

        $result->passed = $result->percentage >= $quiz->requiredScore;

        if ( $user_id ) {
            self::update_user_meta_summary( $user_id, $quiz, $result );
        }

        return $result;
    }

    private static function get_feedback( FoxoQuizQuestion $question, array $submitted_uids ): array {
        $feedback = [];
        foreach ( $question->answers as $answer ) {
            if ( in_array( $answer->uid, $submitted_uids, true ) && $answer->feedback ) {
                $feedback[] = $answer->feedback;
            }
        }
        return $feedback;
    }

    private static function update_user_meta_summary( int $user_id, FoxoQuiz $quiz, FoxoQuizResult $result ): void {
        $learning_data = FoxoUserLearningService::get_user_learning_data( $user_id );

        $quiz_key = 'quiz_' . $quiz->id;
        $existing = $learning_data['quizStats'][ $quiz_key ] ?? [];

        $best = max( $result->percentage, (int) ( $existing['bestScore'] ?? 0 ) );
        $attempts = ( (int) ( $existing['attempts'] ?? 0 ) ) + 1;

        $learning_data['quizStats'][ $quiz_key ] = [
            'quizId'       => $quiz->id,
            'title'        => $quiz->title,
            'url'          => $quiz->url,
            'bestScore'    => $best,
            'lastScore'    => $result->percentage,
            'passed'       => $result->passed,
            'attempts'     => $attempts,
            'lastAttemptAt' => $result->completedAt,
        ];

        $learning_data['lastVisited']['quiz'] = [
            'id'        => $quiz->id,
            'title'     => $quiz->title,
            'url'       => $quiz->url,
            'visitedAt' => $result->completedAt,
        ];

        FoxoUserLearningService::save_user_learning_data( $user_id, $learning_data );
    }

    public static function record_visit( int $user_id, int $quiz_id ): void {
        if ( ! $user_id ) return;
        $post = get_post( $quiz_id );
        if ( ! $post ) return;

        $learning_data = FoxoUserLearningService::get_user_learning_data( $user_id );
        $learning_data['lastVisited']['quiz'] = [
            'id'        => $quiz_id,
            'title'     => $post->post_title,
            'url'       => get_permalink( $quiz_id ),
            'visitedAt' => current_time( 'mysql' ),
        ];
        FoxoUserLearningService::save_user_learning_data( $user_id, $learning_data );
    }
}
