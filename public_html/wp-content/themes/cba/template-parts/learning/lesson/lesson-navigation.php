<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Lesson navigation + progress dots.
 * $args: lesson_id (int), course_id (int), lessons (FoxoCourseLessonItem[])
 */
$lesson_id = (int) ( $args['lesson_id'] ?? 0 );
$course_id = (int) ( $args['course_id'] ?? 0 );
$lessons   = $args['lessons']   ?? [];
$user_id   = get_current_user_id();

if ( ! $lesson_id || ! $lessons ) return;

$current_item = null;
foreach ( $lessons as $item ) {
    if ( $item->lessonId === $lesson_id ) { $current_item = $item; break; }
}
if ( ! $current_item ) return;
?>

<!-- Progress dots -->
<nav class="foxo-lesson-dots" aria-label="<?php esc_attr_e( 'Postup kurzem', 'cba' ); ?>">
    <?php foreach ( $lessons as $dot_item ) :
        if ( $dot_item->current ) {
            $state = 'current';
        } elseif ( $dot_item->completed ) {
            $state = 'completed';
        } else {
            $state = 'available';
        }
    ?>
        <a
            href="<?php echo esc_url( $dot_item->url ); ?>"
            class="foxo-lesson-dot foxo-lesson-dot--<?php echo esc_attr( $state ); ?>"
            title="<?php echo esc_attr( $dot_item->position . '. ' . $dot_item->title ); ?>"
            aria-label="<?php printf( esc_attr__( 'Lekce %d: %s', 'cba' ), $dot_item->position, $dot_item->title ); ?>"
            <?php if ( $dot_item->current ) : ?> aria-current="step"<?php endif; ?>
        ></a>
    <?php endforeach; ?>
</nav>

<!-- Lesson navigation -->
<div class="foxo-lesson-nav">
    <div class="foxo-lesson-nav__left">
        <?php if ( $current_item->previousLessonId ) : ?>
            <a
                href="<?php echo esc_url( add_query_arg( 'course', $course_id, get_permalink( $current_item->previousLessonId ) ) ); ?>"
                class="<?php echo d1g1B::btn_class('outline'); ?>"
            >
                ← <?php esc_html_e( 'Předchozí lekce', 'cba' ); ?>
            </a>
        <?php endif; ?>
    </div>

    <div class="foxo-lesson-nav__center">
        <?php if ( $course_id ) : ?>
            <a href="<?php echo esc_url( get_permalink( $course_id ) ); ?>" class="foxo-lesson-nav__course-link">
                <?php echo esc_html( get_the_title( $course_id ) ); ?>
            </a>
            <span class="foxo-lesson-nav__position">
                <?php printf( esc_html__( '%1$d / %2$d', 'cba' ), $current_item->position, count( $lessons ) ); ?>
            </span>
        <?php endif; ?>
    </div>

    <div class="foxo-lesson-nav__right">
        <?php if ( $current_item->nextLessonId ) : ?>
            <a
                href="<?php echo esc_url( add_query_arg( 'course', $course_id, get_permalink( $current_item->nextLessonId ) ) ); ?>"
                class="<?php echo d1g1B::btn_class('primary'); ?>"
            >
                <?php esc_html_e( 'Další lekce', 'cba' ); ?> →
            </a>
        <?php else : ?>
            <?php
            $final_quiz_id = $course_id ? (int) get_field( 'foxo_course_final_quiz', $course_id ) : 0;
            if ( $final_quiz_id ) : ?>
                <a href="<?php echo esc_url( get_permalink( $final_quiz_id ) ); ?>" class="<?php echo d1g1B::btn_class('primary'); ?>">
                    <?php esc_html_e( 'Závěrečný kvíz', 'cba' ); ?> →
                </a>
            <?php elseif ( $course_id ) : ?>
                <a href="<?php echo esc_url( get_permalink( $course_id ) ); ?>" class="<?php echo d1g1B::btn_class('outline'); ?>">
                    <?php esc_html_e( 'Zpět na kurz', 'cba' ); ?>
                </a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Complete lesson button -->
<?php if ( $user_id ) : ?>
    <div class="foxo-lesson-complete">
        <?php
        $is_completed = false;
        $rows = FoxoLearningDB::get_lesson_data( $user_id, $lesson_id, $course_id );
        foreach ( $rows as $r ) {
            if ( $r['data_uid'] === 'completed' && $r['data_value'] === 'true' ) { $is_completed = true; break; }
        }
        ?>
        <button
            type="button"
            id="foxo-complete-btn"
            class="<?php echo d1g1B::btn_class('success'); ?><?php echo $is_completed ? ' button--completed' : ''; ?>"
            data-lesson-id="<?php echo esc_attr( $lesson_id ); ?>"
            data-course-id="<?php echo esc_attr( $course_id ); ?>"
            <?php if ( $is_completed ) : ?> disabled aria-disabled="true"<?php endif; ?>
        >
            <?php echo $is_completed
                ? esc_html__( 'Lekce dokončena ✓', 'cba' )
                : esc_html__( 'Dokončit lekci', 'cba' );
            ?>
        </button>
        <div class="foxo-lesson-complete__msg" id="foxo-complete-msg" role="alert" hidden></div>
    </div>
<?php else : ?>
    <div class="foxo-lesson-login-hint">
        <a href="<?php echo esc_url( linksd1g1::login_registration() ); ?>" class="<?php echo d1g1B::btn_class('outline'); ?>">
            <?php esc_html_e( 'Přihlaste se pro sledování postupu', 'cba' ); ?>
        </a>
    </div>
<?php endif; ?>
