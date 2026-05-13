<?php
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

while ( have_posts() ) : the_post();
    $user_id   = get_current_user_id();
    $lesson    = FoxoLesson::from_post( get_post() );
    $course_id = (int) ( $_GET['course'] ?? 0 );
    $lessons   = $course_id ? FoxoCourseService::get_lessons( $course_id, $user_id, $lesson->id ) : [];
?>

<div class="foxo-page-banner foxo-lesson-banner">
    <div class="container">
        <?php if ( $course_id ) : ?>
            <a href="<?php echo esc_url( get_permalink( $course_id ) ); ?>" class="foxo-lesson-banner__course-link">
                ← <?php echo esc_html( get_the_title( $course_id ) ); ?>
            </a>
        <?php endif; ?>
        <h1 class="foxo-page-banner__title"><?php the_title(); ?></h1>
        <div class="foxo-page-banner__meta">
            <?php if ( $lesson->type !== 'text' ) : ?>
                <span class="foxo-badge"><?php echo esc_html( $lesson->type === 'video' ? __( 'Video', 'cba' ) : __( 'Kombinovaná', 'cba' ) ); ?></span>
            <?php endif; ?>
            <?php if ( $lesson->durationText ) : ?>
                <span class="foxo-badge foxo-badge--outline"><?php echo esc_html( $lesson->durationText ); ?></span>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="foxo-page-content container">
    <div class="foxo-lesson-layout">

        <article class="foxo-lesson-content">

            <!-- Video -->
            <?php if ( $lesson->videoUrl && in_array( $lesson->type, [ 'video', 'combined' ], true ) ) : ?>
                <div class="foxo-lesson-video">
                    <?php
                    $video_url = esc_url( $lesson->videoUrl );
                    // Auto-embed for YouTube/Vimeo
                    echo wp_oembed_get( $video_url ) ?: '<video src="' . $video_url . '" controls class="foxo-lesson-video__player"></video>';
                    ?>
                </div>
            <?php endif; ?>

            <!-- Main content -->
            <?php if ( $lesson->content ) : ?>
                <div class="foxo-lesson-content__body">
                    <?php echo wp_kses_post( $lesson->content ); ?>
                </div>
            <?php endif; ?>

            <!-- Materials -->
            <?php if ( $lesson->materials ) : ?>
                <div class="foxo-lesson-materials">
                    <h3 class="foxo-lesson-materials__title"><?php esc_html_e( 'Materiály ke stažení', 'cba' ); ?></h3>
                    <ul class="foxo-lesson-materials__list">
                        <?php foreach ( $lesson->materials as $material ) :
                            if ( empty( $material['url'] ) ) continue; ?>
                            <li class="foxo-lesson-materials__item">
                                <a href="<?php echo esc_url( $material['url'] ); ?>" download class="foxo-lesson-materials__link">
                                    <span class="foxo-lesson-materials__icon">↓</span>
                                    <?php echo esc_html( $material['title'] ?: basename( $material['url'] ) ); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

        </article>

        <!-- Navigation + dots + complete button -->
        <?php get_template_part( 'template-parts/learning/lesson/lesson-navigation', null, [
            'lesson_id' => $lesson->id,
            'course_id' => $course_id,
            'lessons'   => $lessons,
        ] ); ?>

    </div>
</div>

<?php endwhile; ?>

<?php get_footer(); ?>
