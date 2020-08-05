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
            
            // Step 1: Validate user
            if ( DV_Verification::is_verified() == false) {
                return rest_ensure_response( 
                    array(
                        "status" => "unknown",
                        "message" => "Please contact your administrator. Request Unknown!",
                    )
                );
            }

            // Step 2: Sanitize and validate all requests
			if (!isset($_POST["wpid"]) || !isset($_POST["snky"]) || !isset($_POST['ctc']) ) {
				return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your awdawdwadministrator. Request unknown!",
					)
                );
                
            }

            //Check if id is in valid format
            if ( !is_numeric($_POST['ctc']) ||  !is_numeric($_POST['wpid']) ) {
                return rest_ensure_response( 
                    array(
                        "status" => "failed",
                        "message" => "Please contact your administrator. ID not in valid format!",
                    )
                );
                
            }  

            //Check if post passed is not null
            if (empty($_POST["wpid"]) || empty($_POST["snky"]) || empty($_POST['ctc']) ) {
				return rest_ensure_response( 
					array(
						"status" => "failed",
						"message" => "Required fields cannot be empty",
					)
                );
                
            }

            // Check if id(owner) of this contact exists
            if (!get_user_by("ID", $_POST['wpid'])) {
                return rest_ensure_response( 
                    array(
                        "status" => "failed",
                        "message" => "User not found.",
                    )
                );
            }

            // Step 3: Pass constants to variables and catch post values 
            $table_contact = DV_CONTACTS_TABLE;
            $table_revisions = DV_REVS_TABLE;

            $wpid = $_POST['wpid'];
            $contact_id = $_POST['ctc'];

            //Check if this contact exists
            $get_contact = $wpdb->get_row("SELECT created_by FROM dv_contacts  WHERE ID = $contact_id ");
            
            //Check if wpid match the created_by value
             if ( !$get_contact ) {
                return rest_ensure_response( 
                    array(
                        "status" => "error",
                        "message" => "An error occurred while fetching data to the server.",
                    )
                );
            }
            
           //Check if wpid match the created_by value
            if ($get_contact->created_by !== $wpid ) {
                return rest_ensure_response( 
                    array(
                        "status" => "error",
                        "message" => "An error occurred while fetching data to the server.",
                    )
                );
            }

            // Step 4: Start query
            $result = $wpdb->query("UPDATE `$table_contact` SET `status` = '0' WHERE ID = $contact_id ");

            // Step 5: Check if no rows found
            if ($result < 0) {
                return rest_ensure_response( 
                    array(
                        "status" => "failed",
                        "message" => "An error occurred while submitting data to the server."
                    )
                );
            }
            
            // Return a success message and complete object
            return rest_ensure_response( 
                array(
                    "status" => "success",
                    "message" => "Data has been deleted successfully."
                )
            );
        }
    }