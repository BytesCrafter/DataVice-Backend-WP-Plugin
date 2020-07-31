
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
	class DV_Verification {

		public static function listen() {
			return rest_ensure_response( 
				DV_Verification::verify()
			);
		}

		public static function verify() {
			
			// STEP 1: Check if WPID and SNID is passed as this is REQUIRED!
			if (!isset($_POST["wpid"]) || !isset($_POST["snky"]) ) {
				return array(
					"status" => "unknown",
					"message" => "Please contact your administrator. Request Unknown!",
				);
			}

			// Catch the Post parameters.
			$request =  array(
				'wpid' => $_POST["wpid"],
				'snky' => $_POST["snky"],
			);

			// STEP 2: Verify the Token if Valid and not expired.
			$wp_session_tokens = WP_Session_Tokens::get_instance($request['wpid']);
			if( is_null($wp_session_tokens->get( $request['snky'] )) ) {
				return array(
					"status" => "failed",
					"message" => "Please contact your administrator. Token Not Found!"
				);
			} else {
				if( time() >= $wp_session_tokens->get( $request['snky'] )['expiration'] )   {
					return array(
						"status" => "failed",
						"message" => "Please contact your administrator. Token Expired!"
					);
				}
			}

			// STEP 3 - Return a success status only. 
			return rest_ensure_response( 
				array(
					"status" => "success"
				)
			);

		}
	}

?>