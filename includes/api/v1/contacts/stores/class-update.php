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
    class DV_Contact_Stores_Update{

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
            if ( !isset($_POST["wpid"]) || !isset($_POST["snky"]) || !isset($_POST['value'])  || !isset($_POST['ctc']) || !isset($_POST['stid'])) {
                return rest_ensure_response( 
                    array(
                        "status" => "unknown",
                        "message" => "Please contact your administrator. Request unknown!",
                    )
                );
            }

            // Check if required fields are not empty
            if ( empty($_POST["wpid"]) || empty($_POST["snky"]) || empty($_POST['value']) ||  empty($_POST['ctc']) || empty($_POST['stid']) ) {
                return rest_ensure_response( 
                    array(
                        "status" => "failed",
                        "message" => "Required fields cannot be empty.",
                    )
                );
            }

      
            
            // Check if ID is in valid format (integer)
            if (!is_numeric($_POST["wpid"]) || !is_numeric($_POST["ctc"]) || !is_numeric($_POST['stid']) ) {
                return rest_ensure_response( 
                    array(
                        "status" => "failed",
                        "message" => "Please contact your administrator. ID not in valid format!",
                    )
                );
                
            }

            $stid = $_POST['stid'];
            $get_contact = $wpdb->get_row("SELECT ID FROM tp_stores  WHERE ID = $stid ");
            
            //Check if store id exists
             if ( !$get_contact ) {
                return rest_ensure_response( 
                    array(
                        "status" => "error",
                        "message" => "An error occurred while fetching data to the server.",
                    )
                );
            }

            // Step 2: Check if user exists
            if (!get_user_by("ID", $_POST['wpid'])) {
                return rest_ensure_response( 
                    array(
                        "status" => "failed",
                        "message" => "No results found",
                    )
                );
            }

            //Pass constants to variables
            $table_contact = DV_CONTACTS_TABLE;
            $table_revs = DV_REVS_TABLE;

            //Catching post values
            $wpid = $_POST['wpid'];
            $snky = $_POST['snky'];
            $value = $_POST['value'];
            $revs_type = 'contacts';
            $contact_id = $_POST['ctc'];
            $date_stamp = DV_Globals::date_stamp();

            //Step 3: Start mysql transaction
            $wpdb->query("START TRANSACTION ");
                $update_contact = $wpdb->query("UPDATE `$table_contact` SET `status`= 0 WHERE `ID` = $contact_id AND `created_by` = $wpid  ");
                
                $type = $wpdb->get_row("SELECT `types` FROM `$table_contact`  WHERE ID = $contact_id ");
                $val = $type->types;
                $wpdb->query("INSERT INTO `$table_contact` (`status`, `types`, `revs`, `stid`, `created_by`, `date_created`) 
                                    VALUES ('1', '$val', '0', $stid, $wpid, '$date_stamp');");
                
                $contact_id = $wpdb->insert_id;

                $wpdb->query("INSERT INTO `$table_revs` (revs_type, parent_id, child_key, child_val, created_by, date_created) 
                                    VALUES ( '$revs_type', $contact_id, '$val', '$value', $wpid, '$date_stamp'  )");
                
                $revs_id = $wpdb->insert_id;

                $wpdb->query("UPDATE `$table_contact` SET `revs` = $revs_id WHERE ID = $contact_id ");

            //Check if any of the insert queries above failed
            if ($contact_id < 1  || $revs_id < 1 || $update_contact < 1) {
                //If failed, do mysql rollback (discard the insert queries(no inserted data))
                $wpdb->query("ROLLBACK");
                
                return rest_ensure_response( 
                    array(
                        "status" => "failed",
                        "message" => "An error occured while submitting data to the server"
                    )
                );
            }

            //If no problems found in queries above, do mysql commit (do changes(insert rows))
            $wpdb->query("COMMIT");

            return rest_ensure_response( 
                array(
                        "status" => "Success",
                        "message" => "Data has been updated successfully",
                )
            );

            
        }
    }