<?php

namespace sitemap\admin\fields;

use sitemap\framework\Functions\feedback\d1g1Feedback;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('d1g1PluginField')) {
	class d1g1PluginField
	{
		public static function get_fields_form($formID)
		{

			$fields = [];
			if ($formID == 'mainSettings') {
				$fields = [
					'headline' => __('Nastavení', 'digiSM'),
					'description' => '',
					'enctype' => '',
					'sections' => self::set_section()
				];
			} elseif ($formID == 'feedback') {
				$field = d1g1Feedback::field_feedback();
				$fields = $field;
			}

			return $fields;
		}
		public static function get_fields_cpt_form($post_type)
		{
			$fields = [];
			if ($post_type == 'feedback') {
				$fields = [
					$fields = [
						'headline' => 'Nadpis formuláře',
						'description' => 'Popisek formuláře',
						'enctype' => '',
						'sections' =>
						[
							'section111' => [
								'headline' => 'test-444',
								'description' => 'test-444',
								'fields' => [
									[
										'type' => 'checkbox',
										'name' => 'checkbox_test',
										'label' => 'Jméno',
										'saveAs' => 'options',
										'options' => [
											'checkboxs' => [
												'1' => '1',
												'2' => '2',
												'3' => '3',
											]
										]
									],
									[
										'type' => 'url',
										'name' => 'adresa-url',
										'label' => 'adresa url',
										'saveAs' => 'options',
										'rules' => 'required|url',
										'options' => [
											'width' => 'half',
										]
									],
									[
										'type' => 'email',
										'name' => 'email1',
										'label' => 'E-mail',
										'floating_label' => true,
										'saveAs' => 'options',
										//'rules' => 'required|email',
										'options' => [
											'width' => 'half',
										]
									],
									[
										'type' => 'tel',
										'name' => 'telephone',
										'label' => 'Telefon',
										'floating_label' => true,
										'saveAs' => 'options',
										'options' => [
											'width' => 'half',
										]
									],
									[
										'type' => 'number',
										'name' => 'cislo',
										'label' => 'Nějaké číslo',
										'floating_label' => true,
										'saveAs' => 'options',
										//'rules' => 'required|number|max:20|min:10',
										'options' => [
											'width' => 'full',
										]
									],
									[
										'type' => 'password',
										'name' => 'heslo',
										'label' => 'Vaše heslo',
										'floating_label' => true,
										'saveAs' => 'options',
										'options' => [
											'width' => 'half',
										]
									],
								]
							],
							'section9' => [
								'headline' => 'test-7',
								'description' => 'test-77',
								'fields' => [
									[
										'type' => 'color',
										'name' => 'barva',
										'label' => 'Primární barva',
										'description' => 'Eu nisi sed viverra id aliquam enim, odio nunc.',
										'help_text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
										'value' => '#ff00dd', // rgba(30,30,30,0.64)
										'saveAs' => 'meta',
										'required' => true,
									],
								],
							],
						],
					],
				];
			}
			return $fields;
		}
		public static function description()
		{
			/* 			ob_start(); ?>
			
<?php
			$html_description = ob_get_contents();
			ob_end_clean();
			return $html_description; */
		}

		public static function set_section()
		{
			$sections = [];

			// 1 static header field pro změnu post type jmen
			$sections['section_postTypeSettings'] = 	[
				'headline' => __('Přepsat defaultní názvy vlastních typů příspěvků pro aktivní jazyky', 'digiSM'),
				'description' => '',
				'fields' => []
			];


			// 2 foreach post type ->field - pole FCE
			$args = array(
				'public' => true,
				'publicly_queryable' => true
			);
			$post_types = get_post_types($args);
			unset($post_types["attachment"], $post_types["post"]);

			foreach ($post_types as $post_type) {
				$sections['section_' . $post_type] = 	[
					'headline' => $post_type,
					'description' => '',
					'fields' => self::get_fields($post_type)
				];
			}


			// 3 static header field pro změnu tax jmen 
			$sections['section_taxSettings'] = 	[
				'headline' => __('Přepsat defaultní názvy taxonomií pro aktivní jazyky', 'digiSM'),
				'description' => '',
				'fields' => []
			];


			// 4 foreach tax -> field - pole FCE 
			$args = array('public' => true);
			$taxonomies = get_taxonomies($args, 'objects');

			foreach ($taxonomies as $taxonomy) {
				$tax_name = $taxonomy->label;
				$sections['section_' . $tax_name] =  	[
					'headline' => $tax_name,
					'description' => '',
					'fields' => self::get_fields($taxonomy->name)
				];
			}


			return $sections;
		}

		public static function get_fields($field_lable)
		{
			$fields = [];
			$languages = get_available_languages();
			foreach ($languages as $language) {
				$fields[] = [
					'type' => 'text',
					'name' => str_replace('_', '', $language) . $field_lable,
					'label' => '"' . $field_lable . '"' . __(' v jazyce: ', 'digiSM') . $language,
					'saveAs' => 'options',
					'options' => [
						'width' => 'half'
					]
				];
			}

			return $fields;
		}
	}
}
