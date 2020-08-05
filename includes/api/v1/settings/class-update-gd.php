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
?>
<?php
    class DV_Gender_update{


        public static function listen(){
			global $wpdb;

            //Validate user
			if ( DV_Verification::is_verified() == false ) {
                return rest_ensure_response( 
                    array(
                        "status" => "unknown",
                        "message" => "Please contact your administrator. Request Unknown!",
                    )
                );
            }

            // Step1 : Sanitize all Request
			if ( !isset($_POST["wpid"]) || !isset($_POST["snky"]) || !isset($_POST['gd']) ) {
				return array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request unknown!",
                );
            }

            // Check if required fields are not empty
            if ( empty($_POST["wpid"]) || empty($_POST["snky"]) || empty($_POST['gd'])  ) {
				return array(
						"status" => "failed",
						"message" => "Required fields cannot be empty.",
                    );
            }

            
              // Check if ID is in valid format (integer)
			if (!is_numeric($_POST["wpid"])  ) {
				return array(
						"status" => "failed",
						"message" => "Please contact your administrator. ID not in valid format!",
					);
                
			}

			// Step 2: Check if id(owner) of this contact exists
			if (!get_user_by("ID", $_POST['wpid'])) {
				return array(
					"status" => "failed",
					"message" => "User not found",
                );
			}

			 // Check if gender value is either Male or Female only.
             if (!($_POST['gd'] === 'Male') && !($_POST['gd'] === 'Female')) {
                return array(
                    "status" => "failed",
                    "message" => "Invalid value for gender.",
                );

            }
            
			
            $gender = $_POST['gd'];
            $wpid = $_POST['wpid'];

            $wp_user_data = get_user_meta( $wpid,  $key = 'gender', $single = true );

            if ($wp_user_data === $gender) {
                return array(
                    "status" => "success",
                    "message" => "Data has been updated successfully.",
                );

            }else{
                $result = update_user_meta( $wpid, 'gender', $gender );

            }


			if ($result == false) {
                return array(
                    "status" => "failed",
                    "message" => "An error occurred while submitting data to server.",
                );

            }else{
                return array(
                    "status" => "success",
                    "message" => "Data has been updated successfully.",
                );

            }
        }
    }
