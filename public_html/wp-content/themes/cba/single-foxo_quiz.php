<?php
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

while ( have_posts() ) : the_post();
    $quiz = FoxoQuiz::from_post( get_post() );
?>

<div class="foxo-page-banner">
    <div class="container">
        <?php get_template_part( 'parts/breadcrumbs' ); ?>
        <h1 class="foxo-page-banner__title"><?php the_title(); ?></h1>
        <div class="foxo-page-banner__meta">
            <?php if ( $quiz->requiredScore ) : ?>
                <span class="foxo-badge"><?php printf( esc_html__( 'Potřebné skóre: %d %%', 'cba' ), $quiz->requiredScore ); ?></span>
            <?php endif; ?>
            <?php if ( $quiz->allowRepeat ) : ?>
                <span class="foxo-badge foxo-badge--outline"><?php esc_html_e( 'Lze opakovat', 'cba' ); ?></span>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="foxo-page-content container">
    <div class="foxo-page-content__main">
        <?php get_template_part( 'template-parts/learning/quiz/quiz-form', null, [ 'quiz' => $quiz ] ); ?>
    </div>
</div>

<?php endwhile; ?>

<?php get_footer(); ?>
