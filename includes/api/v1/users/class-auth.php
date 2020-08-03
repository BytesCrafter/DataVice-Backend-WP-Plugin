
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

			/** Token Expiry Function
			 * Returns the value of token_expiry_span in the database
			 * Returns default value if not exists
			 * @param1 = {key};
			 * @param2 = {default value}
			 */
			$token_expiry = DV_Library_Config::dv_get_config('token_expiry_span', 3600);
			
			//Create a session entry unto the session tokens of user with X expiry.
			$expiration = time() + apply_filters('auth_cookie_expiration', (int)$token_expiry, $user_id, true);

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
                            "message" => "Required fields cannot be empty.",
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