<?php
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

while ( have_posts() ) : the_post();
    $user_id = get_current_user_id();
    $course  = FoxoCourse::from_post( get_post(), $user_id );

    // Access check
    if ( $course->accessMode !== 'public' && ! $user_id ) :
?>
        <div class="foxo-page-content container">
            <div class="foxo-locked">
                <h2><?php esc_html_e( 'Tento kurz je dostupný pouze po přihlášení.', 'cba' ); ?></h2>
                <a href="<?php echo esc_url( linksd1g1::login_registration() ); ?>" class="<?php echo d1g1B::btn_class('primary'); ?>">
                    <?php esc_html_e( 'Přihlásit se', 'cba' ); ?>
                </a>
            </div>
        </div>
<?php else : ?>

<div class="foxo-page-banner">
    <div class="container">
        <?php get_template_part( 'parts/breadcrumbs' ); ?>
        <h1 class="foxo-page-banner__title"><?php the_title(); ?></h1>
        <div class="foxo-page-banner__meta">
            <?php if ( $course->level ) : ?>
                <span class="foxo-badge"><?php echo esc_html( $course->level ); ?></span>
            <?php endif; ?>
            <?php if ( $course->durationText ) : ?>
                <span class="foxo-badge foxo-badge--outline"><?php echo esc_html( $course->durationText ); ?></span>
            <?php endif; ?>
            <span class="foxo-badge foxo-badge--outline">
                <?php printf( esc_html__( '%d lekcí', 'cba' ), count( $course->lessons ) ); ?>
            </span>
        </div>
    </div>
</div>

<div class="foxo-page-content container">
    <div class="foxo-page-content__layout">

        <div class="foxo-page-content__main">

            <?php if ( $course->intro ) : ?>
                <p class="foxo-course__intro"><?php echo esc_html( $course->intro ); ?></p>
            <?php endif; ?>

            <?php if ( $course->description ) : ?>
                <div class="foxo-course__description"><?php echo wp_kses_post( $course->description ); ?></div>
            <?php endif; ?>

            <?php get_template_part( 'template-parts/learning/course/course-lesson-list', null, [ 'course' => $course ] ); ?>

        </div>

        <aside class="foxo-page-content__sidebar">
            <?php if ( $course->image ) : ?>
                <img src="<?php echo esc_url( $course->image ); ?>" alt="<?php echo esc_attr( $course->title ); ?>" class="foxo-course__sidebar-img">
            <?php elseif ( has_post_thumbnail() ) : ?>
                <?php the_post_thumbnail( 'large', [ 'class' => 'foxo-course__sidebar-img' ] ); ?>
            <?php endif; ?>

            <?php if ( $user_id ) : ?>
                <div class="foxo-course__sidebar-progress">
                    <p><?php printf( esc_html__( 'Váš postup: %d %%', 'cba' ), $course->progress ); ?></p>
                    <div class="foxo-progress-bar">
                        <div class="foxo-progress-bar__fill" style="width: <?php echo esc_attr( $course->progress ); ?>%"></div>
                    </div>
                </div>
            <?php else : ?>
                <div class="foxo-course__sidebar-login">
                    <p><?php esc_html_e( 'Přihlaste se pro sledování postupu.', 'cba' ); ?></p>
                    <a href="<?php echo esc_url( linksd1g1::login_registration() ); ?>" class="<?php echo d1g1B::btn_class('outline'); ?>">
                        <?php esc_html_e( 'Přihlásit se', 'cba' ); ?>
                    </a>
                </div>
            <?php endif; ?>
        </aside>

    </div>
</div>

<?php endif; ?>

<?php endwhile; ?>

<?php get_footer(); ?>
