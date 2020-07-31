
<?php
	// Exit if accessed directly
	if ( ! defined( 'ABSPATH' ) ) 
	{
		exit;
	}

	/** 
        * @package datavice-wp-plugin
        * @version 0.1.0
	*/
?>
<?php
	class DV_Authenticate {

		//Get the user session token string and if nothing, create and return one.
		public static function get_session( $user_id ) {
			//Grab WP_Session_Token from wordpress.
			$wp_session_token = WP_Session_Tokens::get_instance($user_id);

			//Create a session entry unto the session tokens of user with X expiry.
			$expiration = time() + apply_filters('auth_cookie_expiration', 30 * DAY_IN_SECONDS, $user_id, true); //
			$session_now = $wp_session_token->create($expiration);
	
			return $session_now;
		}

		// Rest Api routing.
		public static function listen() {
		
			// Check that we're trying to authenticate
			if (!isset($_POST["un"]) || !isset($_POST["pw"])) {
				return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request Unknown!",
					)
				);
			}

			//Listens for POST values.
			$username = $_POST["UN"];
			$password = $_POST["PW"];

			//Initialize wp authentication process.
			$user = wp_authenticate($username, $password);
			
			// Check for WordPress authentication issue.
			if ( is_wp_error($user) ) {
				return rest_ensure_response( 
					array(
						"status" => "error",
						"message" => $user->get_error_message(),
					)
				);
			}
	
			// Return User ID and Session KEY as success data.
			return rest_ensure_response( 
				array(
					"status" => "success",
					"data" => array(
						"wpid" => $user->ID,
						"snky" => DV_Authenticate::get_session($user->ID), 
					)
				)  
			);
		}
	}

?>