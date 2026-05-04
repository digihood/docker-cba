<?php

/**
 * 
 * Tlačítka (odkazy)
 *
 * 
 * @author Digihood
 */ 

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if( ! class_exists( 'd1g1BuilderButton' ) )
{
	class d1g1BuilderButton
	{

		public function __construct()
		{
    }

    /**
    * 	Description 
    *
    * 	@param $text = title links
    *	  @param $link = href
    *   @param $class = array('class1','class2') / ''class1'
    *	  @param $id = 'id'
    *   @param $attribute = array('target'=>'_blank', ... ) 
    * 
    * 	@author Digihood
    * 	@return echo
    */
    public static function get_link( $title, $link, $class='', $id="", $attribute=[]) { 
      $html = "";
      $button_class = self::get_class($class);
      $attr = self::get_attribute($attribute);

      //build link
      if ($title && $link) {
        $html.='<a href="'.$link.'" class="'.$button_class.'" '.( $id ? 'id="'.$id.'" ' : '').$attr.'>'.$title.'</a>';
      }
      return $html;
    }

    // vrátí classy zadané v array i stringu
    private static function get_class($classes) {
      if (is_array($classes)) {
        $return = '';
        foreach ($classes as $class) {
          if ($class) $return .=$class . ' ';
        }
        return $return;
      } else return $classes;
    }

    // vrátí do html všechny attributy jako string 
    private static function get_attribute($attributes) {
      if (is_array($attributes)) {
        $return = '';
        foreach ($attributes as $key => $value) {
          if ($key) {
            $return .= $key . '="'.$value.'" ';
          }
        }
        return $return;
      } else return $attributes;
    }
  }
}