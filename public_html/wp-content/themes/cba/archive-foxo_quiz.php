<?php
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();
?>

<div class="foxo-page-banner">
    <div class="container">
        <h1 class="foxo-page-banner__title"><?php esc_html_e( 'Kvízy', 'cba' ); ?></h1>
        <p class="foxo-page-banner__sub"><?php esc_html_e( 'Otestujte své znalosti v oblasti osobních financí.', 'cba' ); ?></p>
    </div>
</div>

<div class="foxo-page-content container">
    <div class="foxo-archive-grid">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post();
            $active = get_field( 'foxo_quiz_active' );
            if ( ! $active ) continue;
            $intro = get_field( 'foxo_quiz_intro' );
        ?>
            <article class="foxo-quiz-card">
                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="foxo-quiz-card__thumb">
                        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium' ); ?></a>
                    </div>
                <?php endif; ?>
                <div class="foxo-quiz-card__body">
                    <h2 class="foxo-quiz-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <?php if ( $intro ) : ?>
                        <p class="foxo-quiz-card__intro"><?php echo esc_html( $intro ); ?></p>
                    <?php endif; ?>
                    <a href="<?php the_permalink(); ?>" class="<?php echo d1g1B::btn_class('primary'); ?>">
                        <?php esc_html_e( 'Spustit kvíz', 'cba' ); ?>
                    </a>
                </div>
            </article>
        <?php endwhile; else : ?>
            <p class="foxo-notice"><?php esc_html_e( 'Zatím nejsou dostupné žádné kvízy.', 'cba' ); ?></p>
        <?php endif; ?>
    </div>
    <?php get_template_part( 'parts/pagination' ); ?>
</div>

<?php get_footer(); ?>
