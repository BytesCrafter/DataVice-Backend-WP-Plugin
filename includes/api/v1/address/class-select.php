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

    class DV_Select_Address{
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


            // Step 2: Sanitize and validate all requests
			if ( !isset($_POST["address_id"]) ) {
				return rest_ensure_response(
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request Unknown!",
					)
                );

            }


            //Check if passed values are not null
            if ( empty($_POST["address_id"]) ) {
				return rest_ensure_response(
					array(
						"status" => "failed",
						"message" => "Please contact your administrator. Request Empty!",
					)
                );
            }

            //Check if ID is in valid format (integer)
			if ( !is_numeric($_POST["address_id"]) ) {
				return rest_ensure_response(
					array(
						"status" => "failed",
						"message" => "Please contact your administrator. Id not in valid format!",
					)
                );

			}

			$address_id = $_POST["address_id"];

			$result = DV_Address_Config::get_address(   null,   null,  null, $address_id, null );

			if ($result["status"] == "failed") {
                return rest_ensure_response(
                    array(
                        "status" => "failed",
                        "message" => $result["message"]
                    )
				);

            } else {

                return rest_ensure_response(
					array(
						"status" => "success",
						"data" => $result["data"]
					)
				);
            }
		}
	}
