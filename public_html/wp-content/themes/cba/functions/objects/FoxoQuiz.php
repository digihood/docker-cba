<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class FoxoQuizAnswer {
    public string $uid       = '';
    public string $text      = '';
    public bool   $isCorrect = false;
    public string $feedback  = '';
}

class FoxoQuizQuestion {
    public string $uid         = '';
    public string $text        = '';
    public string $type        = 'single_choice'; // single_choice | multiple_choice
    public string $image       = '';
    public string $explanation = '';
    /** @var FoxoQuizAnswer[] */
    public array  $answers     = [];
}

class FoxoQuiz {
    public int    $id                  = 0;
    public string $title               = '';
    public string $slug                = '';
    public string $url                 = '';
    public string $intro               = '';
    public bool   $active              = true;
    public int    $requiredScore       = 0;
    public bool   $showCorrectAnswers  = false;
    public bool   $allowRepeat         = true;
    public string $resultPassText      = '';
    public string $resultFailText      = '';
    /** @var FoxoQuizQuestion[] */
    public array  $questions           = [];
    public array  $relatedContent      = [];

    public static function from_post( WP_Post $post ): self {
        $q              = new self();
        $q->id          = $post->ID;
        $q->title       = $post->post_title;
        $q->slug        = $post->post_name;
        $q->url         = get_permalink( $post->ID );
        $q->intro       = (string) get_field( 'foxo_quiz_intro', $post->ID );
        $q->active      = (bool)   get_field( 'foxo_quiz_active', $post->ID );
        $q->requiredScore      = (int) get_field( 'foxo_quiz_required_score', $post->ID );
        $q->showCorrectAnswers = (bool) get_field( 'foxo_quiz_show_correct_answers', $post->ID );
        $q->allowRepeat        = (bool) get_field( 'foxo_quiz_allow_repeat', $post->ID );
        $q->resultPassText     = (string) get_field( 'foxo_quiz_result_pass_text', $post->ID );
        $q->resultFailText     = (string) get_field( 'foxo_quiz_result_fail_text', $post->ID );

        $raw_questions = get_field( 'foxo_quiz_questions', $post->ID );
        if ( is_array( $raw_questions ) ) {
            foreach ( $raw_questions as $rq ) {
                $question              = new FoxoQuizQuestion();
                $question->uid         = (string) ( $rq['question_unique_id'] ?? '' );
                $question->text        = (string) ( $rq['question_text'] ?? '' );
                $question->type        = (string) ( $rq['question_type'] ?? 'single_choice' );
                $question->image       = is_array( $rq['question_image'] ?? null )
                    ? (string) ( $rq['question_image']['url'] ?? '' )
                    : '';
                $question->explanation = (string) ( $rq['question_explanation'] ?? '' );

                $raw_answers = $rq['question_answers'] ?? [];
                if ( is_array( $raw_answers ) ) {
                    foreach ( $raw_answers as $ra ) {
                        $answer            = new FoxoQuizAnswer();
                        $answer->uid       = (string) ( $ra['answer_unique_id'] ?? '' );
                        $answer->text      = (string) ( $ra['answer_text'] ?? '' );
                        $answer->isCorrect = (bool)   ( $ra['answer_is_correct'] ?? false );
                        $answer->feedback  = (string) ( $ra['answer_feedback'] ?? '' );
                        $question->answers[] = $answer;
                    }
                }
                $q->questions[] = $question;
            }
        }

        $related = get_field( 'foxo_quiz_related_content', $post->ID );
        $q->relatedContent = is_array( $related ) ? $related : [];

        return $q;
    }
}

class FoxoQuizResult {
    public int    $quizId       = 0;
    public int    $userId       = 0;
    public string $attemptUid   = '';
    public int    $score        = 0;
    public int    $maxScore     = 0;
    public int    $percentage   = 0;
    public bool   $passed       = false;
    public array  $questionResults = [];
    public string $completedAt  = '';
}
