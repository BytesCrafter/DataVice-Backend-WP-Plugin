<?php
	// Exit if accessed directly
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	/**
	 * @package bytescrafter-datavice
	*/
?>

<?php

    // This is sample / demo callback.
    function demo_callback() {
        return $_POST['key1'] . $_POST['key2'];
        //return 'Hello World!';
    }

    function auth_callback() {

        global $wp_json_basic_auth_error;
        $wp_json_basic_auth_error = null;

        //Listens for POST values.
        $username = $_POST["UN"];
        $password = $_POST["PW"];

        // Check that we're trying to authenticate
        if (!isset($username) || !isset($password)) {
            $user = ['code' => 'unknown_request', 'message' => 'Please contact your administrator.', 'data' => null];
            return rest_ensure_response( $user );
        }

        //Initialize wp authentication process.
        $user = wp_authenticate($username, $password);
        
        //Check for wp authentication issue.
        if ( is_wp_error($user) ) {
            $wp_json_basic_auth_error = $user;
            return rest_ensure_response( $user );
        }

        $wp_json_basic_auth_error = true;

        return rest_ensure_response( $user );
    }

    // Init check if DataVice successfully request from wapi.
    function bytescrafter_datavice_route()
    {
        register_rest_route( 'datavice/v1', 'demo', array(
            'methods' => 'POST',
            'callback' => 'demo_callback',
        ));

        register_rest_route( 'datavice/v1', 'auth', array(
            'methods' => 'POST',
            'callback' => 'auth_callback',
        ));
    }
    add_action( 'rest_api_init', 'bytescrafter_datavice_route' );

?>