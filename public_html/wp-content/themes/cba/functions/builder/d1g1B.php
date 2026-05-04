<?php

/**
 * Funckce pro práci s html v šabloně 
 *
 * 
 * @author Digihood
 */ 

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if( ! class_exists( 'd1g1B' ) )
{
	class d1g1B
	{

		public function __construct()
		{
            //add_action('init', [$this, 'test']);
        }

        public function test(){
            if ( !isset($_GET['test_builder']) || $_GET['test_builder'] != 1 ) return;
            $title = 'Nějaký nadpis';
            //$class = 'tri'; 
            $class = array('jedna', 'dva');
            $id = 'idicko';
            $link = 'https://digihood.cz';
            $attribute = ['target'=> '_blank', 'download' => $link, 'data-atribute'=>'test'];
            $thumb_id = 58;
            self::link($title, $link, $class, $id, $attribute);
            self::primary_link($title, $link);
            self::basic_img($thumb_id, 'medium');
            self::icon('person', 'testetets');
            self::section('test', 'idtest');
            echo 'jooo';
            self::end_section();
            self::container(true, true, 'py-10','jedna', 'idid');
            self::cell(7,8,12, true, 'jedna', 'idecko');
            echo 'jojojojo';
            self::end_cell();
            self::end_container(true);

            die('<br>anoanoa');
        }
        
// sekce pro linky        
        /**
        * 	Description 
        *
        * 	@param $text = title links
        *	@param $link = href
        *   @param $class = array('class1','class2') / ''class1'
        *	@param $id = 'id'
        *   @param $attribute = array('target'=>'_blank', ... ) 
        * 
        * 	@author Digihood
        * 	@return echo
        */
        public static function link( $title, $link, $class='', $id="", $attribute=[]) { 
            echo d1g1BuilderButton::get_link($title, $link, $class, $id, $attribute);
        }
        // defaultní primary button z linku
        public static function primary_link($title, $link, $attribute=""){
            echo self::link($title, $link, 'button primary', '', $attribute);
        }

        // defaultní secondary button z linku
        public static function secondary_link($title, $link, $attribute=""){
            echo self::link($title, $link, 'button secondary', '', $attribute);
        }

//sekce pro obrázky a ikony
        /**
        * 	Description 
        *
        * 	@param $thumbnail_id = id obrázku
        *	@param $size = velikost obrázku
        *   @param $figureClass = string !!!
        *   @param $id - id svg
        *   @param $data_attributes - ['data'=>'value=""'] 
        *	@param $is_gallery =  true / fasle
        * 
        * 	@author Digihood
        * 	@return echo
        */
        public static function img($thumbnail_id, $size="", $figureClass="",$id="", $data_attributes=[]){
            echo d1g1BuilderImage::basic_img($thumbnail_id, $size, $figureClass, $id, $data_attributes);
        }

        /**
         * Přidání svg ikony pro return
         *
         * @param $value - name svg
         * @param $classs - class svg
         * @param $id - id svg
         * @param $data_attributes - ['data'=>'value=""'] 
         * 
         * @author Digihood
         * @return echo/false
         */ 

        public static function get_icon($value, $class="",$id="", $data_attributes=[]){
            return d1g1BuilderImage::get_icon($value, $class, $id, $data_attributes);
        }
                /**
         * Přidání svg ikony
         *
         * @param $value - name svg
         * @param $classs - class svg
         * @param $id - id svg
         * @param $data_attributes - ['data'=>'value=""'] 
         * 
         * @author Digihood
         * @return echo/false
         */ 
        public static function icon($value, $class="",$id="", $data_attributes=[]){
            echo d1g1BuilderImage::the_icon($value, $class, $id, $data_attributes);
        }

        /**
         * Logo
         *
         * @param $value - name svg
         * @param $classs - class svg
         * @param $id - id svg
         * @param $data_attributes - ['data'=>'value=""'] 
         * 
         * @author Digihood
         * @return echo/false
         */ 
        public static function logo($url, $width, $height, $id="", $data_attributes=[] ){
            echo d1g1BuilderImage::get_logo($url, $width, $height, $id, $data_attributes);
        }

// sekce pro grid a section

        /**
         * ohraničení sekce
         *
         * @param $classs - class section
         * @param $id - id section
         * 
         * @author Digihood
         * @return echo/false
         */ 
        public static function section($class="", $id=""){
            echo d1g1BuilderGrid::section($class, $id);
        }

        // ukončení sekce
        public static function end_section($class="", $id=""){
            echo d1g1BuilderGrid::end_section($class, $id);
        }

        /**
         * ohraničení grid containerem
         * @param $grid_x - true / false
         * @param $margin_x - true / false
         * @param $margin_y - true / false
         * @param $section - class (pokud chci přidat div pod container)
         * @param $class - class section
         * @param $id - id section
         * @param array $data_atributes array('atr'=>'value')
         * 
         * @author Digihood
         * @return echo/false
         */ 
        public static function container($grid_x=true, $margin_x=true, $margin_y = false, $section="", $class="", $id="", $data_atributes_to_grid_x=[]){
            echo d1g1BuilderGrid::container($grid_x, $margin_x, $margin_y, $section, $class, $id, $data_atributes_to_grid_x);
        }
        // ukončení sekce
        public static function end_container($grid_x=true,$section=false){
            echo d1g1BuilderGrid::end_container($grid_x, $section);
        }

        /**
         * ohraničení grid containerem
         * @param $margin_x - true / false
         * @param $margin_y - true / false
         * @param $class - class section
         * @param $id - id section
         * 
         * @author Digihood
         * @return echo/false
         */ 
        public static function cell($large='', $medium='', $small='', $cell=true, $class="", $id="", $data_atributes=[]){
            echo d1g1BuilderGrid::cell($large, $medium, $small, $cell, $class, $id, $data_atributes);
        }
        // ukončení sekce
        public static function end_cell(){
            echo d1g1BuilderGrid::end_cell();
        }
        /**
         * ohraničení grid containerem
         * @param $margin_x - true / false
         * @param $margin_y - true / false
         * @param $class - class section
         * @param $id - id section
         * 
         * @author Digihood
         * @return echo/false
         */ 
        public static function grid_x($margin_x=true, $margin_y=false, $class="", $id=""){
            echo d1g1BuilderGrid::grid_x($margin_x, $margin_y, $class, $id);
        }
        // ukončení gridu
        public static function end_grid_x(){
            echo d1g1BuilderGrid::end_cell();
        }

// sekce pro message
        /**
         * ohraničení grid containerem
         * @param $margin_x - true / false
         * @param $margin_y - true / false
         * @param $class - class section
         * @param $id - id section
         * 
         * @author Digihood
         * @return echo/false
         */ 
        public static function message( $content, $class="", $btn_link="", $btn_text="" ){
            echo d1g1BuilderMessage::get_message( $content, $class, $btn_link, $btn_text );
        }
// taby a accordeony
        /**
         * ohraničení grid containerem
         * @param $tabs_data - array viz d1g1BuilderTabs::demo();
         * 
         * @author Digihood
         * @return echo/false
         */ 
        public static function vertical_tabs($tabs_data){
            d1g1BuilderTabs::vertical_tabs($tabs_data, true);
        }
        /**
         * ohraničení grid containerem
         * @param $tabs_data - array - viz d1g1BuilderTabs::demo();
         * 
         * @author Digihood
         * @return echo/false
         */ 
        public static function horizontal_tabs($tabs_data){
            d1g1BuilderTabs::horizontal_tabs($tabs_data, true);
        }

    }

}