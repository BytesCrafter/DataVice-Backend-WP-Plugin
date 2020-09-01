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

    class DV_Contact_Update{

        public static function listen(){

            global $wpdb;

            // Step 1: Validate user
            if ( DV_Verification::is_verified() == false ) {
                return rest_ensure_response( 
                    array(
                        "status" => "unknown",
                        "message" => "Please contact your administrator. Verification Issue!",
                    )
                );
            }

            // Step 2: Sanitize and validate all requests
            if ( !isset($_POST['val'])  || !isset($_POST['cid']) || !isset($_POST['type']) ) {
                return rest_ensure_response( 
                    array(
                        "status" => "unknown",
                        "message" => "Please contact your administrator. Request unknown!",
                    )
                );
            }

            // Check if required fields are not empty
            if ( empty($_POST['val']) ||  empty($_POST['cid']) ||  empty($_POST['type']) ) {
                return rest_ensure_response( 
                    array(
                        "status" => "failed",
                        "message" => "Please contact your administrator. Request Empty!",
                    )
                );
            }

            // Step 3: Check if passed values are standard
            if ( !($_POST['type'] === 'phone') && !($_POST['type'] === 'email') && !($_POST['type'] === 'emergency') ) {
                return rest_ensure_response( 
					array(
						"status" => "invalid",
						"message" => "Please contact your administrator. Request Invalid!",
					)
                );
            }

            // Step 3: Pass constants to variables and catch post values 
            $table_contact = DV_CONTACTS_TABLE;
            $table_revs = DV_REVS_TABLE;
            $wpid = $_POST['wpid'];
            $contact_id = $_POST['cid'];
            $value = $_POST['val'];
            $types = $_POST['type'];
            $revs_type = 'contacts';
            $date_stamp = DV_Globals::date_stamp();

            // Step 4: Check if this contact exists
            $get_contact = $wpdb->get_row("SELECT `stid`, `wpid`, `created_by` FROM `dv_contacts`  WHERE `ID` = $contact_id AND `status` = 1");
            
            //if not found, return error
            if ( !$get_contact ) {
                return rest_ensure_response( 
                    array(
                        "status" => "failed",
                        "message" => "Please contact your administrator. Contact dont Exist!",
                    )
                );
            }
            
            //Step 5: Check if wpid matches the created_by value
            if ($get_contact->created_by !== $wpid ) {
                return rest_ensure_response( 
                    array(
                        "status" => "error",
                        "message" => "Current user does not match the contact creator.",
                    )
                );
            }

            $prev_wpid = $get_contact->wpid;
            $prev_stid = $get_contact->stid;

            // Step 6: Start query
            $wpdb->query("START TRANSACTION ");
                
                $wpdb->query("INSERT INTO `$table_revs` (revs_type, parent_id, child_key, child_val, created_by, date_created) 
                                    VALUES ( '$revs_type', $contact_id, '$types', '$value', $wpid, '$date_stamp'  )");
                
                $revs_id = $wpdb->insert_id;

                $wpdb->query("UPDATE `$table_contact` SET `revs` = $revs_id WHERE ID = $contact_id ");

            // Step 7: Check if no rows found
            if ($revs_id < 1) {
                //If failed, do mysql rollback (discard the insert queries(no inserted data))
                $wpdb->query("ROLLBACK");
                
                return rest_ensure_response( 
                    array(
                        "status" => "error",
                        "message" => "Please contact your administrator. Contact not Found!"
                    )
                );
            }

            //If no problems found in queries above, do mysql commit (do changes(insert rows))
            $wpdb->query("COMMIT");

            // Return a success message and complete object
            return rest_ensure_response( 
                array(
                        "status" => "Success",
                        "message" => "Contact has been updated successfully",
                )
            );
            
        }
    }