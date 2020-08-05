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
    class DV_Email_Update{
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
			if ( !isset($_POST["wpid"]) || !isset($_POST["snky"]) || !isset($_POST['em']) ) {
				return array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request unknown!",
                );
            }

            // Check if required fields are not empty
            if ( empty($_POST["wpid"]) || empty($_POST["snky"]) || empty($_POST['em'])  ) {
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

			 // Check if email is in valid format.
             if ( !is_email($_POST['em']) ) {
                return array(
                            "status" => "failed",
                            "message" => "Invalid email address",
                );
            }
			
			$email = $_POST['em'];
            $wpid = $_POST['wpid'];

            
            $wp_user_data = get_userdata( $wpid );

            if ($wp_user_data->user_email === $email) {
                return array(
                    "status" => "success",
                    "message" => "Data has been updated successfully.",
                );

			}else{
    			$result = wp_update_user( array( 'ID' => $wpid, 'user_email' => $email ) );

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
