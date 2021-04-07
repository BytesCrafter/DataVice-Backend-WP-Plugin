<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
    * @package datavice-wp-plugin
    * @version 0.1.0
    * This is the primary gateway of all the rest api request.
*/

require plugin_dir_path(__FILE__) . '/file/class-upload.php';

// Init check if USocketNet successfully request from wapi.
function datavice_api_v2_route()
{
    /** TEST RESTAPI */
    register_rest_route( 'datavice/v2/file/image', 'upload', array(
        'methods' => 'POST',
        'callback' => array('DV_File_Upload', 'listen'),
    ));
}
add_action( 'rest_api_init', 'datavice_api_v2_route' );