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

            if ( DV_Verification::is_verified() ) {
                return rest_ensure_response( 
                    array(
                        "status" => "unknown",
                        "message" => "Please contact your administrator. Request Unknown!",
                    )
                );
            }


            // Step1 : Sanitize all Request
			if (!isset($_POST["wpid"]) || !isset($_POST["snky"]) || !isset($_POST['phone']) || !isset($_POST['email']) || !isset($_POST['ctcid']) ) {
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

            $created_by = $_POST['wpid'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $contact_id = $_POST['ctcid'];
            $date_stamp = DV_Globals::date_stamp();
            $ck_phone = 'phone';
            $ck_email = 'email';
            $ck_ec_name = 'name';
            $revs_type = 'contacts';

            $table_contact = DV_CONTACTS_TABLE;
            $table_revs = DV_REVS_TABLE;

            if(!isset($_POST['ec_name'])){

                $wpdb->query("START TRANSACTION");
                    $insert_phone = $wpdb->query("INSERT INTO $table_revs 
                                            (revs_type, parent_id, child_key, child_val, created_by, date_created) VALUES 
                                            ('$revs_type', $contact_id, '$ck_phone', '$phone',$created_by, '$date_stamp' )");
                    $insert_ph = $wpdb->insert_id;
                    $insert_email = $wpdb->query("INSERT INTO $table_revs 
                                            (revs_type, parent_id, child_key, child_val, created_by, date_created) VALUES 
                                            ('$revs_type', $contact_id, '$ck_email', '$email',$created_by, '$date_stamp' )");
                    $insert_eml = $wpdb->insert_id;
                    
                    $update_contact = $wpdb->query("UPDATE $table_contact SET phone = $insert_ph, email = $insert_eml WHERE ID = $contact_id");

               $wpdb->query("COMMIT");

               if ($insert_phone < 0 || $insert_email < 0 || $update_contact < 0 ) {
                    return rest_ensure_response( 
                        array(
                            "status" => "unknown",
                            "message" => "Please Contact your Administrator. Contact Deletion Failed!"
                        )
                    );
                }
                return rest_ensure_response( 
                    array(
                        "status" => "success",
                        "message" => "Contact updated successfully"
                    )
                );

            }else{

                if (!isset($_POST['ec_name'])) {
                    return rest_ensure_response( 
                        array(
                            "status" => "unknown",
                            "message" => "Please contact your administrator. Missing Variables",
                        )
                    );
                }
                $name = $_POST['ec_name'];

                $wpdb->query("START TRANSACTION");
                    $insert_phone = $wpdb->query("INSERT INTO $table_revs 
                                            (revs_type, parent_id, child_key, child_val, created_by, date_created) VALUES 
                                            ('$revs_type', $contact_id, '$ck_phone', '$phone',$created_by, '$date_stamp' )");
                    $insert_ph = $wpdb->insert_id;
                    $insert_email = $wpdb->query("INSERT INTO $table_revs 
                                            (revs_type, parent_id, child_key, child_val, created_by, date_created) VALUES 
                                            ('$revs_type', $contact_id, '$ck_email', '$email',$created_by, '$date_stamp' )");
                    $insert_eml = $wpdb->insert_id;

                    $insert_name = $wpdb->query("INSERT INTO $table_revs 
                                            (revs_type, parent_id, child_key, child_val, created_by, date_created) VALUES 
                                            ('$revs_type', $contact_id, '$ck_ec_name', '$email',$created_by, '$date_stamp' )");
                    
                    $update_contact = $wpdb->query("UPDATE $table_contact SET phone = $insert_ph, email = $insert_eml WHERE ID = $contact_id");

                $wpdb->query("COMMIT");

                if ($insert_phone < 0 || $insert_email < 0 || $update_contact < 0 || $insert_name < 0) {
                    return rest_ensure_response( 
                        array(
                            "status" => "unknown",
                            "message" => "Please Contact your Administrator. Contact Deletion Failed!"
                        )
                    );
                }
                return rest_ensure_response( 
                    array(
                        "status" => "success",
                        "message" => "Contact updated successfully"
                    )
                );


            }

        }
    }