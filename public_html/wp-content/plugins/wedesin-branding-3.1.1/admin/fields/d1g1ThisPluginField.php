<?php 
namespace pluginbrandslug\admin\fields;
use pluginbrandslug\framework\Forms\fields\d1g1PluginFields;
if ( ! defined( 'ABSPATH' ) ) {
    exit;
  }
  
if( ! class_exists( 'd1g1ThisPluginField' ) )
{
    class d1g1ThisPluginField
    {
        public static function get_fields_form($formID){
            $fields= [];
			if ($formID == 'feedback'){
				$field = d1g1PluginFields::field_feedback();
				$fields = $field;
			
			}elseif($formID == 'license'){
				$field = d1g1PluginFields::field_licenses();
				$fields = $field;
			}
			elseif  ($formID == 'brand_emails_settings') {
				$fields=[
					[
						'headline' => 'Nastavení emailů',
						'description' => 'Pro správné používání emailů je potřeba přizpůsobit jejich vzhled a nastavení.',
					],
					[           
                        'headline' => 'Nastavení vzhledu emailu',     
						'columns' => [   
							[
								[
									'type' => 'color',
									'name' => 'footer_bg_color',
									'label' => 'Barva pozadí zápatí',
									'help_text' => 'Vyberte si barvu, kterou bude mít zápatí emailů',
									'value' => '#0a0a0a', // rgba(30,30,30,0.64)
									'saveAs' => 'meta',
								]
							],
							[
								[
									'type' => 'color',
									'name' => 'footer_color',
									'label' => 'Barva textu zápatí',
									'help_text' => 'Vyberte si barvu, kterou bude mít text  v zápatí emailů',
									'value' => '#ff00dd', // rgba(30,30,30,0.64)
									'saveAs' => 'meta',
								]
							],
							[
								[
									'type' => 'color',
									'name' => 'Links_color',
									'label' => 'Barva textu odkazů',
									'help_text' => 'Vyberte si barvu, kterou bude mít odkaz  v zápatí emailů',
									'value' => '#2199e8', 
									'saveAs' => 'meta',
								]
							]
						],
                        [
                            'type' => 'image',
                            'name' => 'header_logo',
                            'label' => 'Logo do hlavičky emailu',
                            'description' => 'Chcete-li vložit do emailu své logo, zde ho nahrajte',
                            'help_text' => 'Chcete-li vložit do emailu své logo, zde ho nahrajte. Nahrávejte ve formátu jpg, nebo png o šířce alespoň 200px',
                            'value' => '',
                            'floating_label' => true,
                            'saveAs' => 'meta'
                        ],     
						[
                            'type' => 'text',
                            'name' => 'reply_to_email',
                            'saveAs' => 'meta',
                            'label' => 'Email pro odpověď',
                            'help_text' => 'Vyplňte email, na který má přijít odpověď',
                        ]
                    ],
					[           
                        'headline' => 'Nastavení emailu o Aktualizacích',     
						[
                            'type' => 'editor',
                            'name' => 'mail_text_license_d1g1',
                            'label' => 'Text emailu',
                            'description' => 'Text emailu',
                            'help_text' => 'Pomocný text pro editor',
                            'floating_label' => true,
                            'saveAs' => 'meta',
                        ],
						[
                            'type' => 'text',
                            'name' => 'license_email_subject',
                            'saveAs' => 'meta',
                            'label' => 'Předmět emailu',
                            'help_text' => 'Vyplňte předmět, který má být uvedem v emailu s odeslanou licencí',
                        ],
						[
                            'type' => 'text',
                            'name' => 'send_to_email',
                            'saveAs' => 'meta',
                            'label' => 'Email odeslat na',
                            'help_text' => 'Vyplňte email, na který má být odesláno hlášení o aktualizaci',
                        ]
					]
				];
			} else if ($formID == 'update_report') {
				add_action('d1g1_submit_button_form-'.D1G1_BRANDING,function () { echo submit_button('Odeslat hlášení');} );
				$plugins = [];
				$plugins_list = get_plugins();
				foreach ($plugins_list as $plugin => $value) {
					if ($plugin && isset($value['Name']))
						$plugins[$plugin] = $value['Name'];
				}
				$fields=[
					[
						'headline' => 'Jádro WP',
						'description' => 'Co z WP bylo aktualizováno?',
						'save_option' => 'send_mail',
						'button_text' => 'send_report',
						[
							'type' => 'checkbox',
							'name' => 'up_wp',
							'label' => 'Aktualizace Wordpressu',
							'saveAs' => 'meta',
						],
						[
							'type' => 'checkbox',
							'name' => 'up_theme',
							'label' => 'Aktualizace Šablony',
							'saveAs' => 'meta',
						],
						[
							'type' => 'checkbox',
							'name' => 'up_settings',
							'label' => 'Aktualizace Nastavení důležitých fcí (zabezpečení, zálohy a pod.)',
							'saveAs' => 'meta',
						]
					],
					[
						'headline' => 'Pluginy',
						'description' => 'Jaké pluginy byly aktualizovány',
						[
							'type' => 'checkbox',
							'name' => 'up_plugins',
							'label' => 'Aktualizace Nastavení důležitých fcí (zabezpečení, zálohy a pod.)',
							'saveAs' => 'meta',
							'options' => $plugins
						],
					],
					[
						'headline' => 'Připojit ke zprávě poznámku',
						[
                            'type' => 'editor',
                            'name' => 'up_content',
                            'label' => 'Poznámka',
                            'description' => 'Text emailu navíc',
                            'floating_label' => true,
                            'saveAs' => 'meta',
                        ],
					]
					];

			}elseif($formID == 'send_digi_mail'){
				add_action('d1g1_submit_button_form-'.D1G1_BRANDING,function () { echo submit_button('Odeslat email');} );
				$fields=[
					
					[
						'headline' => 'Odesílaní emailu',
						'save_option' => 'send_mail_anthor',
						[
                            'type' => 'text',
                            'name' => 'send_to_email',
                            'saveAs' => 'meta',
                            'label' => 'Email odeslat na',
                            'help_text' => 'Vyplňte email, na který má být odesláno hlášení o aktualizaci',
							'required' => true,
                        ],
						[
                            'type' => 'text',
                            'name' => 'license_email_subject',
                            'saveAs' => 'meta',
                            'label' => 'Předmět emailu',
                            'help_text' => 'Vyplňte předmět, který má být uvedem v emailu s odeslanou licencí',
							'required' => true,
                        ],
						[
                            'type' => 'editor',
                            'name' => 'up_content',
                            'label' => 'Poznámka',
                            'description' => 'Text emailu navíc',
                            'floating_label' => true,
                            'saveAs' => 'meta',
                        ],
					]
					];
			}elseif($formID == 'plugins_tools'){
				$fields=[
					
					[
						'headline' => 'Režím udržby',
						[
							'type' => 'switch',
							'name' => 'digi_maintenance_mode',
							'label' => 'Zapnout udržbu',
							'saveAs' => 'meta',
						],
						[
                            'type' => 'text',
                            'name' => 'digi_maintenance_mode_title',
                            'saveAs' => 'meta',
                            'label' => 'Title',
                            'help_text' => '',
							'required' => false,
                        ],
						[
                            'type' => 'text',
                            'name' => 'digi_maintenance_mode_text',
                            'saveAs' => 'meta',
                            'label' => 'Defautní zprava stránky',
                            'help_text' => '',
							'required' => false,
                        ],
						[
                            'type' => 'time',
                            'name' => 'digi_maintenance_mode_time',
                            'saveAs' => 'meta',
                            'label' => 'udržba do ',
                            'help_text' => '',
							'required' => false,
                        ],
						
					]
				];
			}
			return $fields;
        
        }
        public static function get_fields_cpt_form($post_type){
            $fields= [];
            if ($post_type == 'feedback') {
                $fields=[
                   
                ];
            }
            return $fields;
        }
    }
}

?>