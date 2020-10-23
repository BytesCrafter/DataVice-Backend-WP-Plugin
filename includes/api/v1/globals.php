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

  	class DV_Globals {

        //Declare a private variable for mysql table name
        private $table;
        private $rev_table;

        /** Global function for retrieving from database
		* @param1 = {table name in database};
        * @param2 = {fields to be selected}
        * @param3 = {optional. WHERE clause}
		* @param4 = {optional. ORDER BY field}
		* @param5 = {optional. ASC or DESC}
	    */
        public static function retrieve($table_name, $fields, $where = NULL, $sort_field = NULL , $sort = NULL){
            global $wpdb;

            $table = $table_name;

            return $wpdb->get_results("SELECT $fields FROM $table $where $sort_field $sort ");
        }

        // Return Current DateTime
        public static function date_stamp(){
            return date("Y-m-d h:i:s");
        }

        // TODO: Convert the date
        public static function convert_user_datetime($tzone_id, $date_time) {
            //STEP 1 - Get timezone name from tzone ID.
            //step 2 - USe that tzone to convert date_time to current timezone.
        }

        /** Checking if $id has row. Returns boolean
	    * Returns true if  row found, false if not found, "unavail" if status is not active
		* @param1 = {table name in database};
        * @param2 = {id key}
		* @param3 = {optional. additional where clause}
	    */
        public static function check_availability($table_name, $where, $return_id = NULL){
            global $wpdb;

            $table = $table_name;

            $result = $wpdb->get_row("SELECT id, status FROM $table $where;");

            if (!$result) {
                return false;
            } else {

                if($return_id == true) {
                    return $result->id;
                }

                if ($result->status == 0){
                    return "unavail";
                } else {
                    return true;
                }
            }
        }

        public static function custom_update($parent_id, $wpid, $rev_type, $parent_table, $revisions_table, $data, $where){

            global $wpdb;

            $date = DV_Globals:: date_stamp();

            if ( ! is_array( $data ) || ! is_array( $where ) ) {
                return false;
            }

            //Initialize empty array
            $fields     = array();
            $insert_fields = array();
            $insert_values = array();
            $conditions = array();
            $values     = array();

            //Remove null data
            foreach ( $data as $field => $value ) {
                if ( is_null( $value ) ) {
                    unset($data[$field]);
                    continue;
                }
            }
            $wpdb->query("START TRANSACTION");
            //Insert into revisions table
            foreach ($data as $key => $value) {
                $insert_result = $wpdb->query("INSERT INTO $revisions_table (`revs_type`, `parent_id`, `child_key`, `child_val`, `created_by`, `date_created`) VALUES ('$rev_type', '$parent_id', '$key', '$value', '$wpid', '$date')");
                if ($insert_result < 1) {
                    $wpdb->query("ROLLBACK");
                    return false;
                }
                $insert_values[$key] = $wpdb->insert_id;
            }

            //Get all `where` conditions
            foreach ( $where as $field => $value ) {
                if ( is_null( $value ) ) {
                    $conditions[] = "`$field` IS NULL";
                    continue;
                }

                $conditions[] = "`$field` = " . $value;
            }

            //Make fields a comma seperated values
            $conditions = implode( ' AND ', $conditions );

            foreach ($insert_values as $key => $value) {
                $result = $wpdb->query("UPDATE $parent_table SET $key = $value WHERE ID = $parent_id");
                if ($result < 1) {
                    $wpdb->query("ROLLBACK");
                    return false;
                }
            }

            $wpdb->query("COMMIT");
            return true;

        }
        public static function activation_key(){
            $activation_key_length = DV_Library_Config:: dv_get_config('activation_key_length', 5);

            $digits = $activation_key_length;
            return rand(pow(10, $digits-1), pow(10, $digits)-1);
        }

        public static function check_roles($role){

            $wp_user = get_userdata($_POST['wpid']);

            if ( in_array('administrator' , $wp_user->roles, true) ) {
                return true;
            }

            if ( in_array($role , $wp_user->roles, true) ) {
                return true;
            }

            return false;
        }

        public static function old_tiger($data = "", $width=20, $rounds = 3) {
            return substr(
                implode(
                    array_map(
                        function ($h) {
                            return str_pad(bin2hex(strrev($h)), 16, "0");
                        },
                        str_split(hash("tiger192,$rounds", $data, true), 8)
                    )
                ),
                0, 48-(192-$width)/4
            );
        }

        public static function upload_image($request, $files){


            $max_img_size = DV_Library_Config::dv_get_config('max_img_size', 123);
            if (!$max_img_size) {
                return array(
                    "status" => "unknown",
                    "message" => "Please contact your administrator. Can't find config of img size.",
                );
            }

            //Get the directory of uploading folder
            $target_dir = wp_upload_dir();

            //Get the file extension of the uploaded image
            $file_type = strtolower(pathinfo($target_dir['path'] . '/' . basename($files['img']['name']),PATHINFO_EXTENSION));

            if (!isset($_POST['IN'])) {
                $img_name = $files['img']['name'];

            } else {
                $img_name = sanitize_file_name($_POST['IN']);

            }

            $completed_file_name = sha1(date("Y-m-d~h:i:s"))."-".$img_name;

            $target_file = $target_dir['path'] . '/' . basename($completed_file_name);
            $uploadOk = 1;

            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

            $check = getimagesize($files['img']['tmp_name']);

            if($check !== false) {
                $uploadOk = 1;

            } else {
                $uploadOk = 0;
                return array(
                    "status" => "failed",
                    "message" => "File is not an image.",
                );
            }

            // Check if file already exists
            if (file_exists($target_file)) {
                //  file already exists
                $uploadOk = 0;
                return array(
                    "status" => "failed",
                    "message" => "File is already existed.",
                );
            }

            // Check file size
            if ($files['img']['size'] > $max_img_size) {
                // file is too large
                $uploadOk = 0;
                return array(
                    "status" => "failed",
                    "message" => "File is too large.",
                );
            }

            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType !=
                "jpeg"
                && $imageFileType != "gif" ) {
                //only JPG, JPEG, PNG & GIF files are allowed
                $uploadOk = 0;
                return array(
                    "status" => "failed",
                    "message" => "Only JPG, JPEG, PNG & GIF files are allowed.",
                );
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
               // file was not uploaded.
                // if everything is ok, try to upload file
                    return array(
                        "status" => "unknown",
                        "message" => "Please contact your admnistrator. File has not uploaded! ",
                    );

            } else {//

                if (move_uploaded_file($files['img']['tmp_name'], $target_file)) {

                    $pic = $files['img'];
                    $file_mime = mime_content_type( $target_file);

                    $upload_id = wp_insert_attachment( array(
                        'guid'           => $target_file,
                        'post_mime_type' => $file_mime,
                        'post_title'     => preg_replace( '/\.[^.]+$/', '', $pic['name'] ),
                        'post_content'   => '',
                        'post_status'    => 'inherit'
                    ), $target_file );

                    // wp_generate_attachment_metadata() won't work if you do not include this file
                    require_once( ABSPATH . 'wp-admin/includes/image.php' );

                    $attach_data = wp_generate_attachment_metadata( $upload_id, $target_file );

                    // Generate and save the attachment metas into the database
                    wp_update_attachment_metadata( $upload_id, $attach_data );

                    // Show the uploaded file in browser
	                wp_redirect( $target_dir['url'] . '/' . basename( $target_file ) );

                    //return file path
                    return array(
                        "status" => "success",
                        "data" =>  (string)$target_dir['url'].'/'.basename($completed_file_name),
                    );

                } else {
                    //there was an error uploading your file
                    return array(
                        "status" => "unknown",
                        "message" => "Please contact your admnistrator. File has not uploaded! ",
                    );

                }
            }
        }


        public static function get_location($string, $api_key){
            $string = str_replace(" ", "+", urlencode($string));

            $details_url = "https://maps.googleapis.com/maps/api/geocode/json?address=".$string."&sensor=false&key=".$api_key."";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $details_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = json_decode(curl_exec($ch), true);

            // If Status Code is ZERO_RESULTS, OVER_QUERY_LIMIT, REQUEST_DENIED or INVALID_REQUEST
            if ($response['status'] != 'OK') {
                return null;
            }

            $geometry = $response['results'][0]['geometry'];
            $place_id = $response['results'][0]['place_id'];
            $formatted_address = $response['results'][0]['formatted_address'];


            $longitude = $geometry['location']['lat'];
            $latitude = $geometry['location']['lng'];

            $array = array(
                'latitude' => $geometry['location']['lat'],
                'longitude' => $geometry['location']['lng'],
                'location_type' => $geometry['location_type'],
                'place_id' => $place_id,
                'formatted_address' => $formatted_address,
            );


	        return $array;

        }

        public static function get_formated_address($street, $data){

            global $wpdb;

            $brgy = '';
            $city = '';
            $province = '';
            $country = '';

            foreach ($data as $key => $value) {

                switch($key){
                    case 'dv_geo_brgys':
                        $brgy = $wpdb->get_row("SELECT brgy_name FROM $key WHERE ID = '$value';");
                        break;
                    case 'dv_geo_cities':
                        $city = $wpdb->get_row("SELECT city_name FROM $key WHERE city_code = '$value';");
                        break;
                    case 'dv_geo_provinces':
                        $province = $wpdb->get_row("SELECT prov_name FROM $key WHERE prov_code = '$value';");
                        break;
                    case 'dv_geo_countries':
                        $country = $wpdb->get_row("SELECT country_name FROM $key WHERE country_code = '$value';");
                        break;

                }

            }


            return  $street.", ".$brgy->brgy_name.", ".$city->city_name.", ".$province->prov_name.", ".$country->country_name;


        }

        public static function update_public_key_hash($primary_key, $table_name){
			global $wpdb;

			$results = $wpdb->query("UPDATE  $table_name SET hash_id = concat(
							substring('abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', rand(@seed:=round(rand($primary_key)*4294967296))*36+1, 1),
							substring('abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', rand(@seed:=round(rand(@seed)*4294967296))*36+1, 1),
							substring('abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', rand(@seed:=round(rand(@seed)*4294967296))*36+1, 1),
							substring('abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', rand(@seed:=round(rand(@seed)*4294967296))*36+1, 1),
							substring('abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', rand(@seed:=round(rand(@seed)*4294967296))*36+1, 1),
							substring('abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', rand(@seed:=round(rand(@seed)*4294967296))*36+1, 1),
							substring('abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', rand(@seed:=round(rand(@seed)*4294967296))*36+1, 1),
							substring('abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', rand(@seed:=round(rand(@seed)*4294967296))*36+1, 1),
							substring('abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', rand(@seed:=round(rand(@seed)*4294967296))*36+1, 1),
							substring('abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', rand(@seed:=round(rand(@seed)*4294967296))*36+1, 1),
							substring('abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', rand(@seed:=round(rand(@seed)*4294967296))*36+1, 1),
							substring('abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', rand(@seed)*36+1, 1)
						)
						WHERE ID = $primary_key;");
			if ($results < 1) {
				return false;
			}else{
				if ($results == 1) {
					return true;
				}
			}
		}


    } // end of class