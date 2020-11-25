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

    class DV_Select_All_Address{


		public static function listen(){
			global $wpdb;

            // Step 1: Validate user
            if ( DV_Verification::is_verified() == false ) {
                return rest_ensure_response(
                    array(
                        "status" => "unknown",
                        "message" => "Please contact your administrator. Verification Issue!",
                    )
                );
            }

            $user = $_POST['wpid'];

			$data = DV_Address_Config::get_address($user, null, 'active');

			if ($data["status"] == "failed") {
				return rest_ensure_response(
					array(
						"status" => "failed",
						"message" => $data["message"]
					)
				);
			}

			return rest_ensure_response(
				array(
					"status" => "success",
					"data" => $data["data"]
				)
			);
		}
	}