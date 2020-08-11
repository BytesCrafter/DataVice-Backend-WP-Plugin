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
                        "message" => "Please contact your administrator. Verification Issue!",
                    )
                );
            }

            // Step 2 : Sanitize and validate all Request
			if ( !isset($_POST['cid']) ) {
				return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request Unknown!",
					)
                );
            }

            //Check if params passed has values
            if ( empty($_POST['cid']) ) {
				return rest_ensure_response( 
					array(
						"status" => "failed",
						"message" => "Please contact your administrator. Request Empty!",
					)
                );
            }

            // Step 3: Catching post values and passing global constants
            $table_contact = DV_CONTACTS_TABLE;
            $wpid = $_POST['wpid'];
            $contact_id = $_POST['cid'];

            // Step 4: Check if this contact exists
            $get_contact = $wpdb->get_row("SELECT `stid`, `created_by` FROM `$table_contact`  WHERE `ID` = $contact_id AND `status` = 1 AND `wpid` = $wpid");
            
            //if not found, return error
            if ( !$get_contact ) {
                return rest_ensure_response( 
                    array(
                        "status" => "failed",
                        "message" => "Contact cant be found or currently inactive.",
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
                        "message" => "Please contact your administrator. Contact not Found!"
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