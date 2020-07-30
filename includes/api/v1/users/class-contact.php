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
    class DV_Contact{
        
        public static function add_contact(){
            global $wpdb;
            
            if (TP_Globals::validate_user() == false) {
                return rest_ensure_response( 
                    array(
                        "status" => "unknown",
                        "message" => "Please contact your administrator. Request Unknown!",
                    )
                );
            }


            // Step1 : Sanitize all Request
			if (!isset($_POST["wpid"]) || !isset($_POST["snky"]) || !isset($_POST['phone']) || !isset($_POST['email']) || !isset($_POST['type'])) {
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


        public static function get_contacts(){
            global $wpdb;
           

            if (TP_Globals::validate_user() == false) {
                return rest_ensure_response( 
                    array(
                        "status" => "unknown",
                        "message" => "Please contact your administrator. Request Unknown!",
                    )
                );
            }


            // Step1 : Sanitize all Request
			if (!isset($_POST["wpid"]) || !isset($_POST["snky"]) ) {
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

            
            $table_contact = DV_CONTACTS_TABLE;
            $table_revs = DV_REVS_TABLE;


            $created_by = $_POST['wpid'];

            $result = $wpdb->get_results("SELECT
                revs.parent_id,
                ctc.type,
                ctc.`status`,
                max( IF ( revs.child_key = 'phone', revs.child_val, '') ) AS phones,
                max( IF ( revs.child_key = 'email', revs.child_val, '') ) AS emails,
                max( IF ( revs.child_key = 'name', revs.child_val, '') ) AS NAME,
                revs.revs_type 
            FROM
                $table_revs revs
                INNER JOIN $table_contact ctc ON ctc.`status` = revs.ID 
                OR ctc.phone = revs.ID 
                OR revs.parent_id = ctc.ID 
            WHERE
            revs.revs_type = 'contacts' 
                AND revs.created_by = $created_by
                AND ctc.created_by = $created_by 
            GROUP BY
            revs.parent_id");

            return rest_ensure_response( 
                array(
                    "status" => "success",
                    "data" => array(
                        'list' => $result, 
                    
                    )
                )
            );
        }

        public static function get_contactsByid(){
            global $wpdb;


            if (TP_Globals::validate_user() == false) {
                return rest_ensure_response( 
                    array(
                        "status" => "unknown",
                        "message" => "Please contact your administrator. Request Unknown!",
                    )
                );
            }


            // Step1 : Sanitize all Request
			if (!isset($_POST["wpid"]) || !isset($_POST["snky"])  || !isset($_POST['ctcid']) || !isset($_POST['ctctype'])) {
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

            

            $table_contact = DV_CONTACTS_TABLE;
            $table_revs = DV_REVS_TABLE;


            $created_by = $_POST['wpid'];

            $contact_id = $_POST['ctcid'];

            $key = $_POST['ctctype'];

            $result  = $wpdb->get_results("SELECT
                ctc.ID,
                ctc.type,
                ctc.`status`,
               
                revs.child_val as $key
            FROM
                $table_revs revs
                INNER JOIN $table_contact ctc ON revs.parent_id = ctc.ID 
                OR ctc.phone = revs.ID 
            WHERE
            revs.child_key = '$key' 
                AND ctc.ID = $contact_id
                AND ctc.`status` = 'active' 
                AND ctc.created_by = $created_by
                AND revs.created_by = $created_by");


            if (!$result) {
                return rest_ensure_response( 
					array(
						"status" => "failed",
						"message" => "Contact not found!.",
					)
                );
            }

            return rest_ensure_response( 
                array(
                    "status" => "success",
                    "data" => array(
                        'list' => $result, 
                    
                    )
                )
            );
            
        }
        

        public static function delete_contact(){
            global $wpdb;
            

            
            if (TP_Globals::validate_user() == false) {
                return rest_ensure_response( 
                    array(
                        "status" => "unknown",
                        "message" => "Please contact your administrator. Request Unknown!",
                    )
                );
            }


            // Step1 : Sanitize all Request
			if (!isset($_POST["wpid"]) || !isset($_POST["snky"]) || !isset($_POST["ctcid"])) {
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


            $table_contact = DV_CONTACTS_TABLE;
            


            $created_by = $_POST['wpid'];
            $contact_id = $_POST['ctcid'];

            $result = $wpdb->query("UPDATE $table_contact SET `status`='inactive' WHERE ID = $contact_id AND created_by = $created_by");


            if ($result < 0) {
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
                    "message" => "Contact set to inactive."
                )
            );
        }


        public static function update_contact(){
            global $wpdb;

            if (TP_Globals::validate_user() == false) {
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