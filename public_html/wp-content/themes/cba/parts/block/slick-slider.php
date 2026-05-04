<?php 
/**
 * Block Name: Seznam projektů
 *
 * This is the template that displays the testimonial block.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$id = 'd1g1_accordion-' . $block['id'];

?>
<div id="<?php echo $id; ?>" class="d1g1-project-slick d1g1-block pb-10">

    <?php // check if the repeater field has rows of data
    if( have_rows('block_slider') ):

        $ct = 0; 

        // loop through the rows of data
        while ( have_rows('block_slider') ) : the_row();

            // display a sub field value
            $img = get_sub_field('slide_img');
            $header = get_sub_field('slide_header');
            $btn = get_sub_field('slide_btn');

            if ( $img && $header ) { 

                d1g1B::container(true);

	                d1g1B::cell( 4, 4, 6, true, 'relative slide' );

                        echo '<div class="slide-content">';

                            if ( $ct == 0 && is_front_page() ) {
                                echo '<h1>'. $header .'</h1>';
                            } else {
                                echo '<h2 class="h1">'. $header .'</h2>';
                            }

                            if ( $btn ) {
                                echo '<div class="py-10">';
                                    d1g1B::link( $btn['title'], $btn['url'], 'button', '', $btn['target'] );
                                echo '</div>';
                            } 

                        echo '</div>';
                    
                        d1g1B::img( $img['ID'], 'full' );

                    d1g1B::end_cell();
                
                d1g1B::end_container();
            }

            
            $ct++;
        endwhile;

    endif; 
    ?>
</div>