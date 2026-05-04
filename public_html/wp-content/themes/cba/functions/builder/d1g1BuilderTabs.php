<?php 
/**
 * Vkládání accordeonu a tabů přes builder
 *
 * 
 * @author Digihood
 */ 

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if( ! class_exists( 'd1g1BuilderTabs' ) )
{
	class d1g1BuilderTabs
	{
		public function __construct()
		{
        }
        /**
         * sestaví horizontální tabs
         *
         * @param $tabs_data - array
         * 
         * @author Digihood
         * @return echo/false
         */ 
        public static function horizontal_tabs($tabs_data=[]){
            if (!empty(self::check_valid_data_array($tabs_data))) {
                echo self::check_valid_data_array($tabs_data);
            } else {
                echo self::get_tab_nav($tabs_data, false);
                echo self::get_tab_content($tabs_data, false);
            }

        }

        /**
         * sestaví vertical tabs
         *
         * @param $tabs_data - array
         * 
         * @author Digihood
         * @return echo/false
         */ 
        public static function vertical_tabs($tabs_data=[]){
            if (!empty(self::check_valid_data_array($tabs_data))) {
                echo self::check_valid_data_array($tabs_data);
            } else {
                echo '<div class="grid grid-cols-12">';
                echo self::get_tab_nav($tabs_data, true);
                echo self::get_tab_content($tabs_data, true);
                echo '</div>';
            }
        }
        // sestaví navigaci tabs
        private static function get_tab_nav($tabs_data, $vertical=false){
            $attr = (self::is_data($tabs_data,'data_attribute_nav') ?  ' '.$tabs_data['data_attribute_nav'] . ' ': '');
            $icons = (self::is_data($tabs_data,'icons') ?  ' '.$tabs_data['icons'] . ' ': '');
            $html = self::get_nav_cell($tabs_data, $vertical);
            $html .= '<ul class="'.($vertical ? 'vertical ' : '') .'tabs"'.$attr.'data-tabs '.(self::is_data($tabs_data, 'id') ? 'id="'.$tabs_data['id'] : "") .'">';
            if (self::is_data($tabs_data,'content') && is_array($tabs_data['content'])){
                foreach ($tabs_data['content'] as $data_tab) {
                   $html .= self::single_tab_li($data_tab, $icons);
                }
            }
        
            $html .= '</div></ul>'; // div ukončuje get_nav_cell
            return $html;
        }
        // sestaví jednotlivý řádek navigace
        private static function single_tab_li($data_tab, $icons){
            $title=(self::is_data($data_tab,'title') ? $data_tab['title']: '');
            $hash=(self::is_data($data_tab,'hash') ? $data_tab['hash']: '');
            $active=(self::is_data($data_tab,'active') ? $data_tab['active'] : '');
            return '<li class="tabs-title '.($active ? 'is-active' : '').'"><a href="#'.$hash.'">'.$title. ( $icons ? D1g1B::get_icon('tab-slips') . D1g1B::get_icon('tab-arrow', 'tab-arrow') : "") .'</a></li>';
        }
        // přidá obalovač do table
        private static function get_nav_cell($tabs_data, $vertical){
            $class =($vertical ? 'col-span-12 md:col-span-4' : '');
            if(self::is_data($tabs_data, 'nav_cell')) {
                $class = $tabs_data['nav_cell'];
            }
            return '<div class="'.$class.'">';
        }

        private static function get_content_cell($tabs_data, $vertical){
            $class = ($vertical ? 'col-span-12 md:col-span-8' : '');
            if(self::is_data($tabs_data, 'content_cell')) {
                $class = $tabs_data['content_cell'];
            }
            return '<div class="'.$class.'">';
        }
        
        private static function is_data($tabs_data, $data_name){
            if ( isset($tabs_data[$data_name]) && !empty($tabs_data[$data_name]) ) return true;
            
            return false;
        }

        private static function get_tab_content($tabs_data, $vertical){
            $html = self::get_content_cell($tabs_data, $vertical);
            $attr = (self::is_data($tabs_data,'data_attribute_content') ?  ' '.$tabs_data['data_attribute_content'] . ' ': '');
            $html .= '<div class="tabs-content'. ($vertical ? ' vertical' : '') .'"'.$attr.'data-tabs-content="'.(self::is_data($tabs_data, 'id') ? ''.$tabs_data['id'] : '').'">';
            if (self::is_data($tabs_data,'content') && is_array($tabs_data['content'])){
                foreach ($tabs_data['content'] as $data_tab) {
                   $html .= self::single_content_div($data_tab);
                }
            }
            $html .= '</div></div>'; // ukončení divu z get_content_cell a tabs-content
            return $html;

        }
        private static function single_content_div($data_tab){
            $active = (self::is_data($data_tab, 'active')? 'is-active' : '');
            $id = (self::is_data($data_tab, 'hash')? $data_tab['hash'] : '');
            $content = (self::is_data($data_tab, 'content')? $data_tab['content'] : '');
            $html = '<div class="tabs-panel '.$active.'" id="'.$id.'">'. $content . '</div>';
            return $html;
        }
        private static function check_valid_data_array($tabs_data) {
            $errors = [];
            if (!self::is_data($tabs_data,'id')) $errors[] = 'Chybí povinná položka v hlavním poli: "id"';
            if (!self::is_data($tabs_data,'content') && !is_array($tabs_data['content'])) $errors[] = 'Chybí povinná položka v hlavním poli: "content" nebo není ve formátu pole';
            if (self::is_data($tabs_data,'content') && is_array($tabs_data['content'])){
                $active = 0;
                foreach ($tabs_data['content'] as $data_tab) {
                    if(self::is_data($data_tab,'active')) $active++;
                    if(!self::is_data($data_tab,'title')) $errors[]= 'V jenotlivých tabech chybí povinný parametr "title"';
                    if(!self::is_data($data_tab,'hash')) $errors[]= 'V jenotlivých tabech chybí povinný parametr "hash"';
                    if(!self::is_data($data_tab,'content')) $errors[]= 'V jenotlivých tabech chybí povinný parametr "content"';
                }
                if ($active == 0 ) $errors[]= 'Alespoň v jednom z jednotlivých tabů musí být: "active" => true';
                if ($active > 1 ) $errors[]= 'V jednotlivých tabech musí být: \' "active" => true\' POUZE JEDNOU';
            }
            if (!empty($errors)) {
                return self::display_errors($errors);
            } else {
                return [];
            }
        }
        //zobrazí hlášky o chybách 
        private static function display_errors($errors){
            $errors_html = "";
            foreach ($errors as $error) {
                $errors_html .= $error . '<br>';
            }
            $errors_html .= '<b>NOTE: chcete-li zobrazit ukázkové pole hodnot, použijte příkaz d1g1BuilderTabs::demo();</b>';
            return $errors_html;

        }
        // zobrazení dema pro tabs
        public static function demo(){
            $tabs_data = [
                'id' =>'vertival_tabs_milan',
                'nav_cell' => 'col-span-12 md:col-span-4',
                'content_cell' =>'col-span-12 md:col-span-6 md:col-start-1',
                'data_attribute_nav' =>'ahoj="takytest"',
                'data_attribute_content' =>'ahoj="test"',
                'icons' => true,
                'content'=> [
                    [
                        'title' => 'Tab 1',
                        'hash'=> 'tab1',
                        'active'=> true,
                        'content'=> 'nějaký content'
                    ],
                    [
                        'title' => 'Tab 2',
                        'hash'=> 'tab2',
                        'active'=> false,
                        'content'=> 'nějaký content druhé záložky'
                    ],                    [
                        'title' => 'Tab 3',
                        'hash'=> 'tab3',
                        'active'=> false,
                        'content'=> 'nějaký content třetí záložky'
                    ]
                ]
            ];
        }
    }
}