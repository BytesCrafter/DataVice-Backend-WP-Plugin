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
    require plugin_dir_path(__FILE__) . '/v1/users/class-contact.php';
    require plugin_dir_path(__FILE__) . '/v1/users/class-verify.php';
    require plugin_dir_path(__FILE__) . '/v1/users/class-forgotpass.php';
    require plugin_dir_path(__FILE__) . '/v1/users/class-changepass.php';
	require plugin_dir_path(__FILE__) . '/v1/users/class-data.php';
	require plugin_dir_path(__FILE__) . '/v1/users/class-signup.php';
	require plugin_dir_path(__FILE__) . '/v1/globals/class-globals.php';
	
	// Init check if USocketNet successfully request from wapi.
    function datavice_route()
    {
        register_rest_route( 'datavice/v1/user', 'auth', array(
            'methods' => 'POST',
            'callback' => array('DV_Authenticate','initialize'),
        ));
        // new
        register_rest_route( 'datavice/v1/user', 'contact', array(
            'methods' => 'POST',
            'callback' => array('DV_Contact','add_contact'),
        ));

        register_rest_route( 'datavice/v1/user', 'verify', array(
            'methods' => 'POST',
            'callback' => array('DV_Verification','initialize'),
            
        ));

        register_rest_route( 'datavice/v1/user', 'forgot', array(
            'methods' => 'POST',
            'callback' => array('DV_Forgotpassword','initialize'),
        ));

        register_rest_route( 'datavice/v1/user', 'reset', array(
            'methods' => 'POST',
            'callback' => array('DV_Changepassword','initialize'),
        ));

        register_rest_route( 'datavice/v1/user', 'data', array(
            'methods' => 'POST',
            'callback' => array('DV_Userdata', 'initialize'),
        ));

        register_rest_route( 'datavice/v1/user', 'signup', array(
            'methods' => 'POST',
            'callback' => array('DV_Signup', 'initialize'),
        ));

        register_rest_route( 'datavice/v1/feeds', 'profile', array(
            'methods' => 'POST',
            'callback' => array('DV_Newsfeed', 'get_feeds'),
        ));

        register_rest_route( 'datavice/v1/feeds', 'p_feeds', array(
            'methods' => 'POST',
            'callback' => array('DV_Newsfeed', 'get_additional_feeds'),
        ));

        register_rest_route( 'datavice/v1/feeds', 'home', array(
            'methods' => 'POST',
            'callback' => array('DV_Newsfeed', 'home_feeds'),
        ));

        register_rest_route( 'datavice/v1/feeds', 'home_add_feeds', array(
            'methods' => 'POST',
            'callback' => array('DV_Newsfeed', 'home_additional_feeds'),
        ));

        register_rest_route( 'datavice/v1/user', 'add_address', array(
            'methods' => 'POST',
            'callback' => array('DV_Userdata', 'add_address'),
        ));

        register_rest_route( 'datavice/v1/user', 'ctry', array(
            'methods' => 'POST',
            'callback' => array('DV_Userdata', 'get_countries'),
        ));

        register_rest_route( 'datavice/v1/user', 'prv', array(
            'methods' => 'POST',
            'callback' => array('DV_Userdata', 'get_provinces'),
        ));

        register_rest_route( 'datavice/v1/user', 'city', array(
            'methods' => 'POST',
            'callback' => array('DV_Userdata', 'get_cities'),
        ));

        register_rest_route( 'datavice/v1/user', 'brgy', array(
            'methods' => 'POST',
            'callback' => array('DV_Userdata', 'get_brgy'),
        ));

                
    }
    add_action( 'rest_api_init', 'datavice_route' );

?>