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
            return 'test';
            //Validate user
            if ( DV_Verification::is_verified() ) {
                return rest_ensure_response( 
                    array(
                        "status" => "unknown",
                        "message" => "Please contact your administrator. Request Unknown!",
                    )
                );
            }

            // Step1 : Sanitize all Request
			if ( !isset($_POST["wpid"]) || !isset($_POST["snky"]) || !isset($_POST['value']) || !isset($_POST['type']) || !isset($_POST['own']) ) {
				return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request unknown!",
					)
                );
            }

            if ( empty($_POST["wpid"]) || empty($_POST["snky"]) || empty($_POST['value']) || empty($_POST['type']) || empty($_POST['own']) ) {
				return rest_ensure_response( 
					array(
						"status" => "failed",
						"message" => "Required fields cannot be empty.",
					)
                );
            }

            // Step 2 : Check if gender value is either Male or Female only.
            if (!($_POST['type'] === 'phone') && !($_POST['type'] === 'email') && !($_POST['type'] === 'emergency')) {
                return rest_ensure_response( 
                    array(
                            "status" => "failed",
                            "message" => "Invalid value for type.",
                    )
                );
            }

            //TO DO: Please fillup appropriate error message for this one
            if (!($_POST['own'] === 'user') && !($_POST['own'] === 'store')) {
                return rest_ensure_response( 
                    array(
                            "status" => "failed",
                            "message" => "Invalid value.",
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

            //Pass constants to variables
            $table_contact = DV_CONTACTS_TABLE;
            $table_revs = DV_REVS_TABLE;

             //Catching post values
             $wpid = $_POST['wpid'];
             $snky = $_POST['snky'];
             $own = $_POST['own'];
             $revs_type = 'contacts';
             $date_stamp = DV_Globals::date_stamp();

            //Check contact type if phone, email, or emergency
            if ($_POST['type'] == 'phone') {
                $type = 'phone';
            } else if ($_POST['type'] == 'email') {
                $type = 'email';
            } else {
                $type = 'emergency';
            }

            // Check if value is email or phone number
            if (is_email($_POST['value'])) {
                $value = 'email';
            } else {
                $value = 'phone';
            }

            //Check own contact if user or store
            if ($_POST['own'] == 'user') {
                $own = 'user';
                 $wpdb->query("INSERT INTO `$table_contact` (`status`, `types`, `revs`, `wpid`, `stid`, `date_created`) VALUES ('1', '$type', '0', $wpid, 0, '$date_stamp');");

            } else {
                $own = 'store';
                 $wpdb->query("INSERT INTO `$table_contact` (`status`, `types`, `revs`, `wpid`, `stid`, `date_created`) VALUES ('1', '$type', '0', $wpid, 0, '$date_stamp');");

            }
            
            return $wpdb->insert_id();

            $wpdb->query("START TRANSACTION ");


                $contact_id = $wpdb->insert_id;

                $wpdb->query("INSERT INTO $table_revs (revs_type, parent_id, child_key, child_val, created_by, date_created) 
                                    VALUES ( '$revs_type', $parent_id, '$ck_phone', '$phone', $wpid, '$date_stamp'  )");
                $phone_id = $wpdb->insert_id;

                $query_update = $wpdb->query("UPDATE $table_contact SET phone = $phone_id , email = $email_id WHERE ID = $parent_id   ");

            //Check if any of the insert queries above failed
            if ($contact_id < 1 ) {

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



        }

    }