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

    class DV_Select_type_Address{
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
			if ( !isset($_POST["address_type"]) ) {
				return rest_ensure_response(
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request Unknown!",
					)
                );

            }

            //Check if passed values are not null
            if ( empty($_POST["address_type"]) ) {
				return rest_ensure_response(
					array(
						"status" => "failed",
						"message" => "Please contact your administrator. Request Empty!",
					)
                );
            }

            //Check if type value is either 'home','office','business'.
			if (!($_POST['address_type'] === 'home')  && !($_POST['address_type'] === 'office') && !($_POST['address_type'] === 'business')) {
				return rest_ensure_response(
					array(
							"status" => "failed",
							"message" => "Invalid value for address type.",
					)
				);
			}

			$address_type = $_POST["address_type"];

			$result = DV_Address_Config::get_address(   null,   null,  null, null, $_POST['address_type'] );

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

