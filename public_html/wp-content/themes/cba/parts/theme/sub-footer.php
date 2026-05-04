<?php 

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

?>
<div class="py-8">
  <?php 

  d1g1B::container(true);

    d1g1B::cell( 4, 3, 12 ); ?>

        <aside itemscope itemtype="http://schema.org/WPSideBar"> 
          <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer 1')) : ?>
                        
          <?php endif; ?> 
        </aside>

      <?php d1g1B::end_cell( );   
      
      d1g1B::cell( 4, 3, 12 ); ?>

        <aside itemscope itemtype="http://schema.org/WPSideBar"> 
          <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer 2')) : ?>
                        
          <?php endif; ?> 
        </aside>

      <?php d1g1B::end_cell( );   

      d1g1B::cell( 4, 3, 12 ); ?>

        <aside itemscope itemtype="http://schema.org/WPSideBar"> 
          <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer 3')) : ?>
                        
          <?php endif; 
          
          get_template_part( 'parts/social-list' );
          
          ?>        
        </aside>

      <?php d1g1B::end_cell( ); 
    
  d1g1B::end_container();

  ?>
  
</div>