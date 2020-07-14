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
?>

<?php

    //Require the USocketNet class which have the core function of this plguin. 
    require plugin_dir_path(__FILE__) . '/v1/users/class-auth.php';
	require plugin_dir_path(__FILE__) . '/v1/users/class-verify.php';
	
	// Init check if USocketNet successfully request from wapi.
    function datavice_route()
    {
        register_rest_route( 'datavice/v1/user', 'auth', array(
            'methods' => 'POST',
            'callback' => array('DVC_Authenticate','initialize'),
        ));

        register_rest_route( 'datavice/v1/user', 'verify', array(
            'methods' => 'POST',
            'callback' => array('DVC_Verification','initialize'),
        ));
    }
    add_action( 'rest_api_init', 'datavice_route' );

?>