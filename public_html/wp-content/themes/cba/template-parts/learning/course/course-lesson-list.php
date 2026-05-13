<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/** @var FoxoCourse $course */
$course  = $args['course'] ?? null;
$user_id = get_current_user_id();

if ( ! $course instanceof FoxoCourse ) return;

$lessons = $course->lessons;
if ( ! $lessons ) : ?>
    <p class="foxo-notice"><?php esc_html_e( 'Tento kurz zatím neobsahuje žádné lekce.', 'cba' ); ?></p>
<?php return; endif;

$completed_count = count( array_filter( $lessons, fn( $l ) => $l->completed ) );
$total_count     = count( $lessons );
$first_url       = $lessons[0]->url ?? '#';

// Find last visited lesson from DB
$last_lesson_id = 0;
if ( $user_id ) {
    $rows = FoxoLearningDB::get_course_data( $user_id, $course->id, 'last_lesson', 'last_lesson_id' );
    $last_lesson_id = $rows ? (int) $rows[0]['data_value'] : 0;
}
$last_lesson_url = $last_lesson_id
    ? add_query_arg( 'course', $course->id, get_permalink( $last_lesson_id ) )
    : null;
?>

<div class="foxo-course__lessons">

    <div class="foxo-course__lessons-header">
        <h2 class="foxo-course__lessons-title"><?php esc_html_e( 'Obsah kurzu', 'cba' ); ?></h2>
        <?php if ( $user_id ) : ?>
            <span class="foxo-course__lessons-progress">
                <?php printf(
                    esc_html__( 'Dokončeno: %1$d / %2$d lekcí (%3$d %%)', 'cba' ),
                    $completed_count,
                    $total_count,
                    $course->progress
                ); ?>
            </span>
        <?php endif; ?>
    </div>

    <?php if ( $user_id ) : ?>
        <div class="foxo-progress-bar">
            <div class="foxo-progress-bar__fill" style="width: <?php echo esc_attr( $course->progress ); ?>%"></div>
        </div>
    <?php endif; ?>

    <ol class="foxo-course__lesson-list">
        <?php foreach ( $lessons as $item ) : ?>
            <li class="foxo-course__lesson-item foxo-course__lesson-item--<?php echo esc_attr( $item->completed ? 'completed' : 'available' ); ?>">
                <a href="<?php echo esc_url( $item->url ); ?>" class="foxo-course__lesson-link">
                    <span class="foxo-course__lesson-num"><?php echo esc_html( $item->position ); ?></span>
                    <span class="foxo-course__lesson-title"><?php echo esc_html( $item->title ); ?></span>
                    <?php if ( $item->completed ) : ?>
                        <span class="foxo-course__lesson-check" aria-label="<?php esc_attr_e( 'Dokončeno', 'cba' ); ?>">✓</span>
                    <?php endif; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ol>

    <div class="foxo-course__actions">
        <?php if ( $last_lesson_url && $last_lesson_id ) : ?>
            <a href="<?php echo esc_url( $last_lesson_url ); ?>" class="<?php echo d1g1B::btn_class('primary'); ?>">
                <?php esc_html_e( 'Pokračovat v kurzu', 'cba' ); ?>
            </a>
        <?php else : ?>
            <a href="<?php echo esc_url( $first_url ); ?>" class="<?php echo d1g1B::btn_class('primary'); ?>">
                <?php esc_html_e( 'Začít kurz', 'cba' ); ?>
            </a>
        <?php endif; ?>

        <?php if ( $course->finalQuizId ) : ?>
            <a href="<?php echo esc_url( get_permalink( $course->finalQuizId ) ); ?>" class="<?php echo d1g1B::btn_class('outline'); ?>">
                <?php esc_html_e( 'Závěrečný kvíz', 'cba' ); ?>
            </a>
        <?php endif; ?>
    </div>

</div>
