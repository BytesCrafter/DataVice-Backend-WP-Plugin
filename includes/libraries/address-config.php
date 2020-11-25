<?php
	// Exit if accessed directly
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	/**
	 * @package datavice-wp-plugin
     * @version 0.1.0
     * Data for list of cities in Philippines only.
    */

    class DV_Address_Config {

        /**
         * Listing Script of Address
        */
        public static function get_address( int $user_id = null, string $store_id = null, string $status = null, string $address_id = null, string $type = null ){
            global $wpdb;
            $tbl_address = DV_ADDRESS_TABLE;
            $tb_countries = DV_COUNTRY_TABLE;
            $tbl_brgy = DV_BRGY_TABLE;
            $tbl_city = DV_CITY_TABLE;
            $tbl_province = DV_PROVINCE_TABLE;
            $tbl_contacts = DV_CONTACTS_TABLE;

            if ( DV_Verification::is_verified() == false ) {
                return array(
                    "status" => "unknown",
                    "message" => "Please contact your administrator. Verification Issue!",
                );
            }

            $sql = "SELECT * FROM $tbl_address WHERE id IN ( SELECT MAX( id ) FROM $tbl_address ad WHERE ad.hash_id = hash_id  GROUP BY hash_id ) ";

            if ($user_id != null) {
                $sql .= " AND `wpid` = $user_id ";
            }

            if ($store_id != null) {
                $sql .= " AND `stid` = '$store_id' ";
            }

            if ($status != null) {
                if ($status != 'active' && $status != 'inactive') {
                    return array(
                        "status" => "failed",
                        "message" => "Invalid value of status."
                    );
                }

                $sql .= " AND `status` = '$status'  ";
            }

            if ($address_id != null) {
                $sql .= " AND `hash_id` = '$address_id' ";
            }

            if ($type != null) {
                $sql .= " AND `types` = '$type' ";
            }

            $data = $wpdb->get_results($sql);

            foreach ($data as $key => $value) {
                $value->brgy_code = $value->brgy;
                $value->city_code = $value->city;
                $value->province_code = $value->province;
                $value->country_code = $value->country;
                $value->brgy = self::get_geo_location( $tbl_brgy, 'ID', $value->brgy )['data'][0]->brgy_name;
                $value->city = self::get_geo_location( $tbl_city, 'city_code', $value->city )['data'][0]->city_name;
                $value->province = self::get_geo_location( $tbl_province, 'prov_code', $value->province )['data'][0]->prov_name;
                $value->country = self::get_geo_location( $tb_countries, 'country_code', $value->country )['data'][0]->country_name;


                $get_contact = $wpdb->get_row("SELECT * FROM $tbl_contacts WHERE adid = '$value->hash_id' AND id IN ( SELECT MAX( id ) FROM $tbl_contacts c WHERE c.hash_id = hash_id  GROUP BY hash_id ) ");

                if (!empty($get_contact)) {
                    $value->contact_id = $get_contact->hash_id;
                    $value->contact_status = $get_contact->status;
                    $value->contact = $get_contact->value;
                    $value->contact_person = $get_contact->contact_person = null? '': $get_contact->contact_person ;
                    $value->contact_type = $get_contact->contact_type;
                }
            }

            return array(
                "status" => "success",
                "data" => $data
            );
        }

        /**
         * Insert Script of Address
        */
        public static function add_address( array $data = array(), $user_id = 0, $store_id = 0, $latitude = null, $longitude = null, string $image_url = null, $status = 'active', $address_id = null){
            global $wpdb;
            $tbl_address = DV_ADDRESS_TABLE;
            $tbl_address_filed = DV_INSERT_ADDRESS_FIELDS;
            $error = 0;
            $f_address = array();
            $street = '';

            if ( DV_Verification::is_verified() == false ) {
                return array(
                    "status" => "unknown",
                    "message" => "Please contact your administrator. Verification Issue!",
                );
            }

            $default_keys = array( "st" , "bg", "ct", "pv", "co", "type" );

            // Check if array data is completed value
                if (count($data) != count($default_keys)) {
                    $error = 1;
                    return array(
                        "status" => "failed",
                        "message" => "Theres a miss default key is adding address."
                    );
                }
            // End

			foreach ($data as $key => $value) {

                switch ($key) {
                    case 'st':
                        $street = $value;
                        break;
                    case 'bg':
                        $bg_status = DV_Globals:: check_availability(DV_BRGY_TABLE, 'WHERE `id` = '.$value);
                        if ($bg_status == false) {
                            return array(
                                "status" => "failed",
                                "message" => "Invalid value for barangay"
                            );

                        }

                        if ($bg_status === "unavail") {
                            return  array(
                                "status" => "failed",
                                "message" => "Not available yet in selected barangay",
                            );
                        }
                        $f_address[DV_BRGY_TABLE] = $value;
                        break;

                    case 'ct':
                        $ct_status = DV_Globals:: check_availability(DV_CITY_TABLE, " WHERE city_code ='".$value."'");

                        if ($ct_status == false) {
                            return array(
                                "status" => "failed",
                                "message" => "Invalid value for city"
                            );

                        }
                        if ($ct_status === "unavail") {
                            return  array(
                                "status" => "failed",
                                "message" => "Not available yet in selected city",
                            );
                        }
                        $f_address[DV_CITY_TABLE] = $value;

                        break;

                    case 'pv':
                        $pv_status = DV_Globals:: check_availability(DV_PROVINCE_TABLE, " WHERE prov_code ='".$value."'");

                        if ($pv_status == false) {

                            return array(
                                "status" => "failed",
                                "message" => "Invalid value for province"
                            );

                        }
                        if ($pv_status === "unavail") {

                            return  array(
                                "status" => "failed",
                                "message" => "Not available yet in selected province",
                            );
                        }
                        $f_address[DV_PROVINCE_TABLE] = $value;

                        break;

                    case 'co':
                        $co_status = DV_Globals:: check_availability(DV_COUNTRY_TABLE, " WHERE country_code = '$value' ", true);

                        if ($co_status == false) {

                            return array(
                                "status" => "failed",
                                "message" => "Invalid value for country"
                            );

                        }
                        if ($co_status === "unavail") {

                            return  array(
                                "status" => "failed",
                                "message" => "Not available yet in selected country",
                            );
                        }
                        $f_address[DV_COUNTRY_TABLE] = $value;

                        break;

                    case 'type':
                        if (!empty($value)) {
                            if ($value != "home" && $value != "office" ) {
                                return  array(
                                    "status" => "failed",
                                    "message" => "Invalid Value of type",
                                );
                            }
                        }
                        break;
                }

                $error ++;
            }

            $wpdb->query("START TRANSACTION");

            $import = $wpdb->query("INSERT INTO $tbl_address
                    ( `stid`, $tbl_address_filed, `img_url`, `status` )
                VALUES
                    ( '$store_id',  $user_id, '{$data["type"]}', '{$data["st"]}', '{$data["bg"]}',  '{$data["ct"]}', '{$data["pv"]}', '{$data["co"]}', '$image_url', '$status' ) ");
            $import_id = $wpdb->insert_id;

            if ($latitude == null || $longitude == null) {

                $address = DV_Globals::get_formated_address($street, $f_address);
                $geolocation = DV_Globals::get_location($address, "AIzaSyDeR29pTg1D5Exga3rQd8a3XKL3XtukQQg");

                if($geolocation == null){

                    return array(
                        "status" => "failed",
                        "message" => "Please contact your administrator. Geolocation Api Failed"
                    );
                }
                $latitude = $geolocation["latitude"];
                $longitude = $geolocation["longitude"];
            }

            if ($address_id != null || $address_id != 0 ) {

                $wpdb->query("UPDATE $tbl_address SET `hash_id` = '$address_id'  WHERE ID = $import_id ");

            }else{
                $hash_id = DV_Globals::generating_hash_id($import_id, $tbl_address, 'hash_id', $get_key = true, $length = 64);
                $address_id = $hash_id;
            }

            $wpdb->query("UPDATE $tbl_address SET `latitude` = $latitude, `longitude` = $longitude  WHERE ID = $import_id ");

            if ($import < 1) {
                $wpdb->query("ROLLBACK");
                return array(
                    "status" => "failed",
                    "message" => "An error occured while submitting data to server.",
                );
            }else{
                $wpdb->query("COMMIT");
                return array(
                    "status" => "success",
                    "message" => "Data has been added successfully.",
                    "data" => $address_id
                );
            }
        }


        /**
         * Get Latitude and Longitude Script of Address
        */
        public static function get_geo_location( string $table_name, string $column_name, $key){
            global $wpdb;

            if ( DV_Verification::is_verified() == false ) {
                return array(
                    "status" => "unknown",
                    "message" => "Please contact your administrator. Verification Issue!",
                );
            }

            $data = $wpdb->get_results("SELECT * FROM $table_name WHERE $column_name = '$key' ;");

            if (!empty($data)) {

                if ($data[0]->status == "0") {
                    return array(
                        "status" => "failed",
                        "status" => "This $column_name value is currently unavailable."
                    );
                }

                return array(
                    "status" => "success",
                    "data" => $data
                );

            }else{
                return array(
                    "status" => "failed",
                    "status" => "This $column_name key does not exists."
                );
            }
        }
    }