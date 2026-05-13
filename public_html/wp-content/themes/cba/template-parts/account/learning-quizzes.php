<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$user_id    = get_current_user_id();
$quiz_stats = FoxoUserLearningService::get_quiz_stats( $user_id );

$quizzes = get_posts( [
    'post_type'      => 'foxo_quiz',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'meta_query'     => [ [ 'key' => 'foxo_quiz_active', 'value' => '1' ] ],
] );
?>

<div class="account-card foxo-account-quizzes">
    <div class="account-card__header">
        <h2 class="account-card__title"><?php esc_html_e( 'Kvízy', 'cba' ); ?></h2>
    </div>
    <div class="account-card__body">
        <?php if ( ! $quizzes ) : ?>
            <p class="foxo-notice"><?php esc_html_e( 'Momentálně nejsou dostupné žádné kvízy.', 'cba' ); ?></p>
        <?php else : ?>
            <div class="foxo-quiz-list">
                <?php foreach ( $quizzes as $q ) :
                    $stats     = $quiz_stats[ 'quiz_' . $q->ID ] ?? [];
                    $attempted = ! empty( $stats['attempts'] );
                    $intro     = (string) get_field( 'foxo_quiz_intro', $q->ID );
                    $allow_repeat = (bool) get_field( 'foxo_quiz_allow_repeat', $q->ID );
                ?>
                    <div class="foxo-quiz-card">
                        <h3 class="foxo-quiz-card__title">
                            <a href="<?php echo esc_url( get_permalink( $q->ID ) ); ?>"><?php echo esc_html( $q->post_title ); ?></a>
                        </h3>
                        <?php if ( $intro ) : ?>
                            <p class="foxo-quiz-card__intro"><?php echo esc_html( $intro ); ?></p>
                        <?php endif; ?>

                        <?php if ( $attempted ) : ?>
                            <div class="foxo-quiz-card__stats">
                                <span class="foxo-quiz-card__stat">
                                    <?php printf( esc_html__( 'Nejlepší výsledek: %d %%', 'cba' ), (int) $stats['bestScore'] ); ?>
                                </span>
                                <span class="foxo-quiz-card__stat">
                                    <?php printf( esc_html__( 'Posledně: %d %%', 'cba' ), (int) $stats['lastScore'] ); ?>
                                </span>
                                <span class="foxo-quiz-card__stat foxo-quiz-card__stat--<?php echo $stats['passed'] ? 'passed' : 'failed'; ?>">
                                    <?php echo $stats['passed']
                                        ? esc_html__( '✓ Splněno', 'cba' )
                                        : esc_html__( '✗ Nesplněno', 'cba' );
                                    ?>
                                </span>
                                <span class="foxo-quiz-card__stat">
                                    <?php printf( esc_html__( 'Počet pokusů: %d', 'cba' ), (int) $stats['attempts'] ); ?>
                                </span>
                            </div>
                        <?php endif; ?>

                        <a
                            href="<?php echo esc_url( get_permalink( $q->ID ) ); ?>"
                            class="<?php echo d1g1B::btn_class( $attempted ? 'outline' : 'primary' ); ?>"
                        >
                            <?php if ( ! $attempted ) :
                                esc_html_e( 'Spustit kvíz', 'cba' );
                            elseif ( $allow_repeat ) :
                                esc_html_e( 'Zopakovat kvíz', 'cba' );
                            else :
                                esc_html_e( 'Zobrazit kvíz', 'cba' );
                            endif; ?>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
