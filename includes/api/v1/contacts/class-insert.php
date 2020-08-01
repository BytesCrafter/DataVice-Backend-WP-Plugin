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
            
            if ( DV_Verification::is_verified() ) {
                return rest_ensure_response( 
                    array(
                        "status" => "unknown",
                        "message" => "Please contact your administrator. Request Unknown!",
                    )
                );
            }

            // Step1 : Sanitize all Request
			if ( !isset($_POST["wpid"]) || !isset($_POST["snky"]) || !isset($_POST['phone']) || !isset($_POST['email']) || !isset($_POST['type']) ) {
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

            

            $wpid = $_POST['wpid'];
            $snky = $_POST['snky'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];
            $contact_type = $_POST['type'];
            $revs_type = 'contacts';
            $date_stamp = DV_Globals::date_stamp();
            // child keys
            $ck_phone = 'phone';
            $ck_email = 'email';
            
            
            $table_contact = DV_CONTACTS_TABLE;
            $table_revs = DV_REVS_TABLE;

            if(!isset($_POST['ec_name']) ){

                if ($_POST['type'] != "personal") {
                    return rest_ensure_response( 
                        array(
                                "status" => "unknown",
                                "message" => "Please contact your Administrator. Parameters Unknown.",
                        )
                    );
                }
                
                $wpdb->query("START TRANSACTION ");

                    $query_contact = $wpdb->query("INSERT INTO $table_contact (`type`, created_by, date_created  ) VALUES ('$contact_type', $wpid, '$date_stamp')");
                        $parent_id = $wpdb->insert_id;
                    $query_phone = $wpdb->query("INSERT INTO $table_revs (revs_type, parent_id, child_key, child_val, created_by, date_created) 
                                                            VALUES ( '$revs_type', $parent_id, '$ck_phone', '$phone', $wpid, '$date_stamp'  )");
                        $phone_id = $wpdb->insert_id;
                    $query_email = $wpdb->query("INSERT INTO $table_revs (revs_type, parent_id, child_key, child_val, created_by, date_created) 
                                                            VALUES ( '$revs_type', $parent_id, '$ck_email', '$email', $wpid, '$date_stamp'  )");
                        $email_id = $wpdb->insert_id;
                  
                    $query_update = $wpdb->query("UPDATE $table_contact SET phone = $phone_id , email = $email_id WHERE ID = $parent_id   ");

                $wpdb->query("COMMIT");

                if($query_contact < 0 || $query_phone < 0 || $query_email < 0 ||  $query_update < 0 ){
                    return rest_ensure_response( 
                        array(
                                "status" => "failed",
                                "message" => "Please contact your Administrator. Submition failed",
                        )
                    );
                }

                return rest_ensure_response( 
                    array(
                            "status" => "Success",
                            "message" => "Contact Created Successfully!.",
                    )
                );

            }else{
                if (!isset($_POST['ec_name'])) {
                    return rest_ensure_response( 
                        array(
                                "status" => "unknown",
                                "message" => "Please contact your Administrator. Missing Variables.",
                        )
                    ); 
                }

                if ($_POST['type'] != "emergency") {
                    return rest_ensure_response( 
                        array(
                                "status" => "unknown",
                                "message" => "Please contact your Administrator. Parameters Unknown.",
                        )
                    );
                }
                
                $ec_name = $_POST['ec_name'];
                $ck_emergency_name = 'name';

                
                $wpdb->query("START TRANSACTION ");

                    $query_contact = $wpdb->query("INSERT INTO $table_contact (`type`, created_by, date_created  ) VALUES ('$contact_type', $wpid, '$date_stamp')");
                        $parent_id = $wpdb->insert_id;
                    $query_phone = $wpdb->query("INSERT INTO $table_revs (revs_type, parent_id, child_key, child_val, created_by, date_created) 
                                                            VALUES ( '$revs_type', $parent_id, '$ck_phone', '$phone', $wpid, '$date_stamp'  )");
                        $phone_id = $wpdb->insert_id;
                    $query_email = $wpdb->query("INSERT INTO $table_revs (revs_type, parent_id, child_key, child_val, created_by, date_created) 
                                                            VALUES ( '$revs_type', $parent_id, '$ck_email', '$email', $wpid, '$date_stamp'  )");
                        $email_id = $wpdb->insert_id;
                    $query_ec_name = $wpdb->query("INSERT INTO $table_revs (revs_type, parent_id, child_key, child_val, created_by, date_created) 
                                                            VALUES ( '$revs_type', $parent_id, '$ck_emergency_name', '$ec_name', $wpid, '$date_stamp'  )");
                    $query_update = $wpdb->query("UPDATE $table_contact SET phone = $phone_id , email = $email_id WHERE ID = $parent_id   ");

                $wpdb->query("COMMIT");

                if($query_contact < 0 || $query_phone < 0 || $query_email < 0 || $query_ec_name < 0 || $query_update < 0 ){
                    return rest_ensure_response( 
                        array(
                                "status" => "failed",
                                "message" => "Please contact your Administrator. Submition failed",
                        )
                    );
                }
                return rest_ensure_response( 
                    array(
                            "status" => "Success",
                            "message" => "Contact Created Successfully!.",
                    )
                );
                
            }

        }

    }