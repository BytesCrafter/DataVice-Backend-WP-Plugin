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
    class DV_Lname_update{

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
			if ( !isset($_POST["wpid"]) || !isset($_POST["snky"]) || !isset($_POST['ln']) ) {
				return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request unknown!",
					)
                );
            }

            // Check if required fields are not empty
            if ( empty($_POST["wpid"]) || empty($_POST["snky"]) || empty($_POST['ln'])  ) {
				return rest_ensure_response( 
					array(
						"status" => "failed",
						"message" => "Required fields cannot be empty.",
					)
                );
            }

            
              // Check if ID is in valid format (integer)
			if (!is_numeric($_POST["wpid"])  ) {
				return rest_ensure_response( 
					array(
						"status" => "failed",
						"message" => "Please contact your administrator. ID not in valid format!",
					)
                );
                
			}

			// Step 2: Check if id(owner) of this contact exists
			if (!get_user_by("ID", $_POST['wpid'])) {
				return rest_ensure_response( 
					array(
						"status" => "failed",
						"message" => "No results found",
					)
                );
            }


            $lname = $_POST['ln'];

            // Get last First name for validation
            $wp_user = get_user_by("ID", $_POST['wpid']);
            $last_lname = $wp_user->last_name;
            
            if ($lname === $last_lname) {
                return rest_ensure_response( 
					array(
						"status" => "failed",
						"message" => "This name is already exist",
					)
                );
            }
           
            $result = update_user_meta( $_POST['wpid'], 'first_name', $lname,  $prev_value = $last_lname );

            if ($result == false) {
                return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Update of First name Failed",
					)
                );
            }else{
                return rest_ensure_response( 
					array(
						"status" => "success",
						"message" => "Data has been updated successfully",
					)
                );
            }
        }
    }