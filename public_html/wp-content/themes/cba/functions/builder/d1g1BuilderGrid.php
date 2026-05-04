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

if( ! class_exists( 'd1g1BuilderGrid' ) )
{
	class d1g1BuilderGrid
	{

    public static $end_div = '</div>';
    public static $end_section = '</section>'; 
    public static $column = 12;

		public function __construct()
		{

    }

    /**
     * ohraničení sekce
     *
     * @param $class - class section
     * @param $id - id section
     * 
     * @author Digihood
     * @return echo/false
     */ 
    public static function section($class="", $id=""){
      echo '<section '.($class ? 'class="'.$class. '"' : '').($id ? 'id="'.$id. '"' : ''). '>'; 
    }
    // ukončení sekce
    public static function end_section(){
      echo self::$end_section;
    }

    /**
     * ohraničení grid containerem
     * @param $grid-x - true / false
     * @param $margin_x - true / false
     * @param $margin_y - true / false
     * @param $class - class section
     * @param $id - id section
     * 
     * @author Digihood
     * @return echo/false
     */ 
    public static function container($grid_x=true, $margin_x=true, $margin_y = false, $section="", $class="", $id="", $data_atributes_to_grid_x=[]){
      echo '<div class="container'.($class ? ' '.$class : '').'"'.($id ? ' id="'.$id. '"': '').'>';
      if($section) echo '<div class="'.$section.'">';
      if ($grid_x)
        echo '<div class="grid grid-cols-12'.($margin_x ? ' gap-x-theme' : '').($margin_y ? ' gap-y-theme' : '').'" '.
          ($data_atributes_to_grid_x ? self::add_attributes($data_atributes_to_grid_x): '').
        '>';
    }

    // ukončení sekce
    public static function end_container($grid_x=true, $section=false){
      if ($grid_x) echo self::$end_div;
      if ($section) echo self::$end_div;
      echo self::$end_div;
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
    public static function cell($large='', $medium='', $small='', $cell=true, $class="", $id="", $data_atributes=[] ){
      if (!$cell) return;

      $large = ( $large ? $large : $large=self::$column);
      $medium = ( $medium ? $medium : $medium=self::$column);
      $small = ( $small ? $small : $small=self::$column);
      echo '<div class="'.
      'lg:col-span-'.$large . ' '.
      'md:col-span-'.$medium . ' '.
      'col-span-'.$small . ' '.
      ($class ? ' '. $class : '').
      '"'.
      ($id ? ' id="'.$id. '"': '').
      ($data_atributes ? self::add_attributes($data_atributes): '').
      '>';
    }

    // ukončení sekce
    public static function end_cell(){
      echo self::$end_div;
    }

    /**
     * ohraničení grid containerem
     * @param $data_attributes - array
     * 
     * @author Digihood
     * @return string
     */ 
    private static function add_attributes($data_attributes=[]) {
      if (is_array($data_attributes) && !empty($data_attributes)) {
        $attributes = " ";
        foreach ($data_attributes as $attribute => $value) {
          if($attribute) {
            $attributes .= ' ' . $attribute;
            if ($value) {
              $attributes .='=' . $value; 
            }
          };
        }
        return $attributes;
      }
      return null;

    }
    /**
     * Přidá grid-x div
     * @param $class
     * 
     * @author Digihood
     * @return string
     */ 
    public static function grid_x($margin_x=true, $margin_y=false, $class="", $id=""){
      $html_class = 
      'class="grid grid-cols-12'. 
        ($margin_x ? ' gap-x-theme' : '').
        ($margin_y ? ' gap-y-theme' : '').
        ($class ? ' '.$class : '').
      '"';
      return '<div '.$html_class. ($id ? ' id="'.$id. '"' : '') . '>';

    }

  }
}