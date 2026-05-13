<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/** @var FoxoQuiz $quiz */
$quiz    = $args['quiz'] ?? null;
$user_id = get_current_user_id();

if ( ! $quiz instanceof FoxoQuiz ) return;
if ( ! $quiz->active ) { echo '<p class="foxo-notice">' . esc_html__( 'Tento kvíz momentálně není dostupný.', 'cba' ) . '</p>'; return; }
?>

<div class="foxo-quiz" id="foxo-quiz-<?php echo esc_attr( $quiz->id ); ?>" data-quiz-id="<?php echo esc_attr( $quiz->id ); ?>">

    <?php if ( $quiz->intro ) : ?>
        <div class="foxo-quiz__intro"><?php echo wp_kses_post( $quiz->intro ); ?></div>
    <?php endif; ?>

    <form class="foxo-quiz__form" id="foxo-quiz-form" novalidate>
        <?php wp_nonce_field( 'wp_rest', '_wpnonce' ); ?>

        <div class="foxo-quiz__questions">
            <?php foreach ( $quiz->questions as $i => $question ) : ?>
                <div
                    class="foxo-quiz__question"
                    data-question-uid="<?php echo esc_attr( $question->uid ); ?>"
                    data-type="<?php echo esc_attr( $question->type ); ?>"
                >
                    <p class="foxo-quiz__question-num"><?php printf( esc_html__( 'Otázka %d', 'cba' ), $i + 1 ); ?></p>
                    <p class="foxo-quiz__question-text"><?php echo esc_html( $question->text ); ?></p>

                    <?php if ( $question->image ) : ?>
                        <img src="<?php echo esc_url( $question->image ); ?>" alt="" class="foxo-quiz__question-img" loading="lazy">
                    <?php endif; ?>

                    <div class="foxo-quiz__answers" role="group" aria-label="<?php esc_attr_e( 'Odpovědi', 'cba' ); ?>">
                        <?php foreach ( $question->answers as $answer ) :
                            $input_type = $question->type === 'single_choice' ? 'radio' : 'checkbox';
                            $name       = $question->type === 'single_choice'
                                ? 'answers[' . esc_attr( $question->uid ) . ']'
                                : 'answers[' . esc_attr( $question->uid ) . '][]';
                        ?>
                            <label class="foxo-quiz__answer" data-answer-uid="<?php echo esc_attr( $answer->uid ); ?>">
                                <input
                                    type="<?php echo esc_attr( $input_type ); ?>"
                                    name="<?php echo esc_attr( $name ); ?>"
                                    value="<?php echo esc_attr( $answer->uid ); ?>"
                                    class="foxo-quiz__answer-input"
                                >
                                <span class="foxo-quiz__answer-text"><?php echo esc_html( $answer->text ); ?></span>
                                <span class="foxo-quiz__answer-feedback" hidden></span>
                            </label>
                        <?php endforeach; ?>
                    </div>

                    <div class="foxo-quiz__question-result" hidden>
                        <span class="foxo-quiz__question-result-icon"></span>
                        <span class="foxo-quiz__question-result-text"></span>
                        <?php if ( $question->explanation ) : ?>
                            <p class="foxo-quiz__explanation"><?php echo esc_html( $question->explanation ); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="foxo-quiz__validation-msg" role="alert" hidden></div>

        <div class="foxo-quiz__actions">
            <button type="submit" class="<?php echo d1g1B::btn_class('primary'); ?>" id="foxo-quiz-submit">
                <?php esc_html_e( 'Vyhodnotit kvíz', 'cba' ); ?>
            </button>
        </div>
    </form>

    <!-- Result section (shown after evaluation) -->
    <div class="foxo-quiz__result" id="foxo-quiz-result" hidden>
        <div class="foxo-quiz__result-score">
            <span class="foxo-quiz__result-pct" id="foxo-result-pct"></span>
            <span class="foxo-quiz__result-label" id="foxo-result-label"></span>
        </div>
        <div class="foxo-quiz__result-detail" id="foxo-result-detail"></div>
        <div class="foxo-quiz__result-text" id="foxo-result-text"></div>

        <?php if ( $quiz->relatedContent ) : ?>
            <div class="foxo-quiz__related">
                <h3 class="foxo-quiz__related-title"><?php esc_html_e( 'Doporučujeme dále', 'cba' ); ?></h3>
                <ul class="foxo-quiz__related-list">
                    <?php foreach ( $quiz->relatedContent as $related_post ) : ?>
                        <li>
                            <a href="<?php echo esc_url( get_permalink( $related_post->ID ) ); ?>">
                                <?php echo esc_html( $related_post->post_title ); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ( $quiz->allowRepeat ) : ?>
            <button type="button" class="<?php echo d1g1B::btn_class('outline'); ?>" id="foxo-quiz-retry">
                <?php esc_html_e( 'Zkusit znovu', 'cba' ); ?>
            </button>
        <?php endif; ?>
    </div>

</div>
