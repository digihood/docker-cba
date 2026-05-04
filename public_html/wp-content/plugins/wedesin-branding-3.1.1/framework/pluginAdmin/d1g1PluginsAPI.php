<?php 
namespace pluginbrandslug\framework\PluginAdmin;
/**
 * Popis třídy
 *
 * 
 * @author Digihood
 */ 

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if( ! class_exists( 'd1g1PluginsAPI' ) )
{
	class d1g1PluginsAPI
	{

        private $api_url;

		public function __construct()
		{
           // URl pro získaní dat
			$this->api_url = 'https://plugins.digihood.cz/wp-json/wp/v2/'; 
			add_action('init', [$this, 'testfce']);
        }
		public function testfce() {
			if(isset($_GET['testfce']) && $_GET['testfce'] == 1) {

				$plugins = array(
					array('name' => 'zero-spam', 'path' => 'http://localhost:8080/down/d1g1-zero-spam.zip', 'install' => 'd1g1-zero-spam/setup.php')	
				);

				$test = mm_get_plugins($plugins);
				preprint($test);
				die();
			}
		}

        /**
         * vytvoři url pro ziskani api 
         *
         * @param $type  - pluginlist
         * @author digihood
         * @return strnatcmp
         */

        public function get_url($type, $img_id = '') {
	
				if($type == 'pluginslist') { 
				    return $this->api_url . $type . '?per_page=100';
				}
				if ($type == 'media' && $img_id) {
					return $this->api_url . 'media/' . $img_id;
				}
		}
         /**
         * získaní dat z api
         *
         * @param $type  - pluginlist typ přispevku 
         * @author digihood
         * @return array
         */
        public function rest_api_post($type){
			try {
			// Přimé napojení Rest API na ziskanou URL 
			    $response = wp_remote_get( $this->get_url($type) );
            } catch (Error $e) {
                echo "Při stažení posledního příspěvku došlo k chybě " . $e->getMessage() . '.';
            }
            	// Ukončí pokud se vyskytne error vrátí prazdno
			if ( !$response || is_wp_error( $response ) || !is_array( $response ) ) {
				
				return '';
			}

			// Získá Data
			$posts = json_decode( wp_remote_retrieve_body( $response ) );
            
            //$array = [];
	        // Pokud je $posts prazdný vrátí prazdný pole
			if ( !empty( $posts ) ) {	
			// Data Pro kazdý Posts 
				foreach ( $posts as $post ) {
					$array[] = [
						'title'      => ( isset($post->title->rendered) && !empty($post->title->rendered) ? $post->title->rendered : '') , 
						'content'    => ( isset($post->content->rendered) && !empty($post->content->rendered) ? $post->content->rendered : '') ,
						'thumbnail'  => $this->rest_api_img($post->featured_media),
						'small_content' => ( isset($post->excerpt->rendered) && !empty($post->excerpt->rendered) ? $post->excerpt->rendered : '') ,
						'TextDomain'    => ( isset($post->acf->plugin_slug) && !empty($post->acf->plugin_slug) ? $post->acf->plugin_slug : '') ,
						'link'    => ( isset($post->link) && !empty($post->link) ? $post->link : '') ,
					];
					
				}
			
				return $array;
			} 
				
        }
		/**
		 *  Rest API Obrazků
		 *
		 * @param img_id
		 * 
		 * @author digihood
		 * @return string
		 */

		private function rest_api_img( $img_id ){
			// defautní type pro media
			$type = 'media';
			try {
			// Přimé napojení Rest API na ziskanou URL 
				$img_response = wp_remote_get( $this->get_url($type,$img_id) );
			} catch (Error $e) {
				echo "Při stažení posledního příspěvku došlo k chybě " . $e->getMessage() . '.';
			}
	 		// Ukončí pokud se vyskytne error a vrátí prazdno
			if ( !$img_response || is_wp_error( $img_response ) || !is_array( $img_response ) ) {
				return '';
			}
			// Pokud $img_response['body'] je vyplnený a není prazdný poté se decoduje a vratí URL obrazku 
				if (isset($img_response['body']) && !empty($img_response['body']) ) { 
					$api_img = json_decode( $img_response['body'] ); 
					if (isset($api_img->media_details->sizes->thumbnail->source_url) && $api_img->media_details->sizes->thumbnail->source_url){ 		
						return $api_img->media_details->sizes->thumbnail->source_url;
						
					} else {
	
						return '';
					}

				}
				
			return '';
				
		}
		/**
		 * get digihood plugins
		 *
		 * @param $plugin_status true/false/empty
		 * 
		 * @author digihood
		 * @return echo string
		 */ 


		function d1g1_get_plugins($plugin_status = '') {
			$plugins = get_plugins();
            $data = [];
			foreach($plugins as $plugin){
				if($plugin['AuthorName'] == 'Digihood'){
					$active = is_plugin_active( ''.$plugin['TextDomain'].'/setup.php' );
					if (empty($plugin_status) || ( $plugin_status == $active) )
					$data[] = [
						'name' => $plugin['Name'],
						'version' => $plugin['Version'],
						'description' => $plugin['Description'],
						'author' => $plugin['Author'],
						'TextDomain' => $plugin['TextDomain'],
						'Author_URI' => $plugin['AuthorURI'],
						'activated' => $active
					];
				} 
			}
			return $data;
	
		}
		 /**
         * připravena funkce na porovnani dat z api 
         *
         * @param 
         * @author digihood
         * @return 
         */
		public function d1g1_comparison_plugins(){
			$data = [];
			$plugins = [];
			$data = $this->d1g1_get_plugins();
			$api = $this->rest_api_post('pluginslist');
			
			foreach ($api as $plugin) {
				if (isset($plugin['TextDomain']) && 
					$plugin['TextDomain'] && 
					array_search($plugin['TextDomain'], array_column($data, 'TextDomain')) === false
				){
					$plugins[] = $plugin; 
				}
			}
			
			return $plugins;
			
		}
    }
     new d1g1PluginsAPI;
}