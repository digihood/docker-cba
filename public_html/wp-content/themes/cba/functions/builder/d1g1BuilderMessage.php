<?php

/**
 * Vkládání obrázků 
 *
 * 
 * @author Digihood
 */ 

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if( ! class_exists( 'd1g1BuilderMessage' ) )
{
	class d1g1BuilderMessage
	{

		public function __construct()
		{

        }

        /**
         * Zobrazí zprávu
         *
         * @param $content = jméno session
         * @param $class = třída
         * @param $btn_link = odkaz tlačítka
         * @param $btn_text = text tlačítka
         * 
         * @author Digihood
         * @return true/false
         */ 

        public static function get_message( $content, $class="", $btn_link="", $btn_text="" ) 
        { 
            $has_button = false;
            if ( $btn_link && $btn_text ) {
                $has_button = true;
            }
        ?>
        <div class="callout <?php echo  $class; ?>">
            <div class="grid grid-cols-12 gap-x-theme">
                <div class="col-span-<?php if ( $has_button ) echo "8"; else echo "12"; ?>">
                    <?php echo $content; ?>
                </div>
                <?php if ( $has_button ) { ?>
                <div class="md:col-span-4 col-span-12 md:text-right">
                    <a href="<?php echo $btn_link; ?>" class="button">
                        <?php echo $btn_text; ?>
                    </a>
                </div>
                <?php } ?>
            </div>
        </div>
        <?php 
        }

    }
}