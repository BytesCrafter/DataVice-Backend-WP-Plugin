
<?php

/** Exit if accessed directly */ 
if ( ! defined( 'ABSPATH' ) )
{
    exit;
}

/**
    * @package datavice-wp-plugin
    * @version 0.1.0
*/

class DV_File_Upload {

    public static function listen(WP_REST_Request $request) {
        return rest_ensure_response(
            DV_File_Upload::listen_open($request)
        );
    }

    public static function listen_open($request) {

        if ( DV_Verification::is_verified() == false ) {
            return array(
                    "status" => "unknown",
                    "message" => "Please contact your administrator. Verification Issue!",
            );
        }

        $file_param = $request->get_file_params();
        if ( !isset($file_param['img'])) {
            return  array(
                "status" => "unknown",
                "message" => "Please contact your administrator. Image not set!",
            );
        }

        $overwrite = true; //overwrite file
        $max_img_size = 5000000; //5MB
        $wp_upload_dir = wp_upload_dir(); //full path to upload directory.
        $subfolder = 'cardmake/';
        $file_name = "wpid_".$_POST['wpid']."_".$file_param['img']['name'];
        $file_path = $wp_upload_dir['basedir'] .'/'.$subfolder. basename($file_name);
        $file_type = strtolower(pathinfo($file_path,PATHINFO_EXTENSION));

        if (!file_exists($wp_upload_dir['basedir'] .'/'.$subfolder)) {
            mkdir($wp_upload_dir['basedir'] .'/'.$subfolder, 0777, true);
        }

        if(getimagesize($file_param['img']['tmp_name']) === false) {
            return array(
                "status" => "failed",
                "message" => "File is not an image.",
            );
        }

        if (file_exists($file_path) && $overwrite == false) {
            return array(
                "status" => "duplicate",
                "message" => "File is already existed.",
            );
        }

        if ($file_param['img']['size'] > $max_img_size) {
            return array(
                "status" => "failed",
                "message" => "File is too large.",
            );
        }

        if($file_type != "jpg" && $file_type != "png" && $file_type != "jpeg" && $file_type != "gif" ) {
            return array(
                "status" => "failed",
                "message" => "Only JPG, JPEG, PNG & GIF files are allowed.",
            );
        }

        if (!move_uploaded_file($file_param['img']['tmp_name'], $file_path)) { 
            return array(
                "status" => "failed",
                "message" => "Unable to move the file!",
            );
        }

        $file_mime = mime_content_type($file_path);
        $attach_id = wp_insert_attachment( array(
            'guid'           => $file_path,
            'post_mime_type' => $file_mime,
            'post_title'     => preg_replace( '/\.[^.]+$/', '', $file_param['img']['name'] ),
            'post_content'   => '',
            'post_status'    => 'inherit'
        ), $file_path );

        $attach_data = null;
        if ( ! function_exists( 'wp_crop_image' ) ) {
            include( ABSPATH . 'wp-admin/includes/image.php' );
            $attach_data = wp_generate_attachment_metadata( $attach_id, $file_path );
        }

        return array(
            "status" => "success",
            "data" =>  array(
                "baseurl" => $wp_upload_dir['baseurl'].'/'.$subfolder.basename($file_name),
                "attach" => $attach_id,
                "metadata" => $attach_data,
            )
        );
    }
}