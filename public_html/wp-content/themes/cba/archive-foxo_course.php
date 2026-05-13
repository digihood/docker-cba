<?php
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();
$user_id = get_current_user_id();
?>

<div class="foxo-page-banner">
    <div class="container">
        <h1 class="foxo-page-banner__title"><?php esc_html_e( 'Online akademie', 'cba' ); ?></h1>
        <p class="foxo-page-banner__sub"><?php esc_html_e( 'Vzdělávejte se v oblasti osobních financí.', 'cba' ); ?></p>
    </div>
</div>

<div class="foxo-page-content container">
    <div class="foxo-archive-grid">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post();
            $active = get_field( 'foxo_course_active' );
            if ( ! $active ) continue;
            $intro    = get_field( 'foxo_course_intro' );
            $level    = get_field( 'foxo_course_level' );
            $duration = get_field( 'foxo_course_duration' );
            $access   = get_field( 'foxo_course_access_mode' ) ?: 'public';
            $lessons  = get_field( 'foxo_course_lessons' );
            $count    = is_array( $lessons ) ? count( $lessons ) : 0;
            $progress = $user_id ? FoxoCourseService::get_progress( get_the_ID(), $user_id ) : 0;
        ?>
            <article class="foxo-course-card">
                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="foxo-course-card__thumb">
                        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium' ); ?></a>
                    </div>
                <?php endif; ?>
                <div class="foxo-course-card__body">
                    <h2 class="foxo-course-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <?php if ( $intro ) : ?>
                        <p class="foxo-course-card__intro"><?php echo esc_html( $intro ); ?></p>
                    <?php endif; ?>
                    <div class="foxo-course-card__meta">
                        <?php if ( $level ) : ?><span class="foxo-badge"><?php echo esc_html( $level ); ?></span><?php endif; ?>
                        <?php if ( $duration ) : ?><span class="foxo-badge foxo-badge--outline"><?php echo esc_html( $duration ); ?></span><?php endif; ?>
                        <span class="foxo-badge foxo-badge--outline"><?php printf( esc_html__( '%d lekcí', 'cba' ), $count ); ?></span>
                        <?php if ( $access === 'login_required' ) : ?>
                            <span class="foxo-badge foxo-badge--lock"><?php esc_html_e( 'Po přihlášení', 'cba' ); ?></span>
                        <?php endif; ?>
                    </div>
                    <?php if ( $user_id && $progress > 0 ) : ?>
                        <div class="foxo-progress-bar foxo-progress-bar--sm">
                            <div class="foxo-progress-bar__fill" style="width: <?php echo esc_attr( $progress ); ?>%"></div>
                        </div>
                    <?php endif; ?>
                    <a href="<?php the_permalink(); ?>" class="<?php echo d1g1B::btn_class('primary'); ?>">
                        <?php echo $progress > 0
                            ? esc_html__( 'Pokračovat', 'cba' )
                            : esc_html__( 'Zahájit kurz', 'cba' );
                        ?>
                    </a>
                </div>
            </article>
        <?php endwhile; else : ?>
            <p class="foxo-notice"><?php esc_html_e( 'Zatím nejsou dostupné žádné kurzy.', 'cba' ); ?></p>
        <?php endif; ?>
    </div>
    <?php get_template_part( 'parts/pagination' ); ?>
</div>

<?php get_footer(); ?>
