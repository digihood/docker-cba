<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$user_id = get_current_user_id();
$data    = FoxoUserLearningService::get_user_learning_data( $user_id );
$visited = $data['lastVisited'] ?? [];

$course       = $visited['course']       ?? null;
$lesson       = $visited['lesson']       ?? null;
$quiz         = $visited['quiz']         ?? null;
$decision_tree = $visited['decisionTree'] ?? null;

$has_activity = (
    ! empty( $course['id'] ) ||
    ! empty( $lesson['id'] ) ||
    ! empty( $quiz['id'] )   ||
    ! empty( $decision_tree['id'] )
);
?>

<div class="account-card foxo-activity">
    <div class="account-card__header">
        <h2 class="account-card__title"><?php esc_html_e( 'Pokračovat tam, kde jste skončili', 'cba' ); ?></h2>
    </div>
    <div class="account-card__body">
        <?php if ( ! $has_activity ) : ?>
            <p class="foxo-activity__empty"><?php esc_html_e( 'Zatím nemáte žádnou rozpracovanou aktivitu.', 'cba' ); ?></p>
        <?php else : ?>
            <div class="foxo-activity__grid">

                <?php if ( ! empty( $course['id'] ) ) : ?>
                    <div class="foxo-activity__item">
                        <span class="foxo-activity__type"><?php esc_html_e( 'Kurz', 'cba' ); ?></span>
                        <span class="foxo-activity__title"><?php echo esc_html( $course['title'] ); ?></span>
                        <?php if ( $course['visitedAt'] ) : ?>
                            <span class="foxo-activity__date">
                                <?php printf( esc_html__( 'Navštíveno: %s', 'cba' ), esc_html( date_i18n( 'd. n. Y H:i', strtotime( $course['visitedAt'] ) ) ) ); ?>
                            </span>
                        <?php endif; ?>
                        <a href="<?php echo esc_url( $course['url'] ); ?>" class="<?php echo d1g1B::btn_class('outline'); ?>">
                            <?php esc_html_e( 'Pokračovat', 'cba' ); ?>
                        </a>
                    </div>
                <?php endif; ?>

                <?php if ( ! empty( $lesson['id'] ) ) : ?>
                    <div class="foxo-activity__item">
                        <span class="foxo-activity__type"><?php esc_html_e( 'Lekce', 'cba' ); ?></span>
                        <span class="foxo-activity__title"><?php echo esc_html( $lesson['title'] ); ?></span>
                        <?php if ( $lesson['visitedAt'] ) : ?>
                            <span class="foxo-activity__date">
                                <?php printf( esc_html__( 'Navštíveno: %s', 'cba' ), esc_html( date_i18n( 'd. n. Y H:i', strtotime( $lesson['visitedAt'] ) ) ) ); ?>
                            </span>
                        <?php endif; ?>
                        <a href="<?php echo esc_url( $lesson['url'] ); ?>" class="<?php echo d1g1B::btn_class('outline'); ?>">
                            <?php esc_html_e( 'Pokračovat', 'cba' ); ?>
                        </a>
                    </div>
                <?php endif; ?>

                <?php if ( ! empty( $quiz['id'] ) ) : ?>
                    <div class="foxo-activity__item">
                        <span class="foxo-activity__type"><?php esc_html_e( 'Kvíz', 'cba' ); ?></span>
                        <span class="foxo-activity__title"><?php echo esc_html( $quiz['title'] ); ?></span>
                        <?php if ( $quiz['visitedAt'] ) : ?>
                            <span class="foxo-activity__date">
                                <?php printf( esc_html__( 'Navštíveno: %s', 'cba' ), esc_html( date_i18n( 'd. n. Y H:i', strtotime( $quiz['visitedAt'] ) ) ) ); ?>
                            </span>
                        <?php endif; ?>
                        <a href="<?php echo esc_url( $quiz['url'] ); ?>" class="<?php echo d1g1B::btn_class('outline'); ?>">
                            <?php esc_html_e( 'Spustit', 'cba' ); ?>
                        </a>
                    </div>
                <?php endif; ?>

                <?php if ( ! empty( $decision_tree['id'] ) ) : ?>
                    <div class="foxo-activity__item">
                        <span class="foxo-activity__type"><?php esc_html_e( 'Průvodce', 'cba' ); ?></span>
                        <span class="foxo-activity__title"><?php echo esc_html( $decision_tree['title'] ); ?></span>
                        <?php if ( $decision_tree['visitedAt'] ) : ?>
                            <span class="foxo-activity__date">
                                <?php printf( esc_html__( 'Navštíveno: %s', 'cba' ), esc_html( date_i18n( 'd. n. Y H:i', strtotime( $decision_tree['visitedAt'] ) ) ) ); ?>
                            </span>
                        <?php endif; ?>
                        <?php if ( ! empty( $decision_tree['lastResultNodeUid'] ) ) : ?>
                            <span class="foxo-badge foxo-badge--outline"><?php esc_html_e( 'Dokončeno', 'cba' ); ?></span>
                        <?php endif; ?>
                        <a href="<?php echo esc_url( $decision_tree['url'] ); ?>" class="<?php echo d1g1B::btn_class('outline'); ?>">
                            <?php esc_html_e( 'Spustit znovu', 'cba' ); ?>
                        </a>
                    </div>
                <?php endif; ?>

            </div>
        <?php endif; ?>
    </div>
</div>
