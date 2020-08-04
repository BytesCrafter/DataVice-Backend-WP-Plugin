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
    class DV_Contact_Stores_Delete{

        public static function listen(){
            
            global $wpdb;

            if ( DV_Verification::is_verified() ) {
                return rest_ensure_response( 
                    array(
                        "status" => "unknown",
                        "message" => "Please contact your administrator. Request Unknown!",
                    )
                );
            }


            // Step1 : Sanitize all Request
			if (!isset($_POST["wpid"]) || !isset($_POST["snky"]) || !isset($_POST["ctcid"])) {
				return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request unknown!",
					)
                );
                
            }
            
              // Step 2: Check if ID is in valid format (integer)
			if (!is_numeric($_POST["wpid"]) ) {
				return rest_ensure_response( 
					array(
						"status" => "failed",
						"message" => "Please contact your administrator. ID not in valid format!",
					)
                );
                
			}

			// Step 3: Check if ID exists
			if (!get_user_by("ID", $_POST['wpid'])) {
				return rest_ensure_response( 
					array(
						"status" => "failed",
						"message" => "User not found!",
					)
                );
                
            }


            $table_contact = DV_CONTACTS_TABLE;
            


            $created_by = $_POST['wpid'];
            $contact_id = $_POST['ctcid'];

            $result = $wpdb->query("UPDATE $table_contact SET `status`='inactive' WHERE ID = $contact_id AND created_by = $created_by");


            if ($result < 0) {
                return rest_ensure_response( 
                    array(
                        "status" => "unknown",
                        "message" => "Please Contact your Administrator. Contact Deletion Failed!"
                    )
                );
            }
            return rest_ensure_response( 
                array(
                    "status" => "success",
                    "message" => "Contact set to inactive."
                )
            );
        }
    }