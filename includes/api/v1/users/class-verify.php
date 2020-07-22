
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
	class DVC_Verification {


		public static function through_post(){

			if (!isset($_POST["wpid"]) || !isset($_POST["snky"]) ) {
				return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Verification Unknown!",
					)
				);
			}

			return array(
				'wpid' => $_POST["wpid"],
				'snky' => $_POST["snky"],
			);
			
		}

		public static function through_get(){
			
			if (!isset($_GET["wpid"]) || !isset($_GET["snky"]) ) {
				return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Verification Unknown!!",
					)
				);
			}

			return array(
				'wpid' => $_GET["wpid"],
				'snky' => $_GET["snky"],
			);

		}


		public static function initialize() {
			
			// STEP 1: Check if WPID and SNID is passed as this is REQUIRED!

			if ($_SERVER['REQUEST_METHOD'] === 'POST') {

				$request = DVC_Verification:: through_post();
				
			} else {
			
				$request = DVC_Verification:: through_get();
			
			}

			return $request;

			// STEP 2: Verify the Token if Valid and not expired.
			// $wp_session_tokens = WP_Session_Tokens::get_instance($request['wpid']);
			// if( is_null($wp_session_tokens->get( $request['snky'] )) ) {
			// 	return rest_ensure_response( 
			// 		array(
			// 			"status" => "failed",
			// 			"message" => "Please contact your administrator. Token Not Found!"
			// 		)
			// 	);
			// } else {
			// 	if( time() >= $wp_session_tokens->get( $request['snky'] )['expiration'] )   {
			// 		return rest_ensure_response( 
			// 			array(
			// 				"status" => "failed",
			// 				"message" => "Please contact your administrator. Token Expired!"
			// 			)
			// 		);
			// 	}
			// }

			// $wp_user = get_user_by("ID", $request['wpid']);

			// if( $wp_user != false ) {
			// 	// STEP 3 - Return a success and complete object. //$wp_user->data->user_activation_key
			// 	return rest_ensure_response( 
			// 		array(
			// 			"status" => "success",
			// 			"email" => $wp_user->data->user_email,
			// 			"uname" => $wp_user->data->user_login
			// 		)
			// 	);
			// } else {
			// 	return rest_ensure_response( 
			// 		array(
			// 			"status" => "failed",
			// 			"message" => "Please contact your administrator. User Not Found!"
			// 		)
			// 	);
			// }

		}
	}

?>