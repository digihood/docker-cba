<?php 
namespace pluginbrandslug\framework\Forms;
/**
 * validace formulařu
 *
 * 
 * @author Digihood
 */ 

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if( ! class_exists( 'd1g1ValidFormHandle' ) )
{
	class d1g1ValidFormHandle
	{

        //private $variable; 

		public function __construct()
		{
            //set variable
            //$this->variable = '';
            add_action('wp_ajax_d1g1_frontend_form_dropzone_handle_dropped_files', [$this, 'handle_dropped_files_callback'], 10, 1);
            add_action('wp_ajax_nopriv_d1g1_frontend_form_dropzone_handle_dropped_files', [$this, 'handle_dropped_files_callback'], 10, 1);

            add_action('wp_ajax_d1g1_frontend_form_dropzone_handle_deleted_files', [$this, 'handle_deleted_files_callback'], 10, 1);
            add_action('wp_ajax_nopriv_d1g1_frontend_form_dropzone_handle_deleted_files', [$this, 'handle_deleted_files_callback'], 10, 1);

            add_action('wp_ajax_d1g1_frontend_form_dropzone_get_files', [$this, 'get_files_callback'], 10, 1);
            add_action('wp_ajax_nopriv_d1g1_frontend_form_dropzone_get_files', [$this, 'get_files_callback'], 10, 1);

        }

         /**
         * droped callback file
         *
         * @param 
         *        
         * 
         * @author digihood
         * @return echo
         */ 

        public function handle_dropped_files_callback()
        {
            status_header(200);

            $upload_dir = wp_upload_dir();
            $upload_path = $upload_dir['path'] . DIRECTORY_SEPARATOR;
            $num_files = count($_FILES['file']['tmp_name']);

            $newupload = 0;

            if (!empty($_FILES)) {
                $files = $_FILES;

                foreach ($files as $file) {

                    /* UPLOAD DO MÉDIÍ WORDPRESSU
                    $newfile = array (
                        'name' => $file['name'],
                        'type' => $file['type'],
                        'tmp_name' => $file['tmp_name'],
                        'error' => $file['error'],
                        'size' => $file['size']
                    );

                    $_FILES = array('upload'=>$newfile);
                    */

                    $upload_folder_path = WP_CONTENT_DIR . '/order-files';

                    if (!file_exists($upload_folder_path)) {
                        mkdir($upload_folder_path);
                    }

                    //preprint( $file );
                    $temp = $_FILES['photo']['tmp_name'];
                    $data = $file['name'];
                    $extten = "." . substr($data, strrpos($data, '.') + 1);
                    $file_name = substr($data, 0, strrpos($data, '.'));
                    $finalpath = $upload_folder_path . '/' . $file_name .  $extten;

                    //oprava opakovaných souborů
                    if (file_exists($finalpath)) {

                        for ($i = 1; $i < 999; $i++) {

                            $finalpath = $upload_folder_path . '/' . $file_name . "-" . $i .  $extten;

                            if (!file_exists($finalpath)) break;
                        }
                    }

                    //UPLOAD DO MÉDIÍ WORDPRESSU $newupload = media_handle_upload( $file, 0 );*/                        
                    if (move_uploaded_file($temp, $finalpath)) {
                        $newupload = $finalpath;
                    } else {
                        $newupload = 0;
                    }
                }
            }

            echo $newupload;
            die();
        }

         /**
         * deleted file callback
         *
         * @param u
         * 
         * @author digihood
         * @return die/echo
         */ 

        public function handle_deleted_files_callback()
        {
            if (isset($_REQUEST['media_id'])) {
                $post_id = $_REQUEST['media_id'];

                //$status = wp_delete_attachment($post_id, true);
                $status = !unlink($post_id);

                if ($status)
                    echo json_encode(array('status' => 'OK'));
                else
                    echo json_encode(array('status' => 'FAILED'));
            }

            die();
        }

         /**
         * get files callback
         *
         * @param 
         * 
         * @author digihood
         * @return echo/die
         */ 

        public function get_files_callback()
        {
            $images = [];
            $upload_dir = wp_upload_dir();
            $upload_path = $upload_dir['basedir'] . DIRECTORY_SEPARATOR;
            if (isset($_REQUEST['images'])) {
                $image_ids = $_REQUEST['images'];
                if ($image_ids) {
                    foreach (explode(',', $image_ids) as $image_id) {
                        $meta = wp_get_attachment_metadata($image_id);
                        if ($meta) {
                            $images[] = [
                                'ID' => $image_id,
                                'name' => get_the_title($image_id),
                                'size' => filesize($upload_path . $meta['file']),
                                'url' => wp_get_attachment_url($image_id),
                            ];
                        }
                    }
                }
                echo json_encode($images);
            } else {
                echo json_encode(false);
            }

            die();
        }

    }
     new d1g1ValidFormHandle;
}