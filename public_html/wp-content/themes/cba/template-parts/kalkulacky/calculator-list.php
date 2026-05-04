<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$calculators = new WP_Query( array(
    'post_type'      => 'calculator',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order title',
    'order'          => 'ASC',
) );
?>

<div class="calc-list">
    <div class="calc-list__inner">

        <?php if ( $calculators->have_posts() ) : ?>

            <div class="calc-list__grid">

                <?php while ( $calculators->have_posts() ) : $calculators->the_post();
                    $post_id   = get_the_ID();
                    $permalink = get_permalink();
                    $title     = get_the_title();
                    $excerpt   = get_the_excerpt();
                    $thumb     = get_the_post_thumbnail_url( $post_id, 'medium' );
                    $calc_obj  = new CbaCalculator( $post_id );
                    $icon      = $calc_obj->get_icon();
                ?>

                <a href="<?php echo esc_url( $permalink ); ?>" class="calc-card">
                    <div class="calc-card__icon-wrap">
                        <?php if ( $thumb ) : ?>
                            <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( $title ); ?>" class="calc-card__thumb">
                        <?php else : ?>
                            <span class="calc-card__icon"><?php echo $icon; ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="calc-card__body">
                        <h2 class="calc-card__title"><?php echo esc_html( $title ); ?></h2>
                        <?php if ( $excerpt ) : ?>
                            <p class="calc-card__desc"><?php echo esc_html( $excerpt ); ?></p>
                        <?php else : ?>
                            <p class="calc-card__desc">Otevřít kalkulačku a zjistit výsledky okamžitě.</p>
                        <?php endif; ?>
                        <span class="calc-card__cta">Spustit kalkulačku &rarr;</span>
                    </div>
                </a>

                <?php endwhile; wp_reset_postdata(); ?>

            </div>

        <?php else : ?>
            <div class="calc-list__empty">
                <p>Zatím nejsou žádné kalkulačky k dispozici.</p>
            </div>
        <?php endif; ?>

    </div>
</div>
