<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if( ! class_exists( 'sendEmaild1g1' ) )
{
  class sendEmaild1g1
  {
    private static $background = '#2C3038';
    private static $fontColorText = '#151B24';
    private static $fontColorLink = '#151B24';
    private static $fontColorFooter = '#747880';
    private static $fontSize = '12px';
    private static $lineHeight = '16px';
    private static $fontFamily = '16px';
    private static $buttonColor = array('bg'=> '#B0EA91!important', 'color'=>'#151B24', 'hover'=> '#ffffff!important');

    public function __construct(){
      add_filter( 'wp_mail_from_name', [$this, 'my_mail_from_name'] );
      add_filter( 'wp_mail_from', [$this, 'my_mail_from'] );
      add_filter( 'wp_mail', [$this, 'change_headers'] );
      add_action( 'init', [$this,'email_test'] );
    }

    private static function default_font_style(){
      $css = 'font-size: '.self::$fontSize.'; 
      line-height: '.self::$lineHeight.'; 
      font-family: '.self::$fontFamily.'; 
      color: '.self::$fontColorText.';';
      return $css;
    }
    private static function text_settings(){
      $text = array(
        'footer_copy'=> '© '. date('Y') .' COPY TEXT',
        'footer_text'=> 'Tento email byl odeslán z webu '. get_home_url()
      );
      return $text;
    }
    
    function my_mail_from_name( $name ) {
      return d1g1Settings::email_name();
    }

    function my_mail_from( $email ) {
      return d1g1Settings::email_from_d1g1();
    }

    function change_headers($args) {

      $mailheader = "Reply-To: produkce@gramon.cz\r\n";
      $mailheader .= "MIME-Version: 1.0\r\n";
      $mailheader .= "Content-Type: text/html; charset=utf-8\r\n";

      $args['headers'] = $mailheader;

      return $args;

    }

    /*
    wraps email body into prestyled html content
    ==============================================================*/

    public static function email_content(
      $title, 
      $subtitle="", 
      $body="",
      $footer="", 
      $button_link = "", 
      $button_text = ""
      ){
        $return ='<!DOCTYPE html>
            <html lang="cs">
            <head>
            <title>'. $title .'</title>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width">
            <style type="text/css">'.
            self::mail_default_css().
            '</style>
            </head>
            <body style="margin: 0; padding: 0; background: #f6f6f6;">
            <table  border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td>
                        <div align="center" style="padding: 20px 15px 0px 15px;">
                            <table style="background: #ffffff;" border="0" cellpadding="0" cellspacing="0" width="600" class="wrapper">
                                <tr>
                                    <td style="padding: 30px 30px 0 30px;" class="logo">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td bgcolor="" width="600" align="left">
                                                  <a href="'.home_url().'"><img src="'.home_url() .'/wp-content/themes/fleraacademy/assets/images/logo_tmave.png" width="200" height="auto" style="width: 200px; height:auto;"></a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td align="center" class="section-padding">
                        <table border="0" cellpadding="0" cellspacing="0" width="600" class="responsive-table">
                            <tr>
                                <td bgcolor="#ffffff" style="padding: 20px ; font-size: '.self::$fontSize.'; line-height: '.self::$lineHeight.'; font-family:'.self::$fontFamily.'; color: '.self::$fontColorText.';">
                                <div style="padding: 20px;">
                                    <table  width="100%" border="0" cellspacing="0" cellpadding="0">';
                                        if (!empty($title) ) {
                                          $return .= '<tr><td align="center" style="font-size: 40px; line-height: 48px; font-family: Georgia, Arial, sans-serif; color: '.self::$fontColorText.'; padding:20px 5%" class="padding-copy">' .$title. '</td></tr>';
                                          if ( !empty($subtitle))
                                          $return .= '<tr><td align="center" style="font-size: 20px; line-height: 26px; font-family: '.self::$fontFamily.'; color: '.self::$fontColorText.'; padding:20px 5%" class="padding-copy">' .$subtitle. '</td></tr>';
                                        }

                                      if ( $body ) { 
                                        foreach ($body as $paragraph) {

                                          $return .= '<tr><td align="center" style="'.self::default_font_style().' padding: 0 5% 20px 5%;" class="padding-copy">' .$paragraph. '</td></tr>';

                                        }
                                      }
                                      if ( $button_link && $button_text ) { 
                                          
                                        $return .= ' <tr>
                                            <td align="center" style="padding: 20px 0 20px 0; font-size: '.self::$fontSize.'; line-height: 25px; font-family: Arial, sans-serif; color: #666666;" class="padding-copy">
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                  <tr>
                                                    <td align="center">
                                                      <div>';
                                                        $return .= '<a class="button" href="'.$button_link.'" style="background-color:'.self::$buttonColor["bg"].';  color:'.self::$buttonColor["color"].'; display:inline-block;font-family:'.self::$fontFamily.';font-size:'.self::$fontSize.'; letter-spacing: 0.35px; font-weight: 700; text-transform: uppercase; padding: 10px 20px; line-height: '.self::$lineHeight.';text-align:center;text-decoration:none;max-width:312px;-webkit-text-size-adjust:none;mso-hide:all;">'.$button_text.'</a>
                                                      </div>
                                                    </td>
                                                  </tr>
                                                </table>
                                            </td>
                                        </tr>';
                                    }

                                        $return .= '<tr>
                                            <td align="center" style="padding: 15px 5% 20px 5%; font-size: 12px; line-height: 25px; font-family: '.self::$fontFamily.'; color: '.self::$fontColorText.';" class="padding-copy">'.$footer.'                                            
                                            </td>
                                        </tr>                                      
                                    </table>
                                </div>
                                            
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>'
            .self::render_mail_footer().'
            </body>
            </html>';

            // echo $return;
            // die();

            return $return;

    }
    private static function render_mail_footer() {
      $text = self::text_settings();
      $fb = get_field('facebook_address', 'option') ;
      $insta = get_field('instagram_address', 'option') ;
      $pinte = get_field('pinterest_address', 'option') ;
      $yt = get_field('youtube_address', 'option') ;
      $contact_link = (method_exists(linksd1g1::class, 'contact_link') ? linksd1g1::contact_link( ) : '');
      $business_terms = (method_exists(linksd1g1::class, 'business_terms' ) ? linksd1g1::business_terms( ) : '');
      $gdpr = (method_exists(linksd1g1::class, 'gdpr' ) ? linksd1g1::gdpr( ) : '');

      $return = '<table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
          <td align="center">
              <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" id="footer-email">
                  <tr>
                      <td style="padding: 0px 0px 20px 0px;">
                          <table style="background: '.self::$background.'; color:'.self::$fontColorFooter.';" width="600" border="0" cellspacing="0" cellpadding="0" align="center" class="responsive-table">';
                            if ($contact_link || $business_terms || $gdpr){  
                              $return .='<tr>
                                <td align="center" valign="middle" style="font-size: '.self::$fontSize .'; line-height: '. self::$lineHeight.'; font-family: '.self::$fontFamily.'; padding-top: 35px;">';
                                  if ($contact_link){
                                    $return .='<a href="'.$contact_link.'" target="_blank" style="color: #ffffff;">Kontakt</a>
                                    <span style="color: #ffffff; padding: 0 10px">|</span>';
                                  }
                                    
                                  if ($business_terms){
                                    $return .='<a href="'. $business_terms.'" target="_blank" style="color: #ffffff;">Obchodní podmínky</a>
                                    <span style="color: #ffffff; padding: 0 10px">|</span>';
                                  }
                                  if ($gdpr){
                                      $return .='<a href="'.$gdpr.'" target="_blank" style="color: #ffffff;">Ochrana osobních údajů</a>';
                                  }
                                $return .='</td>
                              </tr>';
                            }
                              if (!empty($fb) || !empty($insta) || !empty($pinte) || !empty($yt)) {
                              $return .='<tr>
                                <td align="center" valign="middle" style="font-size: '.self::$fontSize .'; line-height: '. self::$lineHeight.'; font-family: '.self::$fontFamily.'; padding: 35px 5px 20px 5px;">';
                                if (! empty($fb)) { $return .= '<a href="'.$fb.'" target="_blank" style="color: '.self::$fontColorFooter.'; text-decoration: none; padding:0 10px">
                                  <img src="'.home_url().'/wp-content/themes/fleraacademy/assets/svg/mail/facebook.svg" width="30" height="30" style="width: auto; height:30px;">
                                  </a>'; }
                                if (! empty($yt)) { $return .= '<a href="'.$yt.'" target="_blank" style="color: '.self::$fontColorFooter.'; text-decoration: none; padding:0 10px">
                                  <img src="'.home_url().'/wp-content/themes/fleraacademy/assets/svg/mail/youtube.svg" width="30" height="30" style="width: auto; height:30px;">
                                  </a>'; }
                                if (! empty($insta)) { $return .= '<a href="'.$insta.'" target="_blank" style="color: '.self::$fontColorFooter.'; text-decoration: none; padding:0 10px">
                                  <img src="'.home_url().'/wp-content/themes/fleraacademy/assets/svg/mail/instagram.svg" width="30" height="30" style="width: auto; height:30px;">
                                  </a>'; }
                                if (! empty($pinte)) { $return .= '<a href="'.$pinte.'" target="_blank" style="color: '.self::$fontColorFooter.'; text-decoration: none; padding:0 10px">
                                  <img src="'.home_url().'/wp-content/themes/fleraacademy/assets/svg/mail/pinterest.svg" width="30" height="30" style="width: auto; height:30px;">
                                  </a>'; }
                                $return .='</td>
                              </tr>'; }
                              $return .='<tr>
                                  <td align="center" valign="middle" style="font-size: '.self::$fontSize .'; line-height: '. self::$lineHeight.'; font-family: '.self::$fontFamily.'; padding: 15px 5px 0px 5px;">
                                  '. $text['footer_copy'].'
                                  </td>
                              </tr>
                              <tr>
                                <td align="center" valign="middle" style="font-size: '.self::$fontSize .'; line-height: '. self::$lineHeight.'; font-family: '.self::$fontFamily.'; padding: 0 5px;">
                                  '.$text['footer_text'].'
                                </td>
                              </tr>
                              <tr>
                                <td align="center" valign="middle" style="font-size: '.self::$fontSize .'; line-height: '. self::$lineHeight.'; font-family: '.self::$fontFamily.'; padding: 0px 5px 20px 5px;">
                                  <a href="'.home_url().'" target="_blank" style="color: '.self::$fontColorFooter.'; text-decoration: underline;">'.home_url().'</a>
                                </td>
                              </tr>
                              <tr>
                              
                            </tr>
                          </table>
                      </td>
                  </tr>
              </table>
          </td>
        </tr>
      </table>';
      return $return;
    }

    /* Add default styles to email 
    ========================================================*/
    private static function mail_default_css(){
      $css = '#outlook a{padding:0;}
      .ReadMsgBody{width:100%;} .ExternalClass{width:100%;}
      .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;}
      body, table, td, a{-webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;}
      table, td{mso-table-lspace:0pt; mso-table-rspace:0pt;}
      img{-ms-interpolation-mode:bicubic;}

      body{margin:0; padding:0;}
      img{border:0; height:auto; line-height:100%; outline:none; text-decoration:none;}
      table{border-collapse:collapse !important;}
      body{height:100% !important; margin:0; padding:0; width:100% !important;}

      .appleBody a {color:#666666; text-decoration: none;}
      .appleFooter a {color:#666666; text-decoration: none;}
      a {
        color: #2199e8;
      }
      a.button:hover {
          text-decoration: none !important;
          color: '.self::$buttonColor['hover'].';
          background :'.self::$buttonColor['bg'].';
          border-color: '.self::$buttonColor['bg'].';
      }

      @media screen and (max-width: 525px) {

          table[class="wrapper"]{
            width:100% !important;
          }

          td[class="logo"]{
            text-align: left;
            padding: 20px 0 20px 0 !important;
          }

          td[class="logo"] img{
            margin:0 auto!important;
          }

          td[class="mobile-hide"]{
            display:none;
          }

          img[class="mobile-hide"]{
            display: none !important;
          }

          img[class="img-max"]{
            max-width: 100% !important;
            height:auto !important;
          }

          table[class="responsive-table"]{
            width:100%!important;
          }

          td[class="padding"]{
            padding: 10px 5% 15px 5% !important;
          }

          td[class="padding-copy-to-center"]{
            padding: 10px 5% 10px 5% !important;
            text-align: center;
          }

          td[class="padding-copy"]{
            padding: 10px 5% 10px 5% !important;
          }

          td[class="padding-meta"]{
            padding: 30px 5% 0px 5% !important;
            text-align: center;
          }

          td[class="no-pad"]{
            padding: 0 0 20px 0 !important;
          }

          td[class="no-padding"]{
            padding: 0 !important;
          }

          td[class="section-padding"]{
            padding: 30px 15px 30px 15px !important;
          }

          td[class="section-padding-bottom-image"]{
            padding: 30px 15px 0 15px !important;
          }

          td[class="mobile-wrapper"]{
              padding: 10px 5% 15px 5% !important;
          }

          table[class="mobile-button-container"]{
              margin:0 auto;
              width:100% !important;
          }

          a[class="mobile-button"]{
              width:80% !important;
              padding: 15px !important;
              border: 0 !important;
              font-size: 12px !important;
          }

      }';
      return $css;
    }

    /* Send admin email
    ========================================================*/

    public static function send_admin_email( $subject, $message ) {
        $headers = array( 'Content-Type: text/html; charset=UTF-8 ' );
        $to = get_field( 'admin_email', 'options');
        //submit admin email
        if ( $to && $subject && $message ) {
          wp_mail( $to, $subject, $message, $headers );
        }
    } 

    /* Create email to send to the user
    ========================================================*/

    public static function send_client_emails( $mail, $subject, $message ){ 

      if (empty($subject)) {
          $subject = __('', 'cba' );
      }

      $headers = array( 'Content-Type: text/html; charset=UTF-8 ');

      if ( $mail && $subject && $message ) {
        wp_mail(  $mail, $subject, $message, $headers );
        return true;
      }  

      return false;
    }

    /* Test mailů
    ========================================================*/
    public function email_test() {
      if ( isset($_GET['showmail']) &&$_GET['showmail']==1 ) {
        echo self::email_content( 'title', 'subtitle',array('test', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nullam sapien sem, ornare ac, nonummy non, lobortis a enim. Etiam egestas wisi a erat. Vivamus ac leo pretium faucibus. Maecenas sollicitudin. Morbi leo mi, nonummy eget tristique non, rhoncus non leo. Vivamus luctus egestas leo. Suspendisse sagittis ultrices augue. Etiam dui sem, fermentum vitae, sagittis id, malesuada in, quam. Nullam justo enim, consectetuer nec, ullamcorper ac, vestibulum in, elit. Aliquam ante. Aliquam erat volutpat. In convallis.'),
        'footer', 'https://digihood.cz', 'ahoj' );
        die('semtu');
      }
    }

  } 
  new sendEmaild1g1;
}