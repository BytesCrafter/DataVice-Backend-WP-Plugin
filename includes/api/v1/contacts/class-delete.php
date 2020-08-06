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
    class DV_Contact_Delete{

        public static function listen(){
            
            global $wpdb;

            //Step 1: Validate user 
            if ( DV_Verification::is_verified() == false) {
                return rest_ensure_response( 
                    array(
                        "status" => "unknown",
                        "message" => "Please contact your administrator. Request Unknown!",
                    )
                );
            }

            // Step 2 : Sanitize and validate all Request
			if (!isset($_POST['ctc']) ) {
				return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request unknown!",
					)
                );
                
            }

            //Check if params passed has values
            if (empty($_POST['ctc']) ) {
				return rest_ensure_response( 
					array(
						"status" => "failed",
						"message" => "Required fields cannot be empty",
					)
                );
                
            }

            // Step 3: Catching post values and passing global constants
            $table_contact = DV_CONTACTS_TABLE;
            $table_revisions = DV_REVS_TABLE;
            $wpid = $_POST['wpid'];
            $contact_id = $_POST['ctc'];

            // Step 4: Check if this contact exists
            $get_contact = $wpdb->get_row("SELECT `created_by` FROM `dv_contacts`  WHERE `ID` = $contact_id");
            
            //if not found, return error
            if ( !$get_contact ) {
                return rest_ensure_response( 
                    array(
                        "status" => "failed",
                        "message" => "This contact id does not exists",
                    )
                );
            }
            
            //Step 5: Check if wpid matches the created_by value
            if ($get_contact->created_by !== $wpid ) {
                return rest_ensure_response( 
                    array(
                        "status" => "error",
                        "message" => "Current user does not match the contact creator",
                    )
                );
            }

            //Step 6: Update query
            $result = $wpdb->query("UPDATE `$table_contact` SET `status` = '0' WHERE ID = $contact_id AND created_by = $wpid ");

            //Step 7: if no rows updated
            if ($result < 0) {
                return rest_ensure_response( 
                    array(
                        "status" => "error",
                        "message" => "An error occured while submitting data to the server."
                    )
                );
            }

            //If success, return success message
            return rest_ensure_response( 
                array(
                    "status" => "success",
                    "message" => "Data has been deleted successfully."
                )
            );
        }
    }