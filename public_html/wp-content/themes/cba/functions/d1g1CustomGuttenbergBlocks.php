<?php 

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Bloky
 *
 * 
 * @author Digihood
 */ 

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}
//původní jméno classy customGuttenbergBlocks
if( ! class_exists( 'd1g1CustomGuttenbergBlocks' ) )
{
	class d1g1CustomGuttenbergBlocks
	{

		public function __construct()
		{
			
			//register scripts
			add_action('acf/init', [$this,'block_acf_init']);

            //wrap default guttenberg sections
            add_filter( 'render_block', [$this,'wrap_classic_block'], 10, 2 );

		}

        /**
        * 	Obalí prvky guttenbergu do zvláštního tagz
        *       
        * 	@author Digihood
        * 	@return void
        */
        function wrap_classic_block( $block_content, $block ) {
        
            if ( ! empty( $block_content ) && ! ctype_space( $block_content ) && isset( $block['blockName'] ) ) {

                if ( self::wrap_container( $block['blockName'] ) ) {
                    $block_content = '<div class="container">' . $block_content . '</div>';
                }
                
            }
        
            return $block_content;
        }

        /**
        * 	Rozhodnce, které sekce se mají obalit do gridu
        *       
        * 	@author Digihood
        * 	@return void
        */
        private static function wrap_container($string) {
            // Bloky, které nikdy nemají mít container
            $never_wrap = ['core/list-item'];
            
            if (in_array($string, $never_wrap)) {
                return false;
            }
        
            // Bloky, které mají být vždy obalené
            if (strpos($string, 'core/') !== false ||
                $string == 'acf/d1g1-tabs' ||
                $string == 'acf/d1g1-accordion' ||
                $string == 'acf/d1g1-three-columns-list') {
                return true;
            }
        
            return false;
        }

        /**
        * 	Registrace Guttenberg sekcí
        *       
        * 	@author Digihood
        * 	@return void
        */
        function block_acf_init() {

            // check function exists
            if( function_exists('acf_register_block') ) {

                $gutt_arrays = 
                [
                    [
                        'd1g1_tabs',
                        __('Záložky d1g1', 'cba'),
                        __('Vložte do stránky vlastní záložky', 'cba'),
                        [$this,'digi_tabs'],
                        'formatting',
                        'images-alt',
                        [ 'tabs', 'záložky' ],
                        'edit',
                    ],
                    //tři sloupce
                    [
                        'd1g1_three_columns_list',
                        __('3 sloupce d1g1', 'cba'),
                        __('Sekce 3 sloupců', 'cba'),
                        [$this,'digi_three_columns'],
                        'formatting',
                        'grid-view',
                        [ 'sluzby', 'medailonek' ],
                        'edit',
                    ],
                    //slick slider
                    [
                        'd1g1_slider',
                        __('Slider d1g1', 'cba'),
                        __('Slider', 'cba'),
                        [$this,'digi_slide_list'],
                        'widget',
                        'cover-image',
                        [ 'slider', 'd1g1-slider' ],
                        'edit',
                    ]
                ];
            
                if ( !empty( $gutt_arrays ) ) {
            
                    foreach ($gutt_arrays as $array ) {
                        
                        acf_register_block(array(
                            'name' => $array[0],
                            'title'   => $array[1],
                            'description'   => $array[2],
                            'render_callback' => $array[3],
                            'category'  => $array[4],
                            'icon'  => $array[5],
                            'keywords'   => $array[6],
                            'mode' => $array[7]
                        ));
            
                    }
            
                }
                
            }
        
        }
        
        /**
        * 	Callback pro tabs sekci
        *       
        * 	@author Digihood
        * 	@return void
        */
        function digi_tabs( $block ) {
        
            if( file_exists( get_theme_file_path("/parts/block/tabs-block.php") ) ) {
                include( get_theme_file_path("/parts/block/tabs-block.php") );
            }
        
        }

        /**
        * 	Callback pro 4 sekce
        *       
        * 	@author Digihood
        * 	@return void
        */
        function digi_three_columns( $block ) {
        
            if( file_exists( get_theme_file_path("/parts/block/three-columns.php") ) ) {
                include( get_theme_file_path("/parts/block/three-columns.php") );
            }
        
        }

        /**
        * 	Callback pro 4 sekce
        *       
        * 	@author Digihood
        * 	@return void
        */
        function digi_slide_list( $block ) {
        
            if( file_exists( get_theme_file_path("/parts/block/slick-slider.php") ) ) {
                include( get_theme_file_path("/parts/block/slick-slider.php") );
            }
        
        }       

	}

}

new d1g1CustomGuttenbergBlocks;
