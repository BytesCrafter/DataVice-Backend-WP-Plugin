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

    class DV_Select_All_Address{
        public static function listen(){
			global $wpdb;

            // Step 1: Validate user
            if ( DV_Verification::is_verified() == false ) {
                return rest_ensure_response(
                    array(
                        "status" => "unknown",
                        "message" => "Please contact your administrator. Verification Issue!",
                    )
                );
            }

			$dv_rev_table = DV_REVS_TABLE;
            $table_address = DV_ADDRESS_TABLE;
            $country_table = DV_COUNTRY_TABLE;
            $province_table = DV_PROVINCE_TABLE;
            $city_table = DV_CITY_TABLE;
            $brgy_table = DV_BRGY_TABLE;

            $user = $_POST['wpid'];

            $result  = $wpdb->get_results("SELECT
				dv_add.ID,
				dv_add.types,
				dv_add.wpid,
				dv_add.stid,
				(SELECT dv_rev.child_val FROM $dv_rev_table dv_rev WHERE dv_rev.ID = dv_add.status ) as status,
				(SELECT dv_rev.child_val FROM $dv_rev_table dv_rev WHERE dv_rev.ID = dv_add.street ) as street,
				(SELECT bg.brgy_name FROM $brgy_table bg WHERE bg.ID = (SELECT dv_rev.child_val FROM $dv_rev_table dv_rev WHERE dv_rev.ID = dv_add.brgy ) ) as brgy,
				(SELECT ct.city_name FROM $city_table ct WHERE ct.city_code = (SELECT dv_rev.child_val FROM $dv_rev_table dv_rev WHERE dv_rev.ID = dv_add.city ) ) as city,
				(SELECT pr.prov_name FROM $province_table pr WHERE pr.prov_code = (SELECT dv_rev.child_val FROM $dv_rev_table dv_rev WHERE dv_rev.ID = dv_add.province ) ) as province,
				(SELECT co.country_name FROM $country_table co WHERE co.ID = (SELECT dv_rev.child_val FROM $dv_rev_table dv_rev WHERE dv_rev.ID = dv_add.country ) ) as country,
				(SELECT dv_rev.child_val FROM $dv_rev_table dv_rev WHERE dv_rev.ID = dv_add.img_url ) as `preview`,
				(SELECT dv_rev.child_val FROM $dv_rev_table dv_rev WHERE dv_rev.parent_id = dv_add.ID  AND child_key = 'contact' AND revs_type = 'address' ) as `contact`,
				(SELECT dv_rev.child_val FROM $dv_rev_table dv_rev WHERE dv_rev.parent_id = dv_add.ID  AND child_key = 'contact_type' AND revs_type = 'address' ) as `contact_type`,
				(SELECT dv_rev.child_val FROM $dv_rev_table dv_rev WHERE dv_rev.parent_id = dv_add.ID  AND child_key = 'contact_person' AND revs_type = 'address' ) as `contact_person`
			FROM
				$table_address dv_add
			INNER JOIN $dv_rev_table dv_rev
				ON dv_rev.ID = dv_add.`status`
				WHERE  dv_add.wpid = '$user' AND (SELECT dv_rev.child_val FROM $dv_rev_table dv_rev WHERE dv_rev.ID = dv_add.status ) = 1
			");

				return rest_ensure_response(
					array(
						"status" => "success",
						"data" => $result
					)
				);
		}
	}