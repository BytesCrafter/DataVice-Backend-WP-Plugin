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

    // For debugging purpose only.
    require plugin_dir_path(__FILE__) . '/test/demoguy.php';

    //Require the USocketNet class which have the core function of this plguin. 
    require plugin_dir_path(__FILE__) . '/v1/users/class-signup.php';
    require plugin_dir_path(__FILE__) . '/v1/users/class-reset.php';
    require plugin_dir_path(__FILE__) . '/v1/users/class-forgot.php';
    require plugin_dir_path(__FILE__) . '/v1/users/class-auth.php';
    require plugin_dir_path(__FILE__) . '/v1/users/class-verify.php';

    require plugin_dir_path(__FILE__) . '/v1/contacts/users/class-insert.php';
    require plugin_dir_path(__FILE__) . '/v1/contacts/users/class-listing.php';
    require plugin_dir_path(__FILE__) . '/v1/contacts/users/class-update.php';
    require plugin_dir_path(__FILE__) . '/v1/contacts/users/class-select.php';
    require plugin_dir_path(__FILE__) . '/v1/contacts/users/class-delete.php';
    require plugin_dir_path(__FILE__) . '/v1/contacts/users/class-select-type.php';

    require plugin_dir_path(__FILE__) . '/v1/contacts/stores/class-insert.php';
    require plugin_dir_path(__FILE__) . '/v1/contacts/stores/class-listing.php';
    require plugin_dir_path(__FILE__) . '/v1/contacts/stores/class-update.php';
    require plugin_dir_path(__FILE__) . '/v1/contacts/stores/class-select.php';
    require plugin_dir_path(__FILE__) . '/v1/contacts/stores/class-delete.php';
    require plugin_dir_path(__FILE__) . '/v1/contacts/stores/class-select-type.php';

    require plugin_dir_path(__FILE__) . '/v1/location/class-countries.php';
    require plugin_dir_path(__FILE__) . '/v1/location/class-provinces.php';
    require plugin_dir_path(__FILE__) . '/v1/location/class-cities.php';
	require plugin_dir_path(__FILE__) . '/v1/location/class-barangays.php';
    require plugin_dir_path(__FILE__) . '/v1/users/class-data.php';
    
    require plugin_dir_path(__FILE__) . '/v1/settings/class-avatar.php';
    require plugin_dir_path(__FILE__) . '/v1/settings/class-update-fname.php';
    require plugin_dir_path(__FILE__) . '/v1/settings/class-update-lname.php';
    require plugin_dir_path(__FILE__) . '/v1/settings/class-update-bd.php';
    require plugin_dir_path(__FILE__) . '/v1/settings/class-update-em.php';
    require plugin_dir_path(__FILE__) . '/v1/settings/class-update-un.php';
    require plugin_dir_path(__FILE__) . '/v1/settings/class-update-gd.php';


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

            register_rest_route( 'datavice/v1/user', 'data', array(
                'methods' => 'POST',
                'callback' => array('DV_Userdata', 'listen'),
            ));
    

        /*
         * CONTACT RESTAPI
        */
            //Users
            register_rest_route( 'datavice/v1/contact/users', 'insert', array(
                'methods' => 'POST',
                'callback' => array('DV_Contact_Insert','listen'),
            ));

            register_rest_route( 'datavice/v1/contact/users', 'list/all', array(
                'methods' => 'POST',
                'callback' => array('DV_Contact_Listing','listen'),
            ));

            register_rest_route( 'datavice/v1/contact/users', 'update', array(
                'methods' => 'POST',
                'callback' => array('DV_Contact_Update','listen'),
            ));

            register_rest_route( 'datavice/v1/contact/users', 'select', array(
                'methods' => 'POST',
                'callback' => array('DV_Contact_Select','listen'),
            ));

            register_rest_route( 'datavice/v1/contact/users', 'list/type', array(
                'methods' => 'POST',
                'callback' => array('DV_Contact_Type','listen'),
            ));

            register_rest_route( 'datavice/v1/contact/users', 'delete', array(
                'methods' => 'POST',
                'callback' => array('DV_Contact_Delete','listen'),
            ));

            //Stores
            register_rest_route( 'datavice/v1/contact/stores', 'insert', array(
                'methods' => 'POST',
                'callback' => array('DV_Contact_Stores_Insert','listen'),
            ));

            register_rest_route( 'datavice/v1/contact/stores', 'list/all', array(
                'methods' => 'POST',
                'callback' => array('DV_Contact_Stores_Listing','listen'),
            ));

            register_rest_route( 'datavice/v1/contact/stores', 'update', array(
                'methods' => 'POST',
                'callback' => array('DV_Contact_Stores_Update','listen'),
            ));

            register_rest_route( 'datavice/v1/contact/stores', 'select', array(
                'methods' => 'POST',
                'callback' => array('DV_Contact_Stores_Select','listen'),
            ));

            register_rest_route( 'datavice/v1/contact/stores', 'list/type', array(
                'methods' => 'POST',
                'callback' => array('DV_Contact_Stores_Type','listen'),
            ));

            register_rest_route( 'datavice/v1/contact/stores', 'delete', array(
                'methods' => 'POST',
                'callback' => array('DV_Contact_Stores_Delete','listen'),
            ));

        /*
         * START UNKNOWN
        */

       
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

        //Lorz Route for Address REST
        register_rest_route( 'datavice/v1/location', 'ctry', array(
            'methods' => 'POST',
            'callback' => array('DV_Countries', 'listen'),
        ));  
        
        register_rest_route( 'datavice/v1/location', 'prv', array(
            'methods' => 'POST',
            'callback' => array('DV_Provinces', 'listen'),
        ));

        register_rest_route( 'datavice/v1/location', 'cty', array(
            'methods' => 'POST',
            'callback' => array('DV_Cities', 'listen'),
        ));

        register_rest_route( 'datavice/v1/location', 'brgy', array(
            'methods' => 'POST',
            'callback' => array('DV_Barangays', 'listen'),
        ));

        
        /*
         * SETTING RESTAPI
        */
            
            // register_rest_route( 'datavice/api/v1/settings', 'update_avatar', array(
            //     'methods' => 'POST',
            //     'callback' => array('DV_Avatar_update', 'listen'),
            // ));

            register_rest_route( 'datavice/v1/settings', 'update_avatar', array(
                'methods' => 'POST',
                'callback' => array('DV_Avatar_update', 'initialize'),
            ));

            register_rest_route( 'datavice/v1/settings', 'update_fname', array(
                'methods' => 'POST',
                'callback' => array('DV_Fname_update', 'listen'),
            ));

            register_rest_route( 'datavice/v1/settings', 'update_lname', array(
                'methods' => 'POST',
                'callback' => array('DV_Lname_update', 'listen'),
            ));
            
            register_rest_route( 'datavice/v1/settings', 'update_bd', array(
                'methods' => 'POST',
                'callback' => array('DV_Brithdate_update', 'listen'),
            ));
            
            register_rest_route( 'datavice/v1/settings', 'update_em', array(
                'methods' => 'POST',
                'callback' => array('DV_Email_Update', 'listen'),
            ));

            register_rest_route( 'datavice/v1/settings', 'update_gd', array(
                'methods' => 'POST',
                'callback' => array('DV_Gender_Update', 'listen'),
            ));
    
    }
    add_action( 'rest_api_init', 'datavice_route' );

?>