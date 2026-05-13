<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$user_id = get_current_user_id();
$data    = FoxoUserLearningService::get_user_learning_data( $user_id );
$last    = $data['lastVisited']['decisionTree'] ?? null;

$trees = get_posts( [
    'post_type'      => 'foxo_decision_tree',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'meta_query'     => [ [ 'key' => 'tree_active', 'value' => '1' ] ],
] );
?>

<div class="account-card foxo-account-trees">
    <div class="account-card__header">
        <h2 class="account-card__title"><?php esc_html_e( 'Průvodci a doporučení', 'cba' ); ?></h2>
    </div>
    <div class="account-card__body">

        <?php if ( $last && ! empty( $last['id'] ) ) : ?>
            <div class="foxo-account-trees__last">
                <h3 class="foxo-account-trees__last-label"><?php esc_html_e( 'Naposledy spuštěný průvodce', 'cba' ); ?></h3>
                <div class="foxo-activity__item">
                    <span class="foxo-activity__type"><?php esc_html_e( 'Průvodce', 'cba' ); ?></span>
                    <span class="foxo-activity__title"><?php echo esc_html( $last['title'] ); ?></span>
                    <?php if ( $last['visitedAt'] ) : ?>
                        <span class="foxo-activity__date">
                            <?php printf( esc_html__( 'Navštíveno: %s', 'cba' ), esc_html( date_i18n( 'd. n. Y H:i', strtotime( $last['visitedAt'] ) ) ) ); ?>
                        </span>
                    <?php endif; ?>
                    <?php if ( ! empty( $last['lastResultNodeUid'] ) ) : ?>
                        <span class="foxo-badge foxo-badge--outline"><?php esc_html_e( 'Dokončeno', 'cba' ); ?></span>
                    <?php endif; ?>
                    <a href="<?php echo esc_url( $last['url'] ); ?>" class="<?php echo d1g1B::btn_class( 'outline' ); ?>">
                        <?php esc_html_e( 'Spustit znovu', 'cba' ); ?>
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <?php if ( ! $trees ) : ?>
            <p class="foxo-notice"><?php esc_html_e( 'Momentálně nejsou dostupné žádné průvodce.', 'cba' ); ?></p>
        <?php else : ?>
            <div class="foxo-tree-list">
                <?php foreach ( $trees as $t ) :
                    $intro = (string) get_field( 'tree_intro_text', $t->ID );
                ?>
                    <div class="foxo-tree-card">
                        <h3 class="foxo-tree-card__title">
                            <a href="<?php echo esc_url( get_permalink( $t->ID ) ); ?>"><?php echo esc_html( $t->post_title ); ?></a>
                        </h3>
                        <?php if ( $intro ) : ?>
                            <p class="foxo-tree-card__intro"><?php echo esc_html( $intro ); ?></p>
                        <?php endif; ?>
                        <a href="<?php echo esc_url( get_permalink( $t->ID ) ); ?>" class="<?php echo d1g1B::btn_class( 'primary' ); ?>">
                            <?php esc_html_e( 'Spustit průvodce', 'cba' ); ?>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</div>
