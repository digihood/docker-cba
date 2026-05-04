<?php
/**
 * Block Name: d1g1 Accordion
 *
 * This is the template that displays the testimonial block.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$id = 'd1g1_accordion-' . $block['id'];

?>
<div id="<?php echo $id; ?>" class="d1g1-three-columns d1g1-block pb-10">

    <?php 
    // check if the repeater field has rows of data
    if( have_rows('columns_list') ): 
        echo '<div class="grid grid-cols-12 gap-x-theme">';
            // loop through the rows of data
            while ( have_rows('columns_list') ) : the_row();

                // display a sub field value
                $img = get_sub_field('column_image');
                $header = get_sub_field('columns_header');
                $content = get_sub_field('columns_content');
                $column_link = get_sub_field('column_link');

                d1g1B::cell( 4, 4, 6, true, 'relative slide' );
                
                     if ( $img ) { 
                        d1g1B::img( $img['ID'], "medium", 'three-columns-img text-center pb-6');
                    } 
                    if ( $content || $header ) { ?>
                        <div class="entry-content text-center">
                            <h3 class="text-bold"><?= $header ?></h3>
                            <p><?= $content ?></p>
                            <?php 
                            if ( $column_link ) { 
                                d1g1B::primary_link( __('Viac informacií'), get_permalink( $column_link ) );
                            } 
                            ?>
                        </div>
                    <?php } ?>
                
            <?php d1g1B::end_cell( );
            endwhile; 

        d1g1B::end_container();
    endif;
    ?>
</div>