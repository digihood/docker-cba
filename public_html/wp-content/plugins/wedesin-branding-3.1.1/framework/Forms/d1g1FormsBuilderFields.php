<?php 
namespace pluginbrandslug\framework\Forms;
use pluginbrandslug\admin\fields\d1g1ThisPluginField;


if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if( ! class_exists( 'd1g1FormsBuilderFields' ) )
{
	class d1g1FormsBuilderFields
	{

		public static function get_fields_form($formID){
			$fields = d1g1ThisPluginField::get_fields_form($formID);
			return $fields;

		}

		public static function get_fields_cpt_form($post_type){
			$fields= [];
			$fields = d1g1ThisPluginField::get_fields_cpt_form($post_type);
			return $fields;
		}
		
		
	}

}