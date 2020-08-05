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
    class DV_Brithdate_update{
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
			if ( !isset($_POST["wpid"]) || !isset($_POST["snky"]) || !isset($_POST['bd']) ) {
				return array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request unknown!",
                );
            }

            // Check if required fields are not empty
            if ( empty($_POST["wpid"]) || empty($_POST["snky"]) || empty($_POST['bd'])  ) {
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
						"message" => "No results found",
                );
			}

			// Check if birthday is in valid format (eg. 2020-08-02).
			if ( date('Y-m-d', strtotime($_POST['bd'])) !== date($_POST['bd']) ) {
                return array(
                            "status" => "failed",
                            "message" => "Invalid birthday format",
                );
            }
			
			$bday = $_POST['bd'];


			$wp_user_data = get_user_meta( $wpid,  $key = 'birthday', $single = true );

            if ($wp_user_data === $bday) {
                return array(
                    "status" => "success",
                    "message" => "Data has been updated successfully.",
                );

            }else{
				$result = update_user_meta( $_POST['wpid'], 'birthday', $bday);
				
			}

			if ($result == false) {
                return array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Update of First name Failed",
				);
				
            }else{
                return array(
						"status" => "success",
						"message" => "Data has been updated successfully",
				);
				
            }
        }
    }
