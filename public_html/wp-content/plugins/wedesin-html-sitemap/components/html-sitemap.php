<?php

namespace sitemap\components;

use sitemap\framework\Globals;


defined('ABSPATH') or die("No direct access please.");

class digihoodHtmlSitemap
{

	function __construct()
	{

		add_action('init', [$this, 'plugin_init']);

		//add shortcodes
		//create shortcodes for the sitemap
		add_shortcode('html-sitemap', [$this, 'create_shortcode_WHS_digihood']);
		add_shortcode('post-type-sitemap', [$this, 'create_post_type_shortcode']);
		add_shortcode('attachment-sitemap', [$this, 'create_attachment_sitemap']);
		add_shortcode('tax-post-sitemap', [$this, 'create_tax_posts_shortcode']);
		add_shortcode('tax-term-sitemap', [$this, 'create_tax_terms_shortcode']);
	}

	//initiliaze the plugin
	function plugin_init()
	{
	}


	/**
	 * Create pages list
	 * @since 1.0
	 * @param none
	 * @return string
	 */

	function return_pages_WHS_digihood()
	{

		//get the exclude posts setting
		$exclude = "";
		$excluded_posts_WHS_digihood = str_replace(' ', '', get_option('_html_sitemap_setting_WHS_digihood'));
		$exclude = $excluded_posts_WHS_digihood;

		$args = array(
			'post_status'  => 'publish',
			'echo'         => 0,
			'exclude'      => "",
			'title_li'     => '',
		);

		$result = wp_list_pages($args);

		return $result;
	}

	/**
	 * Creates list of post types
	 * @since 1.02
	 * @param post type => string
	 * @return array
	 */

	function options_to_array_WHS_digihood($string)
	{

		//get the exclude posts setting
		$array_data = get_option('_html_sitemap_setting_WHS_digihood');
		$array_data = str_replace(' ', '', $array_data);

		if (!empty($array_data)) {
			$array_data =  explode(",", $array_data[$string]);
		} else {
			$array_data = array();
		}

		return $array_data;
	}

	/**
	 * Creates list of post types
	 * @since 1.0
	 * @param post type => string
	 * @return string
	 */

	function return_post_type_posts_WHS_digihood($post_type)
	{
		global $post;

		//get options array
		$excluded_posts_WHS_digihood = $this->options_to_array_WHS_digihood('exclude');
		$return = '';
		$content = '';

		$args = array(
			'post_type' => 	isset($post_type->name) ? $post_type->name : $post_type,
			'posts_per_page' => -1,
			'post_status' => 'publish',
			'orderby' => 'date',
			'order' => 'ASC'
		);
		$the_query = new \WP_Query($args);

		$posts = $the_query->posts;

		//if array is set
		if (!is_wp_error($posts) && !empty($posts)) {

			foreach ($posts as $post) {

				//get the post values
				$the_title = $post->post_title;
				$the_url = get_the_permalink($post->ID);

				//check if the post type is not attachement 
				if ($post_type != 'attachment') {

					if (!in_array($post->ID, $excluded_posts_WHS_digihood)) {

						$return .= '<li><a href="' . $the_url . '">' . $the_title . '</a></li>';
					}
				} else {

					$return .= '<li><a href="' . $the_url . '">' . $the_title . '</a></li>';
				}
			}
		}
		wp_reset_query();
		return $return;
	}

	/**
	 * Feed all post types
	 * @since 1.0
	 * @param none
	 * @return array
	 */
	function feed_post_types_WHS_digihood()
	{
		$args = array(
			'public' => true,
			'publicly_queryable' => true
		);

		$post_types = get_post_types($args);

		//remove attachments
		unset($post_types["attachment"]);

		//unset custom post type
		$exclude_post_type = $this->options_to_array_WHS_digihood('exclude_type');

		//unset from feed
		if (isset($exclude_post_type) && !is_wp_error($exclude_post_type)) {

			foreach ($exclude_post_type as $key => $type) {

				unset($post_types[$type]);
			}
		}

		return $post_types;
	}


	/**
	 * Create final content
	 * @since 1.0
	 * @param none
	 * @return string
	 */

	function create_shortcode_WHS_digihood($atts)
	{

		$param = shortcode_atts(array(
			'exclude' => '',
			'exclude_type' => '',
		), $atts);

		array_map("htmlspecialchars", $param);
		//set excluded posts
		update_option('_html_sitemap_setting_WHS_digihood', $param);

		$return = "";
		$content = "";
		$before = '<div class="whs-wrap" style="clear: both;">';
		$after = "</div>";
		//input pages
		$post_type_page = get_post_type_object("page");

		$content .= '<h2>' . $post_type_page->labels->name . '</h2><ul>' . $this->return_pages_WHS_digihood() . '</ul>';

		//get all post types
		$post_types = $this->feed_post_types_WHS_digihood();

		foreach ($post_types as $type) {

			$ct = wp_count_posts($type);

			if ($ct->publish > 0) {

				$post_type_object = get_post_type_object($type);

				if ($type == 'post') {

					if (!empty($post_type_object->labels->name)) {
						$name =  $post_type_object->labels->name;
					} else {
						$name = ucfirst($type);
					}

					//feed in custom post types
					$content .= '<h2>' . $name . '</h2><ul>' . $this->return_post_type_posts_WHS_digihood($type) . '</ul>';
				} else {

					$options_field = str_replace('_', '', get_locale() . $type);

					$name = Globals::d1g1_get_option('mainSettings', $options_field) ? Globals::d1g1_get_option('mainSettings', $options_field) : $post_type_object->labels->name;

					$content .= '<h2>' . $name . '</h2><ul>' . $this->return_post_type_posts_WHS_digihood($type) . '</ul>';
				}
			}
		}

		$result = $before . $content . $after;

		return $result;
	}


	/**
	 * Create final content for post_type map
	 * @since 3.0
	 * @param none
	 * @return string
	 */
	function create_post_type_shortcode($atts)
	{

		$param = shortcode_atts(array(
			'post-type' => '',
		), $atts);

		array_map("htmlspecialchars", $param);

		$content = "";
		$before = '<div class="whs-wrap" style="clear: both;">';
		$after = "</div>";

		//input pages
		$pt_object = get_post_type_object($param['post-type']);

		// check if post type renamed in plugin options
		if (is_a($pt_object, 'WP_Post_Type')) {

			$options_field =  str_replace('_', '', get_locale()) . $param['post-type'];
			$options_name = Globals::d1g1_get_option('mainSettings', $options_field);

			if (!empty($options_name)) {
				$name = $options_name;
			} else {
				$name = $pt_object->labels->name;
			}
			$content .= '<h2>' .  $name . '</h2>';

			//feed in custom post type posts
			$content .= '<ul>' . ($this->return_post_type_posts_WHS_digihood($pt_object) ? $this->return_post_type_posts_WHS_digihood($pt_object) : __('tento post-type neobsahuje žádné příspěvky', 'digiSM')) . '</ul>';
		} else {
			$content = __('Notice: hledaný post-type neexistuje', 'digiSM');
		}

		$result = $before . $content . $after;
		return $result;
	}


	/**
	 * Create final content for tax map
	 * @since 3.0
	 * @param none
	 * @return string
	 * 
	 * Processes all of the posts associated with specified taxonomy/term, if the term is not specified, returns all of the posts inside the taxonomy.
	 */
	function create_tax_posts_shortcode($atts)
	{ //je $atts[taxonomy] classa wptax?  validace + pro terms
		$param = shortcode_atts(array(
			'taxonomy' => '',
			'term' => '',
			//'blacklist' => '', // BLACKLISTING NOT YET SUPPORTED FOR THIS TAG
		), $atts);

		array_map("htmlspecialchars", $param);

		$return = "";
		$content = "";
		$before = '<div class="whs-wrap" style="clear: both;">';
		$after = "</div>";

		$tax_object = get_taxonomy($param['taxonomy']);
		$term_object = get_term_by('slug', $param['term'], $param['taxonomy']);

		if (is_a($tax_object, "WP_Taxonomy")) {
			if ((is_a($term_object, 'WP_Term') || empty($param['term']))) {
				$tax_args = array(
					array(
						'taxonomy' => $param['taxonomy'],
						'terms' => $param['term'],
						'field' => 'slug',
					),
				);
				// add all the terms if the terms argument is empty
				if (empty($param['term'])) {
					// retrieve the term_id list for given taxonomy
					$the_terms = array();
					foreach (get_terms(array('taxonomy' => $param['taxonomy'])) as $term) {
						$the_terms[] = $term->term_id;
					}
					// change $tax_args fields values to show the posts of all the terms in given taxonomy
					$tax_args[0]['field'] = 'term_id';
					$tax_args[0]['terms'] = $the_terms;
				}

				$args = array(
					'posts_per_page' => -1,
					'post_status' => 'publish',
					'orderby' => 'date',
					'order' => 'ASC',
					'tax_query' => $tax_args,
				);
				$query = new \WP_Query($args);

				if ($query->have_posts()) {
					$posts = $query->posts;
					$options_field =  str_replace('_', '', get_locale()) . $param['taxonomy'];
					$options_name = Globals::d1g1_get_option('mainSettings', $options_field);
					if (!empty($options_name)) {
						$name = $options_name;
					} else {
						$name = $tax_object->labels->name;
					}
					$content .= '<h2>' . $name . '</h2>';
					if (!is_wp_error($posts) && !empty($posts)) {
						$content .= '<ul>';
						foreach ($posts as $post) {
							//get the post values
							$the_title = $post->post_title;
							$the_url = get_the_permalink($post->ID);
							//add the post to the list 
							$content .= '<li><a href="' . $the_url . '">' . $the_title . '</a></li>';
						}
						$content .= '</ul>';
					}
				} else {
					$content .= '<h3>' . __('Tato taxonomie/term neobsahuje žádné posty', 'digiSM') . '</h3>';
				}

				wp_reset_postdata();
			} else {
				$content .= '<h3>' . __('Zadaný term v dané taxonomii neexistuje', 'digiSM') . '</h3>';
			}
		} else {
			$content .= '<h3>' . __('Zadaná taxonomie neexistuje: ', 'digiSM') . ($param['taxonomy'] ? $param['taxonomy'] : 'žádná nezadána') . '</h3>';
		}
		$result = $before . $content . $after;
		return $result;
	}


	/**
	 * Create Attachment Lists
	 * @since 1.03
	 * @param none
	 * @return string
	 * 
	 * Retrieves attachments (media files), the only query option is currently file_extension, enabling image / docs attachment sitemaps.
	 * 
	 */

	function create_attachment_sitemap($atts)
	{
		$param = shortcode_atts(array('file-type' => ''), $atts);

		array_map("htmlspecialchars", $param);

		$content = "";
		$before = '<div class="whs-wrap" style="clear: both;">';
		$after = "</div>";

		$args = array(
			'post_type' => 'attachment',
			'numberposts' => -1,
			'post_status' => null,
			'post_parent' => null, // any parent
		);

		if (isset($param['file-type'])) {
			$args['post_mime_type'] = $param['file-type'];
		}

		$attachments = get_posts($args);

		if ($attachments) {

			$content .= '<h2>' . __('Přiložené soubory: ', 'digiSM') . '</h2>';
			$content .= '<ul>';

			foreach ($attachments as $attachment) {

				$the_title = $attachment->post_title;
				$the_url = wp_get_attachment_url($attachment->ID);
				// Get the file path -> extension
				$file_extension = pathinfo(get_attached_file($attachment->ID), PATHINFO_EXTENSION);

				$content .= '<li><a href="' . $the_url . '" target="_blank">' . $the_title . '.' . $file_extension . '</a></li>';
			}
			$content .= '</ul>';
		} else {
			$content .= '<h3>' . __('Žádne přílohy nebyli nalezeny, pokud používáte mime-type, ujistěte se že existuje a je správně zapsaný') . '</h3>';
		}
		$result = $before . $content . $after;

		return $result;
	}

	/**
	 * Create List of taxonomies and their term pages
	 * @since 1.03
	 * @param none
	 * @return string
	 * Spefify taxonomy to list out with tax="slug" parameter.
	 * 
	 */
	function create_tax_terms_shortcode($atts)
	{
		$param = shortcode_atts(array(
			'taxonomy' => '',
			'hide-empty' => false,
			//'blacklist' => '', // BLACKLISTING NOT YET SUPPORTED FOR THIS TAG
		), $atts);

		array_map("htmlspecialchars", $param);

		$content = "";
		$before = '<div class="whs-wrap" style="clear: both;">';
		$after = "</div>";

		if(!empty($param['hide-empty'] && $param['hide-empty'] != 'true' && $param['hide-empty'] != 'false')){
			$content .= '<h2>' . __('Špatný parametr hide-empty.', 'digiSM') . '</h2>';
			return $before . $content . $after;
		}
		$hide_empty = $param['hide-empty'] == 'true' ? true : false;
		$taxonomy = get_taxonomy($param['taxonomy']);
		if ($taxonomy == false) {
			$content .= '<h2>' . __('Zadaná taxonomie neexistuje', 'digiSM') . '</h2>';
			return $before . $content . $after;
		}
		$terms = get_terms( array(
			'taxonomy'   => $taxonomy->name,
			'hide_empty' => $hide_empty,
		));
		if(empty($terms)){
			$content .= '<h2>' . __('Tato taxonomie neobsahuje žádné termy', 'digiSM') . '</h2>';
			return $before . $content . $after;
		}
		
		$options_field =  str_replace('_', '', get_locale()) . $param['taxonomy'];
		$options_name = Globals::d1g1_get_option('mainSettings', $options_field);
		if (!empty($options_name)) {
			$name = $options_name;
		} else {
			$name = $taxonomy->label;
		}
		$content .= '<h2>' . $name . '</h2> <ul>';
		$public = ($taxonomy->public == 1) ? true : false;
		if($public){
			foreach($terms as $term){
				$content .= '<li><a href="' . get_term_link($term) . '">' . $term->name . '</a></li>';
			}
		}else{
			foreach($terms as $term){
				$content .= '<li>' . $term->name . '</li>';
			}
		}
		$content .= '</ul>';
		
		$result = $before . $content . $after;
		return $result;
	}
}

//activate plugin
if (!is_admin())
	new digihoodHtmlSitemap();
