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
    class DV_Contact_Update{

        public static function listen(){

            global $wpdb;

            // Step 1: Validate user
            if ( DV_Verification::is_verified() == false ) {
                return rest_ensure_response( 
                    array(
                        "status" => "unknown",
                        "message" => "Please contact your administrator. Request Unknown!",
                    )
                );
            }

            // Step 2: Sanitize and validate all requests
            if ( !isset($_POST['value'])  || !isset($_POST['ctc']) ) {
                return rest_ensure_response( 
                    array(
                        "status" => "unknown",
                        "message" => "Please contact your administrator. Request unknown!",
                    )
                );
            }

            // Check if required fields are not empty
            if ( empty($_POST['value']) ||  empty($_POST['ctc']) ) {
                return rest_ensure_response( 
                    array(
                        "status" => "failed",
                        "message" => "Required fields cannot be empty.",
                    )
                );
            }

            // Step 3: Pass constants to variables and catch post values 
            $table_contact = DV_CONTACTS_TABLE;
            $table_revs = DV_REVS_TABLE;
            $wpid = $_POST['wpid'];
            $snky = $_POST['snky'];
            $value = $_POST['value'];
            $revs_type = 'contacts';
            $contact_id = $_POST['ctc'];
            $date_stamp = DV_Globals::date_stamp();

            // Step 4: Check if this contact exists
            $get_contact = $wpdb->get_row("SELECT * FROM `dv_contacts`  WHERE `ID` = $contact_id");
            
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

            $types = $get_contact->types;
            $prev_wpid = $get_contact->wpid;
            $prev_stid = $get_contact->stid;

            // Step 6: Start query
            $wpdb->query("START TRANSACTION ");

                $wpdb->query("UPDATE `$table_contact` SET `status`= 0 WHERE `ID` = $contact_id");

                $wpdb->query("INSERT INTO `$table_contact` (`status`, `types`, `revs`, `wpid`, `stid`, `created_by`, `date_created`) 
                                    VALUES ('1', '$types', '0', $prev_wpid, $prev_stid, $wpid, '$date_stamp');");
                
                $contact_id = $wpdb->insert_id;

                $wpdb->query("INSERT INTO `$table_revs` (revs_type, parent_id, child_key, child_val, created_by, date_created) 
                                    VALUES ( '$revs_type', $contact_id, '$types', '$value', $wpid, '$date_stamp'  )");
                
                $revs_id = $wpdb->insert_id;

                $wpdb->query("UPDATE `$table_contact` SET `revs` = $revs_id WHERE ID = $contact_id ");

            // Step 7: Check if no rows found
            if ($contact_id < 1  || $revs_id < 1) {
                //If failed, do mysql rollback (discard the insert queries(no inserted data))
                $wpdb->query("ROLLBACK");
                
                return rest_ensure_response( 
                    array(
                        "status" => "error",
                        "message" => "An error occured while submitting data to the server"
                    )
                );
            }

            //If no problems found in queries above, do mysql commit (do changes(insert rows))
            $wpdb->query("COMMIT");

            // Return a success message and complete object
            return rest_ensure_response( 
                array(
                        "status" => "Success",
                        "message" => "Data has been updated successfully",
                )
            );

            
        }
    }