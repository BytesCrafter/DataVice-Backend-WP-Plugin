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
    class DV_Insert_Store_Address{
        public static function listen(){
            global $wpdb;

             // Step1: Validate user
             if ( DV_Verification::is_verified() == false ) {
                return rest_ensure_response( 
                    array(
                        "status" => "unknown",
                        "message" => "Please contact your administrator. Request Unknown!",
                    )
                );
            }

            // Step 1 : Check if the fields are passed
            if( !isset($_POST['stid']) || !isset($_POST['wpid']) 
                    || !isset($_POST['type']) || !isset($_POST['st']) 
                    || !isset($_POST['co']) || !isset($_POST['pv']) 
                    || !isset($_POST['ct']) || !isset($_POST['bg']) ){
                return rest_ensure_response( 
                    array(
                            "status" => "unknown",
                            "message" => "Please contact your administrator. Request Unknown!",
                    )
                );
            }

             // Step 1 : Check if the fields are passed
             if( empty($_POST['stid']) || empty($_POST['wpid']) 
                    || empty($_POST['type']) || empty($_POST['st'])
                    || empty($_POST['co']) || empty($_POST['pv']) 
                    || empty($_POST['ct']) || empty($_POST['bg']) ){
                return rest_ensure_response( 
                    array(
                            "status" => "unknown",
                            "message" => "Please contact your administrator. Request Unknown!",
                    )
                );
            }

            // Step5: Check if ID is in valid format (integer)
            if( !is_numeric($_POST['stid']) || !is_numeric($_POST['wpid'])  
                || !is_numeric($_POST['co']) || !is_numeric($_POST['pv']) 
                || !is_numeric($_POST['ct']) || !is_numeric($_POST['bg']) ){
                return rest_ensure_response( 
                    array(
                            "status" => "unknown",
                            "message" => "Please contact your administrator. Request not in valid format!",
                    )
                );
            }
            

            // Step 7: Check if id(owner) of this contact exists
            if (!get_user_by("ID", $_POST['wpid'])) {
                return rest_ensure_response( 
                    array(
                        "status" => "failed",
                        "message" => "User not found",
                    )
                );
            }


            //Country input validation
                // Step 2 : Check if country passed is in integer format.
                if ( !is_numeric($_POST['co']) ) {
                    return rest_ensure_response( 
                        array(
                                "status" => "failed",
                                "message" => "Invalid value for country.",
                        )
                    );
                }

                // Step 2 : Check if country_id is in database. 
                $co_status = DV_Globals:: check_availability(DV_COUNTRY_TABLE, $_POST['co']);
                
                if ( $co_status == false ) {
                    return rest_ensure_response( 
                        array(
                                "status" => "failed",
                                "message" => "Invalid value for country.",
                        )
                    );
                }
                
                if ( $co_status === "unavail" ) {
                    return rest_ensure_response( 
                        array(
                                "status" => "failed",
                                "message" => "Not available yet in selected country",
                        )
                    );
                }
            //end of country validation

            //Province input validation
                // Step 2 : Check if province passed is in integer format.
                if ( !is_numeric($_POST['pv']) ) {
                    return rest_ensure_response( 
                        array(
                                "status" => "failed",
                                "message" => "Invalid value for province.",
                        )
                    );
                }

                // Step 2 : Check if province is in database. 
                $pv_status = DV_Globals:: check_availability(DV_PROVINCE_TABLE, $_POST['pv']);
                
                if ( $pv_status == false ) {
                    return rest_ensure_response( 
                        array(
                                "status" => "failed",
                                "message" => "Invalid value for province.",
                        )
                    );
                }
                
                if ( $pv_status === "unavail" ) {
                    return rest_ensure_response( 
                        array(
                                "status" => "failed",
                                "message" => "Not available yet in selected province",
                        )
                    );
                }
            // end of province validation

            //City input validation
                // Step 2 : Check if city passed is in integer format.
                if ( !is_numeric($_POST['ct']) ) {
                    return rest_ensure_response( 
                        array(
                                "status" => "failed",
                                "message" => "Invalid value for city.",
                        )
                    );
                }

                // Step 2 : Check if city is in database. 
                $ct_status = DV_Globals:: check_availability(DV_CITY_TABLE, $_POST['ct']);
                
                if ( $ct_status == false ) {
                    return rest_ensure_response( 
                        array(
                                "status" => "failed",
                                "message" => "Invalid value for city.",
                        )
                    );
                }
                
                if ( $ct_status === "unavail" ) {
                    return rest_ensure_response( 
                        array(
                                "status" => "failed",
                                "message" => "Not available yet in selected city",
                        )
                    );
                }
            // end of city validation

            //Barangay input validation
                // Step 2 : Check if barangay passed is in integer format.
                if ( !is_numeric($_POST['bg']) ) {
                    return rest_ensure_response( 
                        array(
                                "status" => "failed",
                                "message" => "Invalid value for barangay.",
                        )
                    );
                }

                // Step 2 : Check if barangay is in database. 
                $bg_status = DV_Globals:: check_availability(DV_BRGY_TABLE, $_POST['bg']);
                
                if ( $bg_status == false ) {
                    return rest_ensure_response( 
                        array(
                                "status" => "failed",
                                "message" => "Invalid value for barangay.",
                        )
                    );
                }
                
                if ( $bg_status === "unavail" ) {
                    return rest_ensure_response( 
                        array(
                                "status" => "failed",
                                "message" => "Not available yet in selected barangay",
                        )
                    );
                }
            // end of barangay validation
            
            // Type input validation
                // Step 2 : Check if type value is either 'home','office','business'.
                if (!($_POST['type'] === 'home')  && !($_POST['type'] === 'office') && !($_POST['type'] === 'business')) {
                    return rest_ensure_response( 
                        array(
                                "status" => "failed",
                                "message" => "Invalid value for address type.",
                        )
                    );
                }
            // end if input validation


            // Get user object.
            $user = DV_Insert_Store_Address::catch_post();
            $created_id = $user['created_by'];

            //Start for mysql transaction.
            //This is crucial in inserting data with connection with each other
            $wpdb->query("START TRANSACTION");
                
            $dv_rev_table = DV_REVS_TABLE;
                
            $date = DV_Globals:: date_stamp();

            $rev_fields = DV_INSERT_REV_FIELDS;

            $wpdb->query("INSERT INTO $dv_rev_table ($rev_fields) VALUES ('address', 'status', '1', $created_id, '$date');");
                
            $revtype = $wpdb->insert_id;

            $wpdb->query("INSERT INTO $dv_rev_table ($rev_fields) VALUES ('address', 'street', '{$user["street"]}', $created_id, '$date');");
                
            $street = $wpdb->insert_id;

            $wpdb->query("INSERT INTO $dv_rev_table ($rev_fields) VALUES ('address', 'brgy', {$user["brgy"]}, $created_id, '$date');");
                
            $brgy = $wpdb->insert_id;

            $wpdb->query("INSERT INTO $dv_rev_table ($rev_fields) VALUES ('address', 'city', {$user["city"]}, $created_id, '$date');");
                
            $city = $wpdb->insert_id;
                
            $wpdb->query("INSERT INTO $dv_rev_table ($rev_fields) VALUES ('address', 'province', {$user["province"]}, $created_id, '$date');");
                
            $province = $wpdb->insert_id;

            $wpdb->query("INSERT INTO $dv_rev_table ($rev_fields) VALUES ('address', 'country', {$user["country"]}, $created_id, '$date');");
                
            $country = $wpdb->insert_id;
                
                //Check if any of the insert queries above failed
            if ($revtype < 1 || $street < 1 || $brgy < 1 ||
               $province < 1 || $city < 1 || $country < 1 ) {

                //If failed, do mysql rollback (discard the insert queries(no inserted data))
                $wpdb->query("ROLLBACK");
                   
                return rest_ensure_response( 
                    array(
                        "status" => "error",
                        "message" => "An error occured while submitting data to the server."
                    )
                );
            }

            //If no problems found in queries above, do mysql commit (do changes(insert rows))
            $wpdb->query("COMMIT");

            $table_address = DV_ADDRESS_TABLE;

            $address_fields = DV_INSERT_ADDRESS_FIELDS;

            //Save the address in the parent table
            $wpdb->query("INSERT INTO $table_address ($address_fields) VALUES ('$revtype', '0', '{$user["store_id"]}', '{$user["type"]}', $street, $brgy, $city, $province, $country, '$date')");

            $address_id = $wpdb->insert_id;


            //Check if insert success, if not, return error message
            if ($address_id < 1) {
                return rest_ensure_response( 
                    array(
                        "status" => "failed",
                        "message" => "An error occured while submitting data to the server."
                    )
                );
            }

            //Update revision table for saving the parent_id(address_id)
            $result =  $wpdb->query("UPDATE $dv_rev_table SET `parent_id` = {$address_id} WHERE ID IN ($revtype, $street, $brgy, $city, $province, $country)");
                
            if ($result < 1) {
                return rest_ensure_response( 
                    array(
                        "status" => "failed",
                        "message" => "An error occured while submitting data to the server."
                    )
                );
            }else{
                return rest_ensure_response( 
                    array(
                        "status" => "success",
                        "message" => "Address added successfully."
                    )
                );
            }

            
        } // End of listen function

        // Return of Insert store address object from POST.
        public static function catch_post()
        {
            $cur_user = array();

            $cur_user['created_by'] = $_POST['wpid'];
            $cur_user['store_id'] = $_POST['stid'];

            $cur_user['type'] = $_POST['type'];
            $cur_user['street'] = $_POST['st'];
            $cur_user['country'] = $_POST['co'];
            $cur_user['province'] = $_POST['pv'];
            $cur_user['city'] = $_POST['ct'];
            $cur_user['brgy'] = $_POST['bg'];

            return  $cur_user;
        }
    }
