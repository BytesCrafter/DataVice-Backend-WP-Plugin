
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

	class DV_Verification {

		public static function listen() {

			// STEP 1: Check if WPID and SNID is passed as this is REQUIRED!
			if (!isset($_POST["wpid"]) || !isset($_POST["snky"]) ) {
				return array(
					"status" => "unknown",
					"message" => "Please contact your administrator. Request Unknown!",
				);
			}

			return rest_ensure_response(
				self::verify(
					array(
						"wpid" => $_POST['wpid'],
						"snky" => $_POST['snky'],
					 )
				)
			);
		}

		public static function is_verified() {

			// STEP 1: Check if WPID and SNID is passed as this is REQUIRED!
			if (!isset($_POST["wpid"]) || !isset($_POST["snky"]) ) {
				return array(
					"status" => "unknown",
					"message" => "Please contact your administrator. Request Unknown!",
				);
			}

			// Catch verification result.
			 $verified = self::verify(
				 array(
					"wpid" => $_POST['wpid'],
					"snky" => $_POST['snky'],
				 )
			 );

			 // Convert verification status to bool.
			return $verified['status'] == 'success' ? true : false;

		}

		public static function is_cookie_verified() {

			// STEP 1: Check if WPID and SNID is passed as this is REQUIRED!
			if ( !DV_Globals::is_signed_with_cookie() ) {
				return false;
			}

			// Catch verification result.
			 $verified = self::verify( DV_Globals::get_cookie_signed() );

			 // Convert verification status to bool.
			return $verified['status'] == 'success' ? true : false;

		}

		public static function verify($cuser) {

			// Step 2 : Check if id or key is not empty.
            if ( empty($cuser['wpid']) || empty($cuser['snky']) ) {
                return array(
                    "status" => "failed",
                    "message" => "Required fields cannot be empty.",
                );
			}

			// Catch the Post parameters.
			$request =  array(
				'wpid' => $cuser["wpid"],
				'snky' => $cuser["snky"],
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
			return array(
				"status" => "success"
			);

		}
	}