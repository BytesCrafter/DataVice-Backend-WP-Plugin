
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

			// TODO: Put this on config (30). value is int in days. 'token_expiry_span'
            // TODO: We get the value by a global function named, dv_get_config('key') which return value.
            // TODO: We set the value by a global function named, dv_get_config('key', {value}) which bool.
			//Create a session entry unto the session tokens of user with X expiry.
			$expiration = time() + apply_filters('auth_cookie_expiration', 30 * DAY_IN_SECONDS, $user_id, true);

			// TODO: PENDING! Consider not inserting new session on database. For example, we must check if
			// there is a session with the same device id as we currently use then reused that session.
			// Current issue is, everytime user authenticate, we also create new session for that.
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

			// Step 2 : Check if username or password is not empty.
            if ( empty($_POST['un']) || empty($_POST['pw']) ) {
                return rest_ensure_response( 
                    array(
                            "status" => "failed",
                            "message" => "Required field cannot be empty.",
                    )
                );
			}
			
			// Store post variable into vars.
			$uname = $_POST["un"];
			$pword = $_POST["pw"];

			//Initialize wp authentication process.
			$user = wp_authenticate($uname, $pword);
			
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