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

    class DV_Contact_Insert{
        
        public static function listen() {
            global $wpdb;

            //Step 1: Validate user
            if ( DV_Verification::is_verified() == false ) {
                return rest_ensure_response( 
                    array(
                        "status" => "unknown",
                        "message" => "Please contact your administrator. Verification Issue!",
                    )
                );
            }

            //Step 2: Sanitize and validate all Request
			if ( !isset($_POST['value']) || !isset($_POST['type']) ) {
				return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request Unknown!",
					)
                );
            }

            // Check if required fields are not empty
            if ( empty($_POST['value']) || empty($_POST['type']) ) {
				return rest_ensure_response( 
					array(
						"status" => "failed",
						"message" => "Required fields cannot be empty.",
					)
                );
            }

             // Check if value of type is valid
             if (!($_POST['type'] === 'phone') && !($_POST['type'] === 'email') && !($_POST['type'] === 'emergency')) {
                return rest_ensure_response( 
                    array(
                            "status" => "failed",
                            "message" => "Contact type submitted is unknown.",
                    )
                );
            }

            //if type is email, make sure to sanitize if its a valid email format
            if($_POST['type'] === 'email') {
                if (!is_email($_POST['value'])) {
                    return rest_ensure_response( 
                        array(
                            "status" => "failed",
                            "message" => "Email not in valid format."
                        )
                    );
                }
            }

            //Step 3: Pass constants to variables and catching post values
            $table_contact = DV_CONTACTS_TABLE;
            $table_revs = DV_REVS_TABLE;

            $type = $_POST['type'];
            $wpid = $_POST['wpid'];
            $value = $_POST['value'];
            
            $date_stamp = DV_Globals::date_stamp();

            $wpdb->query("START TRANSACTION ");

            $wpdb->query("INSERT INTO `$table_contact` (`status`, `wpid`, `types`, `revs`, `created_by`, `date_created`) 
                            VALUES ('1', $wpid, '$type', '0', $wpid, '$date_stamp');");
            
            $contact_id = $wpdb->insert_id;

            $wpdb->query("INSERT INTO `$table_revs` (revs_type, parent_id, child_key, child_val, created_by, date_created) 
                                VALUES ( 'contacts', $contact_id, '$type', '$value', $wpid, '$date_stamp'  )");
            
            $revs_id = $wpdb->insert_id;

            $wpdb->query("UPDATE `$table_contact` SET `revs` = $revs_id WHERE ID = $contact_id ");

            //Step 6: Check if any of the insert queries above failed
            if ($contact_id < 1  || $revs_id < 1) {
                //If failed, do mysql rollback (discard the insert queries(no inserted data))
                $wpdb->query("ROLLBACK");
                
                return rest_ensure_response( 
                    array(
                        "status" => "error",
                        "message" => "An error occured while submitting data to the server."
                    )
                );
            }

            //Step 7: If no problems found in queries above, do mysql commit (do changes(insert rows))
            $wpdb->query("COMMIT");

            return rest_ensure_response( 
                array(
                        "status" => "success",
                        "message" => "Contacts had been added successfully.",
                )
            );
        }

    }