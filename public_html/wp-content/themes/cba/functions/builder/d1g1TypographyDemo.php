<?php 
/**
 * Typografie - demo zobrazení
 *
 * 
 * @author Digihood
 */ 

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if( ! class_exists( 'd1g1TypographyDemo' ) )
{
	class d1g1TypographyDemo
	{

		public function __construct()
		{
        }
        public static function demo(){
            return '
            <div class="container grid grid-cols-12 gap-5">
                <div class="col-span-1 md:col-span-1 lg:col-span-1"></div>
                <div class="col-span-2 md:col-span-2 lg:col-span-2"></div>
                <div class="col-span-3 md:col-span-3 lg:col-span-3"></div>
                <div class="col-span-4 md:col-span-4 lg:col-span-4"></div>
                <div class="col-span-5 md:col-span-5 lg:col-span-5"></div>
                <div class="col-span-6 md:col-span-6 lg:col-span-6"></div>
                <div class="col-span-7 md:col-span-7 lg:col-span-7"></div>
                <div class="col-span-8 md:col-span-8 lg:col-span-8"></div>
                <div class="col-span-9 md:col-span-9 lg:col-span-9"></div>
                <div class="col-span-10 md:col-span-10 lg:col-span-10"></div>
                <div class="col-span-11 md:col-span-11 lg:col-span-11"></div>
                <div class="col-span-12 md:col-span-12 lg:col-span-12"></div>
                <h1 class="col-span-12">aaaaa</h1>
                <h2 class="col-span-12">hdsjksdhjksdjlksd</h2>
                <h3 class="col-span-12">hdsjksdhjksdjlksd</h3>
                <h4 class="col-span-12">hdsjksdhjksdjlksd</h4>
                <p class="text-lg col-span-12">Velký text</p>
                <p class="text-xl col-span-12">Větší text</p>
                <p class="col-span-12">Normální text</p>
                <a href="" class="button col-span-12">Default Button</a>
                <a href="" class="button primary col-span-12">Primary</a>
                <a href="" class="button secondary col-span-12">Secondary</a>
                <a href="">regular link</a>
            </div>
            '.do_shortcode('[contact-form-7 id="1024b81" title="Kontaktní formulář 1"]');
        }
    }

}