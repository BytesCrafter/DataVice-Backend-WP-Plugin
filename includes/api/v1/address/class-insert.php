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

    class DV_Insert_Address {

        public static function listen(WP_REST_Request $request){
            global $wpdb;


             // Step1: Validate user
             if ( DV_Verification::is_verified() == false ) {
                return rest_ensure_response(
                    array(
                        "status" => "unknown",
                        "message" => "Please contact your administrator. Verification Issue!",
                    )
                );
            }

            // Step 1 : Check if the fields are passed
            if( !isset($_POST['type']) || !isset($_POST['co']) || !isset($_POST['pv']) || !isset($_POST['ct']) || !isset($_POST['bg']) || !isset($_POST['st']) ||  !isset($_POST['cnt'])   || !isset($_POST['cnt_type']) ){
                return rest_ensure_response(
                    array(
                            "status" => "unknown",
                            "message" => "Please contact your administrator. Request unknown!",
                    )
                );
            }

             // Step 1 : Check if the fields are passed
             if( empty($_POST['type']) || empty($_POST['co']) || empty($_POST['pv']) || empty($_POST['ct']) || empty($_POST['bg']) || empty($_POST['st']) ||  empty($_POST['cnt'])  || empty($_POST['cnt_type']) ){
                return rest_ensure_response(
                    array(
                        "status" => "unknown",
                        "message" => "Please contact your administrator. Request Empty!",
                    )
                );
            }

            // Step5: Check if ID is in valid format (integer)
            if( !is_numeric($_POST['pv']) || !is_numeric($_POST['ct']) || !is_numeric($_POST['bg']) ){
                return rest_ensure_response(
                    array(
                        "status" => "unknown",
                        "message" => "Please contact your administrator. Request not in valid format!",
                    )
                );
            }

            //Country input validation
                // Step 2 : Check if country passed is in integer format.
                $co_status = DV_Globals:: check_availability(DV_COUNTRY_TABLE, " WHERE country_code ='".$_POST['co']."'", true);

                if ( $co_status === false ) {
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
                $pv_status = DV_Globals:: check_availability(DV_PROVINCE_TABLE, " WHERE prov_code ='".$_POST['pv']."'");

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
                $ct_status = DV_Globals:: check_availability(DV_CITY_TABLE, " WHERE city_code ='".$_POST['ct']."'");

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
                $bg_status = DV_Globals:: check_availability(DV_BRGY_TABLE, " WHERE ID ='".$_POST['bg']."'");

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
            $user = DV_Insert_Address::catch_post();
            $created_id = $user['created_by'];

            $dv_rev_table = DV_REVS_TABLE;
            $rev_fields = DV_INSERT_REV_FIELDS;
            $table_address = DV_ADDRESS_TABLE;
            $address_fields = DV_INSERT_ADDRESS_FIELDS;
            $table_contact = DV_CONTACTS_TABLE;
            $table_revs = DV_REVS_TABLE;


            $date = DV_Globals:: date_stamp();

            //Start for mysql transaction.
            //This is crucial in inserting data with connection with each other
            $wpdb->query("START TRANSACTION");

            $latitude = "";
            $longitude = "";
            $place_id = "";
            $formatted_address = "";

            $data = array(
                DV_BRGY_TABLE => $_POST['bg'],
                DV_CITY_TABLE => $_POST['ct'],
                DV_PROVINCE_TABLE => $_POST['pv'],
                DV_COUNTRY_TABLE => $_POST['co']
            );

            $address = DV_Globals::get_formated_address($_POST['st'], $data);

            $geolocation = DV_Globals::get_location($address, "AIzaSyDeR29pTg1D5Exga3rQd8a3XKL3XtukQQg");

            // Get Geolocation of Address
                if (isset($_POST['lat']) && isset($_POST['long'])) {

                    if ($_POST['lat'] != null && $_POST['long'] != null) {

                        if($geolocation == null){
                            return array(
                                "status" => "failed",
                                "message" => "Please contact your administrator. Geolocation Api Failed"
                            );
                        }else{
                            $wpdb->query("INSERT INTO $dv_rev_table ($rev_fields) VALUES ('address', 'latitude', '{$user["lat"]}', $created_id, '$date');");
                            $address_latitude = $wpdb->insert_id;

                            $wpdb->query("INSERT INTO $dv_rev_table ($rev_fields) VALUES ('address', 'longitude', '{$user["long"]}', $created_id, '$date');");
                            $address_longitude = $wpdb->insert_id;

                            $wpdb->query("INSERT INTO $dv_rev_table ($rev_fields) VALUES ('address', 'place_id', '{$geolocation["place_id"]}', $created_id, '$date');");
                            $address_place_id = $wpdb->insert_id;

                            $wpdb->query("INSERT INTO $dv_rev_table ($rev_fields) VALUES ('address', 'formatted_address', '{$geolocation["formatted_address"]}', $created_id, '$date');");
                            $address_formated_address = $wpdb->insert_id;
                        }
                    }else{

                        if($geolocation == null){

                            return array(
                                "status" => "failed",
                                "message" => "Please contact your administrator. Geolocation Api Failed"
                            );
                        }

                        $wpdb->query("INSERT INTO $dv_rev_table ($rev_fields) VALUES ('address', 'latitude', '{$geolocation["latitude"]}', $created_id, '$date');");
                        $address_latitude = $wpdb->insert_id;

                        $wpdb->query("INSERT INTO $dv_rev_table ($rev_fields) VALUES ('address', 'longitude', '{$geolocation["longitude"]}', $created_id, '$date');");
                        $address_longitude = $wpdb->insert_id;

                        $wpdb->query("INSERT INTO $dv_rev_table ($rev_fields) VALUES ('address', 'place_id', '{$geolocation["place_id"]}', $created_id, '$date');");
                        $address_place_id = $wpdb->insert_id;

                        $wpdb->query("INSERT INTO $dv_rev_table ($rev_fields) VALUES ('address', 'formatted_address', '{$geolocation["formatted_address"]}', $created_id, '$date');");
                        $address_formated_address = $wpdb->insert_id;

                    }

                }
            // End

            $wpdb->query("INSERT INTO $dv_rev_table ($rev_fields) VALUES ('address', 'status', '1', $created_id, '$date');");

            $status = $wpdb->insert_id;

            $wpdb->query("INSERT INTO $dv_rev_table ($rev_fields) VALUES ('address', 'street', '{$user["street"]}', $created_id, '$date');");

            $street = $wpdb->insert_id;

            $wpdb->query("INSERT INTO $dv_rev_table ($rev_fields) VALUES ('address', 'brgy', '{$user["brgy"]}', $created_id, '$date');");

            $brgy = $wpdb->insert_id;

            $wpdb->query("INSERT INTO $dv_rev_table ($rev_fields) VALUES ('address', 'city', '{$user["city"]}', $created_id, '$date');");

            $city = $wpdb->insert_id;

            $wpdb->query("INSERT INTO $dv_rev_table ($rev_fields) VALUES ('address', 'province', '{$user["province"]}', $created_id, '$date');");

            $province = $wpdb->insert_id;

            $wpdb->query("INSERT INTO $dv_rev_table ($rev_fields) VALUES ('address', 'country', '$co_status', $created_id, '$date');");

            $country = $wpdb->insert_id;

            // Contact
            $wpdb->query("INSERT INTO $dv_rev_table ($rev_fields) VALUES ('address', 'contact', '{$user["contact"]}', $created_id, '$date');");
            $contact = $wpdb->insert_id;

            $wpdb->query("INSERT INTO $dv_rev_table ($rev_fields) VALUES ('address', 'contact_type', '{$user["contact_type"]}', $created_id, '$date');");
            $contact_type = $wpdb->insert_id;

            $contact_person = '0';
            if (isset($_POST['cnt_person'])){
                $wpdb->query("INSERT INTO $dv_rev_table ($rev_fields) VALUES ('address', 'contact_person', '{$user["contact_person"]}', $created_id, '$date');");
                $contact_person = $wpdb->insert_id;
            }

            //Save the address in the parent table
            $wpdb->query("INSERT INTO $table_address ($address_fields, latitude, longitude ) VALUES ($status, '{$user["created_by"]}', '{$user["type"]}',  $street, $brgy, $city, $province, $country, '$date', '$address_latitude', '$address_longitude')");

            $address_id = $wpdb->insert_id;

            //Update revision table for saving the parent_id(address_id)
            $wpdb->query("UPDATE $dv_rev_table SET `parent_id` = $address_id
            WHERE ID IN ($status, '{$user["type"]}', $street, $brgy, $city, $province, $country, $contact, $contact_type, $contact_person, $address_longitude, $address_latitude, $address_place_id, $address_formated_address);");

            $files = $request->get_file_params();

            if (isset($files['img'])){
                if (!empty($files)) {
                    if ( !isset($files['img'])) {
                        return  array(
                            "status" => "unknown",
                            "message" => "Please contact your administrator. Request unknown!",
                        );
                    }

                    // Call upload image function
                    $result = DV_Globals::upload_image( $request, $files);

                    if ($result['status'] != 'success') {
                        return array(
                            "status" => $result['status'],
                            "message" => $result['message']
                        );
                    }
                    $image_add = $result['data'];
                    $wpdb->query("INSERT INTO $dv_rev_table ($rev_fields, `parent_id`) VALUES ('address', 'image_address', '$image_add', $created_id, '$date', '$address_id' );");
                    $image_address =  $wpdb->insert_id;

                    $wpdb->query("UPDATE $table_address SET `img_url` = $image_address
                    WHERE ID IN ($address_id)");

                }
            }

            $update_id = $wpdb->insert_id;

                //Check if any of the insert queries above failed
            if ($status < 1 || $street < 1 || $brgy < 1 || $province < 1 || $city < 1 || $country < 1 || $address_id < 1 || $update_id < 1 ) {

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

            return rest_ensure_response(
                array(
                    "status" => "success",
                    "message" => "Address added successfully."
                )
            );

        } // End of listen function

        // Return of Insert user address object from POST.
        public static function catch_post()
        {
            $cur_user = array();

            $cur_user['type'] = $_POST['type'];
            $cur_user['street'] = $_POST['st'];
            $cur_user['brgy'] = $_POST['bg'];
            $cur_user['city'] = $_POST['ct'];
            $cur_user['province'] = $_POST['pv'];
            $cur_user['country'] = $_POST['co'];
            $cur_user['created_by'] = $_POST['wpid'];
            $cur_user['contact'] = $_POST['cnt'];
            $cur_user['contact_type'] = $_POST['cnt_type'];
            $cur_user['contact_person'] = $_POST['cnt_person'];
            $cur_user['lat'] = $_POST['lat'];
            $cur_user['long'] = $_POST['long'];

            return  $cur_user;
        }
    }
