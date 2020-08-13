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

    // For debugging purpose only.
    require plugin_dir_path(__FILE__) . '/test/demoguy.php';

    //Require the USocketNet class which have the core function of this plguin. 

    //Users Classes
    require plugin_dir_path(__FILE__) . '/v1/users/class-signup.php';
    require plugin_dir_path(__FILE__) . '/v1/users/class-reset.php';
    require plugin_dir_path(__FILE__) . '/v1/users/class-forgot.php';
    require plugin_dir_path(__FILE__) . '/v1/users/class-auth.php';
    require plugin_dir_path(__FILE__) . '/v1/users/class-verify.php';
    require plugin_dir_path(__FILE__) . '/v1/users/class-profile.php';
    require plugin_dir_path(__FILE__) . '/v1/users/class-stats.php';

    // Contacts Classes
    require plugin_dir_path(__FILE__) . '/v1/contacts/class-insert.php';
    require plugin_dir_path(__FILE__) . '/v1/contacts/class-listing.php';
    require plugin_dir_path(__FILE__) . '/v1/contacts/class-update.php';
    require plugin_dir_path(__FILE__) . '/v1/contacts/class-select.php';
    require plugin_dir_path(__FILE__) . '/v1/contacts/class-delete.php';
    require plugin_dir_path(__FILE__) . '/v1/contacts/class-select-type.php';

    //Locations Classes
    require plugin_dir_path(__FILE__) . '/v1/location/class-countries.php';
    require plugin_dir_path(__FILE__) . '/v1/location/class-provinces.php';
    require plugin_dir_path(__FILE__) . '/v1/location/class-cities.php';
    require plugin_dir_path(__FILE__) . '/v1/location/class-barangays.php';
    require plugin_dir_path(__FILE__) . '/v1/location/class-timezone.php';
 
    ///Users Address Classes
    require plugin_dir_path(__FILE__) . '/v1/address/class-insert.php';
    require plugin_dir_path(__FILE__) . '/v1/address/class-update.php';
    require plugin_dir_path(__FILE__) . '/v1/address/class-delete.php';
    require plugin_dir_path(__FILE__) . '/v1/address/class-select.php';
    require plugin_dir_path(__FILE__) . '/v1/address/class-listing.php';
    require plugin_dir_path(__FILE__) . '/v1/address/class-select-type.php';

	require plugin_dir_path(__FILE__) . '/v1/globals.php';
	
	// Init check if USocketNet successfully request from wapi.
    function datavice_route()
    {
        /*
         * TEST RESTAPI
        */

            register_rest_route( 'datavice/test', 'demoguy', array(
                'methods' => 'POST',
                'callback' => array('DV_Demoguy', 'listen'),
            ));

        /*
         * USER RESTAPI
        */

            register_rest_route( 'datavice/v1/user', 'signup', array(
                'methods' => 'POST',
                'callback' => array('DV_Signup', 'listen'),
            ));

            register_rest_route( 'datavice/v1/user', 'reset', array(
                'methods' => 'POST',
                'callback' => array('DV_Reset','listen'),
            ));

            register_rest_route( 'datavice/v1/user', 'forgot', array(
                'methods' => 'POST',
                'callback' => array('DV_Forgot','listen'),
            ));

            register_rest_route( 'datavice/v1/user', 'auth', array(
                'methods' => 'POST',
                'callback' => array('DV_Authenticate','listen'),
            ));

            register_rest_route( 'datavice/v1/user', 'verify', array(
                'methods' => 'POST',
                'callback' => array('DV_Verification','listen'),
                
            ));

            register_rest_route( 'datavice/v1/user', 'profile', array(
                'methods' => 'POST',
                'callback' => array('DV_Userprofile', 'listen'),
            ));

            register_rest_route( 'datavice/v1/user', 'stats', array(
                'methods' => 'POST',
                'callback' => array('DV_Stats', 'listen'),
            ));

        /*
         * CONTACT RESTAPI
        */
     
            register_rest_route( 'datavice/v1/contact/user', 'insert', array(
                'methods' => 'POST',
                'callback' => array('DV_Contact_Insert','listen'),
            ));

            register_rest_route( 'datavice/v1/contact', 'list/all', array(
                'methods' => 'POST',
                'callback' => array('DV_Contact_Listing','listen'),
            ));

            register_rest_route( 'datavice/v1/contact', 'update', array(
                'methods' => 'POST',
                'callback' => array('DV_Contact_Update','listen'),
            ));

            register_rest_route( 'datavice/v1/contact', 'select', array(
                'methods' => 'POST',
                'callback' => array('DV_Contact_Select','listen'),
            ));

            register_rest_route( 'datavice/v1/contact', 'list/type', array(
                'methods' => 'POST',
                'callback' => array('DV_Contact_Type','listen'),
            ));

            register_rest_route( 'datavice/v1/contact', 'delete', array(
                'methods' => 'POST',
                'callback' => array('DV_Contact_Delete','listen'),
            ));

        /*
         * LOCATION RESTAPI
        */
            register_rest_route( 'datavice/v1/location/country', 'active', array(
                'methods' => 'POST',
                'callback' => array('DV_Countries', 'listen'),
            ));  
            
            register_rest_route( 'datavice/v1/location/province', 'active', array(
                'methods' => 'POST',
                'callback' => array('DV_Provinces', 'listen'),
            ));

            register_rest_route( 'datavice/v1/location/city', 'active', array(
                'methods' => 'POST',
                'callback' => array('DV_Cities', 'listen'),
            ));

            register_rest_route( 'datavice/v1/location/brgy', 'active', array(
                'methods' => 'POST',
                'callback' => array('DV_Barangays', 'listen'),
            ));

            register_rest_route( 'datavice/v1/location', 'timezone', array(
                'methods' => 'POST',
                'callback' => array('DV_Timezone', 'listen'),
            ));
        /*
         * ADDRESS RESTAPI
        */
   
            register_rest_route( 'datavice/v1/address', 'insert', array(
                'methods' => 'POST',
                'callback' => array('DV_Insert_Address', 'listen'),
            ));

            register_rest_route( 'datavice/v1/address', 'update', array(
                'methods' => 'POST',
                'callback' => array('DV_Update_Address', 'listen'),
            ));

            register_rest_route( 'datavice/v1/address', 'delete', array(
                'methods' => 'POST',
                'callback' => array('DV_Delete_Address', 'listen'),
            ));
            
            register_rest_route( 'datavice/v1/address', 'select', array(
                'methods' => 'POST',
                'callback' => array('DV_Select_Address', 'listen'),
            ));

            register_rest_route( 'datavice/v1/address', 'list/type', array(
                'methods' => 'POST',
                'callback' => array('DV_Select_type_Address', 'listen'),
            ));

            register_rest_route( 'datavice/v1/address', 'list/all', array(
                'methods' => 'POST',
                'callback' => array('DV_Select_All_Address', 'listen'),
            ));

        /*
         * Start unknown
        */
    

    }
    add_action( 'rest_api_init', 'datavice_route' );
