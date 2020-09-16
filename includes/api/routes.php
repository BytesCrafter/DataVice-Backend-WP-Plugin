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
    require plugin_dir_path(__FILE__) . '/v1/users/class-edit-profile.php';
    require plugin_dir_path(__FILE__) . '/v1/users/class-change-password.php';
    require plugin_dir_path(__FILE__) . '/v1/users/class-activation.php';
    require plugin_dir_path(__FILE__) . '/v1/users/class_verify_activation.php';
    require plugin_dir_path(__FILE__) . '/v1/users/class-link-account.php';
    require plugin_dir_path(__FILE__) . '/v1/users/class-delete-link-acount.php';
    // documents folder
    require plugin_dir_path(__FILE__) . '/v1/users/documents/class-insert.php';
    require plugin_dir_path(__FILE__) . '/v1/users/documents/class-listing.php';
    require plugin_dir_path(__FILE__) . '/v1/users/documents/class-approve.php';

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

    //Coordinates Classes
    require plugin_dir_path(__FILE__) . '/v1/coordinates/class-insert.php';
    require plugin_dir_path(__FILE__) . '/v1/coordinates/class-update.php';

    // Process folder
    require plugin_dir_path(__FILE__) . '/v1/process/class-upload.php';
    require plugin_dir_path(__FILE__) . '/v1/process/class-error-insert.php';

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
         * ERROR RESTAPI
        */
            register_rest_route( 'datavice/v1/process/error', 'insert', array(
                'methods' => 'POST',
                'callback' => array('DV_Error_Log_Insert', 'listen'),
            ));

        /*
         * USER RESTAPI
        */

            register_rest_route( 'datavice/v1/user/account/link', 'delete', array(
                'methods' => 'POST',
                'callback' => array('DV_Link_Account_Delete', 'listen'),
            ));

            register_rest_route( 'datavice/v1/user/account', 'link', array(
                'methods' => 'POST',
                'callback' => array('DV_Link_Account', 'listen'),
            ));

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

            register_rest_route( 'datavice/v1/user', 'edit', array(
                'methods' => 'POST',
                'callback' => array('DV_Edit_Profile', 'listen'),
            ));

            register_rest_route( 'datavice/v1/user/password', 'edit', array(
                'methods' => 'POST',
                'callback' => array('DV_Change_Password', 'listen'),
            ));

            register_rest_route( 'datavice/v1/user', 'activate', array(
                'methods' => 'POST',
                'callback' => array('DV_Activate_Account', 'listen'),
            ));

            register_rest_route( 'datavice/v1/user/activate', 'verify', array(
                'methods' => 'POST',
                'callback' => array('DV_Verify_Account', 'listen'),
            ));

            /*
               * USER DOCUMENTS REST API
            */

                register_rest_route( 'datavice/v1/user/documents', 'insert', array(
                    'methods' => 'POST',
                    'callback' => array('DV_Create_Documents', 'listen'),
                ));

                register_rest_route( 'datavice/v1/user/documents', 'list', array(
                    'methods' => 'POST',
                    'callback' => array('DV_Listing_Documents', 'listen'),
                ));

                register_rest_route( 'datavice/v1/user/documents', 'approve', array(
                    'methods' => 'POST',
                    'callback' => array('DV_Approve_docs', 'listen'),
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
         * Coordinates
        */

            register_rest_route( 'datavice/v1/coordinates', 'insert', array(
                'methods' => 'POST',
                'callback' => array('DV_Insert_Coordinates', 'listen'),
            ));

            register_rest_route( 'datavice/v1/coordinates', 'update', array(
                'methods' => 'POST',
                'callback' => array('DV_Update_Coordinates', 'listen'),
            ));

        /*
         * UPLOAD RESTAPI
        */
            register_rest_route( 'datavice/v1/process', 'upload', array(
                'methods' => 'POST',
                'callback' => array('DV_Upload', 'listen'),
            ));
    }
    add_action( 'rest_api_init', 'datavice_route' );
