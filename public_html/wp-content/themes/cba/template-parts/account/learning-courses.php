<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$user_id = get_current_user_id();
$courses = FoxoCourseService::get_all_active( $user_id );
?>

<div class="account-card foxo-account-courses">
    <div class="account-card__header">
        <h2 class="account-card__title"><?php esc_html_e( 'Online akademie – kurzy', 'cba' ); ?></h2>
    </div>
    <div class="account-card__body">
        <?php if ( ! $courses ) : ?>
            <p class="foxo-notice"><?php esc_html_e( 'Momentálně nejsou dostupné žádné kurzy.', 'cba' ); ?></p>
        <?php else : ?>
            <div class="foxo-course-list">
                <?php foreach ( $courses as $course ) :
                    $rows            = FoxoLearningDB::get_course_data( $user_id, $course->id, 'last_lesson', 'last_lesson_id' );
                    $last_lesson_id  = $rows ? (int) $rows[0]['data_value'] : 0;
                    $continue_url    = $last_lesson_id
                        ? add_query_arg( 'course', $course->id, get_permalink( $last_lesson_id ) )
                        : ( $course->lessons ? $course->lessons[0]->url : $course->url );
                    $lessons_count   = count( $course->lessons );
                    $completed_count = count( array_filter( $course->lessons, fn( $l ) => $l->completed ) );
                ?>
                    <div class="foxo-course-card">
                        <?php if ( $course->image ) : ?>
                            <div class="foxo-course-card__thumb">
                                <img src="<?php echo esc_url( $course->image ); ?>" alt="<?php echo esc_attr( $course->title ); ?>" loading="lazy">
                            </div>
                        <?php endif; ?>
                        <div class="foxo-course-card__body">
                            <h3 class="foxo-course-card__title">
                                <a href="<?php echo esc_url( $course->url ); ?>"><?php echo esc_html( $course->title ); ?></a>
                            </h3>
                            <?php if ( $course->intro ) : ?>
                                <p class="foxo-course-card__intro"><?php echo esc_html( $course->intro ); ?></p>
                            <?php endif; ?>
                            <div class="foxo-course-card__meta">
                                <span><?php printf( esc_html__( '%d lekcí', 'cba' ), $lessons_count ); ?></span>
                                <span><?php printf( esc_html__( 'Dokončeno: %d / %d', 'cba' ), $completed_count, $lessons_count ); ?></span>
                            </div>
                            <div class="foxo-progress-bar foxo-progress-bar--sm">
                                <div class="foxo-progress-bar__fill" style="width: <?php echo esc_attr( $course->progress ); ?>%"></div>
                            </div>
                            <span class="foxo-course-card__pct"><?php echo esc_html( $course->progress ); ?> %</span>
                            <a href="<?php echo esc_url( $continue_url ); ?>" class="<?php echo d1g1B::btn_class('primary'); ?>">
                                <?php echo $last_lesson_id
                                    ? esc_html__( 'Pokračovat', 'cba' )
                                    : esc_html__( 'Začít kurz', 'cba' );
                                ?>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
