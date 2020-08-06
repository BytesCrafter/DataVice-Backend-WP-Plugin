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

    //User Contacts
    require plugin_dir_path(__FILE__) . '/v1/contacts/users/class-insert.php';
    require plugin_dir_path(__FILE__) . '/v1/contacts/users/class-listing.php';
    require plugin_dir_path(__FILE__) . '/v1/contacts/users/class-update.php';
    require plugin_dir_path(__FILE__) . '/v1/contacts/users/class-select.php';
    require plugin_dir_path(__FILE__) . '/v1/contacts/users/class-delete.php';
    require plugin_dir_path(__FILE__) . '/v1/contacts/users/class-select-type.php';

    //Store Contacts
    require plugin_dir_path(__FILE__) . '/v1/contacts/stores/class-insert.php';
    require plugin_dir_path(__FILE__) . '/v1/contacts/stores/class-listing.php';
    require plugin_dir_path(__FILE__) . '/v1/contacts/stores/class-update.php';
    require plugin_dir_path(__FILE__) . '/v1/contacts/stores/class-select.php';
    require plugin_dir_path(__FILE__) . '/v1/contacts/stores/class-delete.php';
    require plugin_dir_path(__FILE__) . '/v1/contacts/stores/class-select-type.php';

    //Locations
    require plugin_dir_path(__FILE__) . '/v1/location/class-countries.php';
    require plugin_dir_path(__FILE__) . '/v1/location/class-provinces.php';
    require plugin_dir_path(__FILE__) . '/v1/location/class-cities.php';
    require plugin_dir_path(__FILE__) . '/v1/location/class-barangays.php';
    require plugin_dir_path(__FILE__) . '/v1/location/class-timezone.php';
	require plugin_dir_path(__FILE__) . '/v1/location/class-dst-offset.php';
	require plugin_dir_path(__FILE__) . '/v1/location/class-offset.php';
    
    
    // require plugin_dir_path(__FILE__) . '/v1/users/class-data.php';
    
    //Update Profile
    require plugin_dir_path(__FILE__) . '/v1/settings/class-update-profile.php';
    require plugin_dir_path(__FILE__) . '/v1/settings/class-notif.php';
    require plugin_dir_path(__FILE__) . '/v1/settings/class-email-notif.php';

    // require plugin_dir_path(__FILE__) . '/v1/settings/class-avatar.php';
 

    require plugin_dir_path(__FILE__) . '/v1/address/store/class-insert.php';
    require plugin_dir_path(__FILE__) . '/v1/address/store/class-update.php';
    require plugin_dir_path(__FILE__) . '/v1/address/store/class-select.php';
    require plugin_dir_path(__FILE__) . '/v1/address/store/class-select-type.php';
    require plugin_dir_path(__FILE__) . '/v1/address/store/class-listing.php';

    require plugin_dir_path(__FILE__) . '/v1/address/user/class-insert.php';
    require plugin_dir_path(__FILE__) . '/v1/address/user/class-update.php';
    require plugin_dir_path(__FILE__) . '/v1/address/user/class-select.php';
    // require plugin_dir_path(__FILE__) . '/v1/address/user/class-select-type.php';
    // require plugin_dir_path(__FILE__) . '/v1/address/user/class-listing.php';

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
         * LOCATION RESTAPI
        */
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

            register_rest_route( 'datavice/v1/location', 'tzone', array(
                'methods' => 'POST',
                'callback' => array('DV_Timezone', 'listen'),
            ));

            register_rest_route( 'datavice/v1/location', 'dst', array(
                'methods' => 'POST',
                'callback' => array('DV_Dst', 'listen'),
            ));


            register_rest_route( 'datavice/v1/location', 'off', array(
                'methods' => 'POST',
                'callback' => array('DV_Offset', 'listen'),
            ));

        
        /*
         * Start unknown
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

        

        
        /*
         * SETTING RESTAPI
        */
            
            register_rest_route( 'datavice/v1/settings', 'update_avatar', array(
                'methods' => 'POST',
                'callback' => array('DV_Avatar_update', 'initialize'),
            ));

            register_rest_route( 'datavice/v1/settings', 'update', array(
                'methods' => 'POST',
                'callback' => array('DV_Update_Profile', 'listen'),
            ));
            // Notification
            register_rest_route( 'datavice/v1/settings', 'notif', array(
                'methods' => 'POST',
                'callback' => array('DV_Notification', 'listen'),
            ));

            register_rest_route( 'datavice/v1/settings', 'email_notif', array(
                'methods' => 'POST',
                'callback' => array('DV_Notification_email', 'listen'),
            ));

        /*
         * ADDRESS RESTAPI
        */
            // Store Folder 

            register_rest_route( 'datavice/v1/address/store', 'insert', array(
                'methods' => 'POST',
                'callback' => array('DV_Insert_Store_Address', 'listen'),
            ));

            register_rest_route( 'datavice/v1/address/store', 'update', array(
                'methods' => 'POST',
                'callback' => array('DV_Update_Store_Address', 'listen'),
            ));
            
            register_rest_route( 'datavice/v1/address/store', 'select', array(
                'methods' => 'POST',
                'callback' => array('DV_Select_Store_Address', 'listen'),
            ));

            register_rest_route( 'datavice/v1/address/store', 'list/type', array(
                'methods' => 'POST',
                'callback' => array('DV_Select_type_Store_Address', 'listen'),
            ));

            register_rest_route( 'datavice/v1/address/store', 'list/all', array(
                'methods' => 'POST',
                'callback' => array('DV_Select_All_Store_Address', 'listen'),
            ));



            // User Folder

            register_rest_route( 'datavice/v1/address/user', 'insert', array(
                'methods' => 'POST',
                'callback' => array('DV_Insert_User_Address', 'listen'),
            ));

            register_rest_route( 'datavice/v1/address/user', 'update', array(
                'methods' => 'POST',
                'callback' => array('DV_Update_User_Address', 'listen'),
            ));
            
            register_rest_route( 'datavice/v1/address/user', 'select', array(
                'methods' => 'POST',
                'callback' => array('DV_Select_User_Address', 'listen'),
            ));

            // register_rest_route( 'datavice/v1/address/user', 'list/type', array(
            //     'methods' => 'POST',
            //     'callback' => array('DV_Select_type_User_Address', 'listen'),
            // ));

            // register_rest_route( 'datavice/v1/address/user', 'list/all', array(
            //     'methods' => 'POST',
            //     'callback' => array('DV_Select_All_User_Address', 'listen'),
            // ));
    
    }
    add_action( 'rest_api_init', 'datavice_route' );

?>