<?php

/**
 * Setup
 * 
 * @author Digihood
 */

if (! defined('ABSPATH')) {
  exit;
}

if (! class_exists('d1g1ThemeSetup')) {
  class d1g1ThemeSetup
  {

    public function __construct()
    {

      //ovládání admin menu
      //add_action( 'admin_menu', [$this,'remove_menu_pages'] );

      //nastavení šablony
      add_action('after_setup_theme', [$this, 'site_setup']);

      //požadované pluginy
      add_action('init', [$this, 'theme_die_d1g1']);

      //GTM
      add_action('wp_head', [$this, 'add_main_gtm'], PHP_INT_MAX);
      add_action('wp_body_open', [$this, 'add_gtm_noindex']);

      //excerpt filter
      if (!is_admin()) {
        remove_filter('get_the_excerpt', [$this, 'wp_trim_excerpt']);
        add_filter('get_the_excerpt', [$this, 'filter_excerpt']);
      }

      //oprava preview modu
      add_filter('_wp_post_revision_fields', [$this, 'add_field_debug_preview_d1g1']);
      add_action('edit_form_after_title', [$this, 'add_input_debug_preview_d1g1']);

      //přidat místo pod zápatím
      add_action('wp_body_open', [$this, 'after_body_open']);
    }

    /**
     * Removed admin links in menu
     *
     * @param none
     * 
     * @author Digihood
     * @return void
     */

    public function remove_menu_pages()
    {
      //remove_menu_page('edit-comments.php');
    }

    /**
     * Základní nastavení šablony
     *
     * @param none
     * 
     * @author Digihood
     * @return void
     */
    function site_setup()
    {

      //load theme text domain
      load_theme_textdomain('cba', get_template_directory() . '/languages');

      // Enable support 
      add_theme_support('post-thumbnails');
      add_theme_support('menus');
      add_theme_support("title-tag");
      add_filter('widget_text', 'do_shortcode');

      //ACF options pages
      if (function_exists('acf_add_options_page')) {
        acf_add_options_page([
          'page_title' => 'Nastavení webu',
          'menu_title' => 'Nastavení webu',
          'menu_slug'  => 'acf-options',
          'capability' => 'edit_posts',
          'redirect'   => false,
        ]);
        acf_add_options_sub_page([
          'page_title' => 'Záhlaví & zápatí',
          'menu_title' => 'Záhlaví & zápatí',
          'menu_slug'  => 'acf-options-zahlavi',
          'parent_slug' => 'acf-options',
        ]);
      }

      // Add custom image sizes
      /*add_image_size('page-banner', 1900, 500, true);
      add_image_size('vertical', 1000, 2000, true);
      add_image_size('room-thumb', 600, 500, true);
      add_image_size('gallery-thumb', 440, 330, true);
      add_image_size('gallery-tiny', 220, 150, true);*/

      //remove default image sizes
      remove_image_size('1536x1536');
      remove_image_size('2048x2048');

      // Register menus
      register_nav_menus(
        array(
          'primary'  => __('Hlavní menu', 'cba'),
          'mobile'   => __('Mobilní menu', 'cba'),
          'footer-1' => __('Zápatí – sloupec 1', 'cba'),
          'footer-2' => __('Zápatí – sloupec 2', 'cba'),
          'footer-3' => __('Zápatí – sloupec 3', 'cba'),
        )
      );

      // Adding post format support
      add_theme_support(
        'post-formats',
        array(
          'aside',             // title less blurb
          'gallery',           // gallery of images
          'link',              // quick link to other site
          'image',             // an image
          'quote',             // a quick quote
          'status',            // a Facebook like status update
          'video',             // video
          'audio',             // audio
          'chat'               // chat transcript
        )
      );

      // Set the maximum allowed width for any content in the theme, like oEmbeds and images added to posts.
      $GLOBALS['content_width'] = 1200;
    }

    /**
     * Ukončí šablonu, pokud nejsou aktivní požadované pluginy
     *
     * @param none
     * 
     * @author Digihood
     * @return void
     */
    public function theme_die_d1g1()
    {

      if (is_admin()) return false;

      if (!function_exists('get_field')) die("Plugin Advanced Custom Fields je požadovaný. Aktivujte ho.");

      //if ( !function_exists( 'is_woocommerce' ) ) die("Plugin Woocommerce je požadovaný. Aktivujte ho.");

    }


    /**
     * přidání GTM do hlavičky
     *
     * @param none
     * 
     * @author Digihood
     * @return true/false
     */

    public function add_main_gtm()
    {

      if (is_production_d1g1()) { ?>

      <?php }
    }

    /**
     * přidání GTM fallbacku pro noscripty za BODY tagem
     *
     * @param none
     * 
     * @author Digihood
     * @return true/false
     */

    public function add_gtm_noindex()
    {

      //zde vložte inframe kód
      if (is_production_d1g1()) { ?>

      <?php }
    }

    /**
     * Removes [...] from the excerpt and allows you to set the number of words in there
     *
     * @param text
     * 
     * @author Digihood
     * @return true/false
     */
    public function filter_excerpt($text)
    {
      if ($text == '') {
        $text = get_the_content('');
        $text = strip_shortcodes($text);
        //$text = apply_filters('the_content', $text);
        $text = str_replace(']]>', ']]>', $text);
        $text = strip_tags($text);
        $text = nl2br($text);
        $excerpt_length = apply_filters('excerpt_length', 20);
        $words = explode(' ', $text, $excerpt_length + 1);
        if (count($words) > $excerpt_length) {
          array_pop($words);
          array_push($words, '...');
          $text = implode(' ', $words);
        }
      }
      return $text;
    }


    /**
     * Oprava preview módu na 
     *
     * @param none
     * 
     * @author Digihood
     * @return true/false
     */

    function add_field_debug_preview_d1g1($fields)
    {
      $fields["debug_preview"] = "debug_preview";
      return $fields;
    }

    function add_input_debug_preview_d1g1()
    {
      echo '<input type="hidden" name="debug_preview" value="debug_preview">';
    }

    /**
     * Přidá mobilní menu do šablony
     *
     * @param none
     * 
     * @author Digihood
     * @return html
     */
    public function after_body_open()
    { ?>
      <noscript><?= __('Tato webová stránka vyžaduje javascript.', 'cba'); ?></noscript>
      <span class="js-slideout-toggle linear-animation fixed right-5 top-8 z-20 block lg:hidden h-12 aspect-square cross menu--1 overflow-hidden">
        <svg class="w-[200%] h-[200%] absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
          <circle cx="50" cy="50" r="30" />
          <path class="line--1" d="M0 40h62c13 0 6 28-4 18L35 35" />
          <path class="line--2" d="M0 50h70" />
          <path class="line--3" d="M0 60h62c13 0 6-28-4-18L35 65" />
        </svg>
      </span>
    <?php
    }
  }

  new d1g1ThemeSetup;
}

//odebrání emojis
if (!function_exists('disable_wp_emojicons_d1g1')) {

  function disable_wp_emojicons_d1g1()
  {
    // all actions related to emojis
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');

    // filter to remove TinyMCE emojis
    add_filter('tiny_mce_plugins', 'disable_emojicons_tinymce_d1g1');
  }

  add_action('init', 'disable_wp_emojicons_d1g1');
}

if (!function_exists('disable_emojicons_tinymce_d1g1')) {
  function disable_emojicons_tinymce_d1g1($plugins)
  {
    if (is_array($plugins)) {
      return array_diff($plugins, array('wpemoji'));
    } else {
      return array();
    }
  }
}

/**
 * Optimalizace načítání favicon
 */
function optimize_site_icon()
{
  // Odstranění původního wp_site_icon
  remove_action('wp_head', 'wp_site_icon', 99);

  // Přidání optimalizované verze
  add_action('wp_head', function () {
    if (!has_site_icon()) {
      return;
    }

    $icon_url = get_site_icon_url();
    ?>
    <link rel="preload" href="<?php echo esc_url($icon_url); ?>" as="image" type="image/x-icon">
    <link rel="icon" href="<?php echo esc_url($icon_url); ?>" media="print" onload="this.media='all'">
<?php
  }, 99);
}
add_action('after_setup_theme', 'optimize_site_icon');
