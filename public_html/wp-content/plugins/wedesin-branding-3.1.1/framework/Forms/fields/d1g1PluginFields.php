<?php 
namespace pluginbrandslug\framework\Forms\fields;
/**
 * field pro feedback
 *
 * 
 * @author Digihood
 */ 

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if( ! class_exists( 'd1g1PluginFields' ) )
{
	class d1g1PluginFields
	{


        public static function field_feedback(){
            $userdata = get_userdata(get_current_user_id());
            $username = $userdata->user_login;
            $useremail = $userdata->user_email;
            $dateregister = $userdata->user_registered;
            $userroles = $userdata->roles[0];
            $userphone = get_user_meta(get_current_user_id(),'billing_phone',true);
            $fields=[
                [
                    'headline' => 'Zpětná vazba ',
                    'description' => 'Váš názor je pro nás důležitý. Chtěli bychom vás poprosit o zpětnou reakci.',
                    'save_option' => 'send_feedback',
                    
                    [
                        'type' => 'text',
                        'name' => 'feedback_subject',
                        'saveAs' => 'meta',
                        'required' => false,
                        'label' => 'Předmět',
                    ],
                    [
                        'type' => 'editor',
                        'name' => 'feedback_content',
                        'saveAs' => 'meta',
                        'required' => true,
                        'label' => 'Obsah',
                    ],
                    [
                        'type' => 'hidden',
                        'name' => 'username',
                        'saveAs' => 'meta',
                        'required' => false,
                        'value' => $username
                    ],
                    [
                        'type' => 'hidden',
                        'name' => 'usermail',
                        'saveAs' => 'meta',
                        'required' => false,
                        'value' => $useremail
                    ],
                    [
                        'type' => 'hidden',
                        'name' => 'dateregister',
                        'saveAs' => 'meta',
                        'required' => false,
                        'value' => $dateregister
                    ],
                    [
                        'type' => 'hidden',
                        'name' => 'userroles',
                        'saveAs' => 'meta',
                        'required' => false,
                        'value' => $userroles
                    ],
                    [
                        'type' => 'hidden',
                        'name' => 'userphone',
                        'saveAs' => 'meta',
                        'required' => false,
                        'value' => $userphone
                    ],
                    [
                        'type' => 'hidden',
                        'name' => 'licence',
                        'saveAs' => 'meta',
                        'required' => false,
                        'value' => 'In construct'
                    ],
                ]
            ];
            return $fields;
        }

        public static function field_licenses(){
            $fields=[
                [
                    'headline' => 'Licenční klíč',
                    'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                    [
                        'type' => 'text',
                        'name' => 'license_key',
                        'saveAs' => 'meta',
                        'required' => false,
                    ],
                    
                ],
            ];
            return $fields;
        }

    }
   
}