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
            if ( !isset($_POST['value'])  || !isset($_POST['ctc']) || !isset($_POST['type'])) {
                return rest_ensure_response( 
                    array(
                        "status" => "unknown",
                        "message" => "Please contact your administrator. Request unknown!",
                    )
                );
            }

            // Check if required fields are not empty
            if ( empty($_POST['value']) ||  empty($_POST['ctc']) || empty($_POST['ctc']) ) {
                return rest_ensure_response( 
                    array(
                        "status" => "failed",
                        "message" => "Required fields cannot be empty.",
                    )
                );
            }

            if ( !($_POST['type'] === 'phone') && !($_POST['type'] === 'email') && !($_POST['type'] === 'emergency') ) {
                return rest_ensure_response( 
                    array(
						"status" => "unknown",
						"message" => "Invalid contact type",
                    )
                );
            }
              

            // Step 3: Pass constants to variables and catch post values 
            $table_contact = DV_CONTACTS_TABLE;
            $table_revs = DV_REVS_TABLE;
            $wpid = $_POST['wpid'];
            $snky = $_POST['snky'];
            $value = $_POST['value'];
            $types = $_POST['type'];
            $revs_type = 'contacts';
            $contact_id = $_POST['ctc'];
            $date_stamp = DV_Globals::date_stamp();

            // Step 4: Check if this contact exists
            $get_contact = $wpdb->get_row("SELECT `created_by`, `stid`, `wpid` FROM `dv_contacts`  WHERE `ID` = $contact_id AND `status` = 1");
            
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

            //This means that the contact found is store contact
            if ($get_contact->stid !== 0) { 
                
                $store_id = $get_contact->stid;

                //Check if personnel is part of the store
                $personnels = $wpdb->get_row("SELECT `wpid`, `roid`
                    FROM `tp_personnels` 
                    WHERE `stid` = $store_id
                    AND `wpid` = $wpid");

                //Check if current user is one of the personnels or one of our staff
                if (!$personnels || (DV_Globals::check_roles('contributor') == false  && DV_Globals::check_roles('administrator') == false) ) {
                    return rest_ensure_response( 
                        array(
                            "status" => "failed",
                            "message" => "User not associated with this store",
                        )
                    );
                }

                $role_id = $personnels->roid;

                //Get all access from that role_id 
                $get_access = $wpdb->get_results("SELECT rm.access
                    FROM `tp_roles` r 
                        LEFT JOIN tp_roles_meta rm ON rm.roid = r.ID
                    WHERE r.id = $role_id");
                
                $access = array_column($get_access, 'access');

                //Check if user has role access of `can_delete_contact` or one of our staff
                if ( !in_array('can_delete_contact' , $access, true) && (DV_Globals::check_roles('contributor') == false  && DV_Globals::check_roles('administrator') == false) ) {
                    return rest_ensure_response( 
                        array(
                            "status" => "failed",
                            "message" => "Current user has no access in updating contacts",
                        )
                    );
                }

            }

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