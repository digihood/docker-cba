<?php

/**
 * Block Name: d1g1 Accordion
 *
 * This is the template that displays the testimonial block.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$id_block = 'd1g1_tabs-' . $block['id'];

if( have_rows('page_tabs') ): 

    $ul = [];

    $div = [];

    $ct=0;

    while ( have_rows('page_tabs') ) : the_row();

        $ul[$ct] = get_sub_field('title_tabs');

        $div[$ct] = get_sub_field('content_tabs');  

        $ct++;

    endwhile; 

endif;

?>

<div id="<?php echo $id_block; ?>" class="d1g1-tabs-wrap d1g1-block">

    <?php if (!empty($ul) && !empty($div)) : ?>

        <ul class="tabs" data-tabs id="tabs-<?= $block['id']?>">

            <?php foreach ($ul as $id => $li) { ?>

                <li class="tabs-title <?= ($id == 0 ? 'is-active' : '')?>"><a href="#panel<?= $id?>" <?= ($id == 0 ? 'aria-selected="true"' : '')?> ><?= $li ?></a></li>

            <?php }?>

        </ul>

        <div class="tabs-content" data-tabs-content="tabs-<?= $block['id']?>">

            <?php 

            foreach ($div as $id => $content) { ?>

                <div class="tabs-panel <?= ($id == 0 ? 'is-active' : '')?>" id="panel<?= $id?>">

                <?= $content ?>

                </div>

            <?php } ?>

        </div>

    <?php endif; ?>

</div>