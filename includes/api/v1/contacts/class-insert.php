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
    class DV_Contact_Insert{
        
        public static function listen() {
            
            global $wpdb;

            //Step 1: Validate user
            if ( DV_Verification::is_verified() == false ) {
                return rest_ensure_response( 
                    array(
                        "status" => "unknown",
                        "message" => "Please contact your administrator. Request Unknown!",
                    )
                );
            }

            //Step 2: Sanitize and validate all Request
			if ( !isset($_POST["own"]) || !isset($_POST['value']) || !isset($_POST['type']) || !isset($_POST['id'])) {
				return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request unknown!",
					)
                );
            }

            // Check if required fields are not empty
            if ( empty($_POST["own"]) || empty($_POST['value']) || empty($_POST['type']) || empty($_POST['id']) ) {
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
                            "message" => "Invalid contact type",
                    )
                );
            }

            // Check if owner type is either user or store only.
            if (!($_POST['own'] === 'user') && !($_POST['own'] === 'store')) {
                return rest_ensure_response( 
                    array(
						"status" => "unknown",
						"message" => "Invalid owner type",
					)
                );
            }
            
            //Check contact type if phone, email, or emergency
            if ($_POST['type'] == 'phone') {
                $type = 'phone';
            } else if ($_POST['type'] == 'email') {
                $type = 'email';
                //if type is email, make sure to sanitize if its a valid email format
                if (!is_email($_POST['value'])) {
                    return rest_ensure_response( 
                        array(
                            "status" => "failed",
                            "message" => "Email not in valid format."
                        )
                    );
                }
            } else {
                $type = 'emergency';
            }


            //Step 3: Pass constants to variables and catching post values
             $table_contact = DV_CONTACTS_TABLE;
             $table_revs = DV_REVS_TABLE;
             $wpid = $_POST['wpid'];
             $id = $_POST['id'];
             $value = $_POST['value'];
             $revs_type = 'contacts';
             $owner_type = $_POST['own'];
             $date_stamp = DV_Globals::date_stamp();

             

             //Step 4: Start mysql transaction
            if ($owner_type == 'user') {
                
                $wpdb->query("START TRANSACTION ");

                $wpdb->query("INSERT INTO `$table_contact` (`status`, `types`, `revs`, `wpid`, `created_by`, `date_created`) 
                                VALUES ('1', '$type', '0', $id, $wpid, '$date_stamp');");
                
                $contact_id = $wpdb->insert_id;

                $wpdb->query("INSERT INTO `$table_revs` (revs_type, parent_id, child_key, child_val, created_by, date_created) 
                                    VALUES ( '$revs_type', $contact_id, '$type', '$value', $wpid, '$date_stamp'  )");
                
                $revs_id = $wpdb->insert_id;

                $wpdb->query("UPDATE `$table_contact` SET `revs` = $revs_id WHERE ID = $contact_id ");
                
            } else {

                $store_id = $id;

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
                if ( !in_array('can_insert_contact' , $access, true) && (DV_Globals::check_roles('contributor') == false  && DV_Globals::check_roles('administrator') == false) ) {
                    return rest_ensure_response( 
                        array(
                            "status" => "failed",
                            "message" => "Current user has no access in inserting contacts",
                        )
                    );
                }

                $wpdb->query("START TRANSACTION ");

                $wpdb->query("INSERT INTO `$table_contact` (`status`, `types`, `revs`, `stid`, `created_by`, `date_created`) 
                                VALUES ('1', '$type', '0', $store_id, $wpid, '$date_stamp');");
                
                $contact_id = $wpdb->insert_id;

                $wpdb->query("INSERT INTO `$table_revs` (revs_type, parent_id, child_key, child_val, created_by, date_created) 
                                    VALUES ( '$revs_type', $contact_id, '$type', '$value', $wpid, '$date_stamp'  )");
                
                $revs_id = $wpdb->insert_id;

                $wpdb->query("UPDATE `$table_contact` SET `revs` = $revs_id WHERE ID = $contact_id ");

            }
           
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
                        "message" => "Data has been added successfully.",
                )
            );

        }

    }