<?php
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

while ( have_posts() ) : the_post();
    $tree = FoxoDecisionTree::from_post( get_post() );
?>

<div class="foxo-page-banner">
    <div class="container">
        <?php get_template_part( 'parts/breadcrumbs' ); ?>
        <h1 class="foxo-page-banner__title"><?php the_title(); ?></h1>
    </div>
</div>

<div class="foxo-page-content container">
    <div class="foxo-page-content__main">
        <?php get_template_part( 'template-parts/learning/decision-tree/decision-tree', null, [ 'tree' => $tree ] ); ?>
    </div>
</div>

<?php endwhile; ?>

<?php get_footer(); ?>
