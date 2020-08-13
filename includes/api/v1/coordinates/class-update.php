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
    class DV_Update_Coordinates{

        //REST API Call
        public static function listen(){
            return rest_ensure_response( 
                DV_Update_Coordinates::update_coords()
            );
        }
        
        //Main function
        public static function update_coords() {
            global $wpdb;
           
            // Step 1: Validate user
            if ( DV_Verification::is_verified() == false ) {
                return array(
                        "status" => "unknown",
                        "message" => "Please contact your administrator. Verification Issue!",
                );
            }

            // Step 2: Sanitize and validate all Request
			if ( !isset($_POST['lat']) || !isset($_POST['lon']) || !isset($_POST['addr']) ) {
				return array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request Unknown!",
                );
            }

            // Step 3: Check if required fields are not empty
            if ( empty($_POST['lat']) || empty($_POST['lon']) || empty($_POST['addr']) ) {
				return array(
						"status" => "failed",
						"message" => "Required fields cannot be empty.",
                );
            }

            if ( !is_numeric($_POST['lat']) || !is_numeric($_POST['lon']) ) {
				return array(
						"status" => "failed",
						"message" => "This is not a valid location coordinates",
                );
            }

            // Step 4: Pass constants to variables and catching post values
            $table_address = DV_ADDRESS_TABLE;
            $dv_rev_table = DV_REVS_TABLE;
            $country_table = DV_COUNTRY_TABLE;
            $province_table = DV_PROVINCE_TABLE;
            $city_table = DV_CITY_TABLE;
            $brgy_table = DV_BRGY_TABLE;
            $address_id = $_POST['addr'];
            $latitude = $_POST['lat'];
            $longitude = $_POST['lon'];
            $wpid = $_POST['wpid'];
           
            // Step 5: Check if this address exists
            $get_address = $wpdb->get_row("SELECT
                    dv_add.id,
                    (SELECT dv_rev.child_val FROM $dv_rev_table dv_rev WHERE dv_rev.ID = dv_add.status ) as status
                FROM
                    $table_address dv_add
                INNER JOIN $dv_rev_table dv_rev 
                    ON dv_rev.ID = dv_add.status
                WHERE dv_add.ID = $address_id
            ");

            //Check if 0 rows found
            if (!$get_address) {
                return array(
                    "status" => "failed",
                    "message" => "This address does not exists.",
                );
            }

            //Fails if address status = 0
            if ($get_address->status == 0) {
                return array(
                    "status" => "failed",
                    "message" => "This address is currently inactive.",
                );
            }
            
            $date_stamp = DV_Globals::date_stamp();

            // Step 6: Start mysql transaction
            $wpdb->query("START TRANSACTION ");

            $wpdb->query("INSERT INTO `$dv_rev_table` (revs_type, parent_id, child_key, child_val, created_by, date_created) 
                                VALUES ( 'address', $address_id, 'latitude', '$latitude', $wpid, '$date_stamp'  )");
            $lat_id = $wpdb->insert_id;

            $wpdb->query("INSERT INTO `$dv_rev_table` (revs_type, parent_id, child_key, child_val, created_by, date_created) 
                                VALUES ( 'address', $address_id, 'longitude', '$longitude', $wpid, '$date_stamp'  )");
            $lon_id = $wpdb->insert_id;

            $result = $wpdb->query("UPDATE $table_address  SET `latitude` = $lat_id, `longitude` = $lon_id WHERE ID = $address_id ");


            // Step 7: Check if any of the insert queries above failed
            if ($lat_id < 1  || $lon_id < 1) {
                //If failed, do mysql rollback (discard the insert queries(no inserted data))
                $wpdb->query("ROLLBACK");
                
                return rest_ensure_response( 
                    array(
                        "status" => "error",
                        "message" => "An error occured while submitting data to the server."
                    )
                );
            }

            //Step 8: If no problems found in queries above, do mysql commit (do changes(insert rows))
            $wpdb->query("COMMIT");

            return rest_ensure_response( 
                array(
                        "status" => "success",
                        "message" => "Data has been updated successfully.",
                )
            );
        }

    }