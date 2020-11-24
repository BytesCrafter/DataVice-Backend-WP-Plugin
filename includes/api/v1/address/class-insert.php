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

        public static function listen(){
            return rest_ensure_response(
                self::listen_open()
            );
        }

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
            isset($_POST['lat']) && !empty($_POST['lat'])? $cur_user['lat'] =  $_POST['lat'] :  $cur_user['lat'] = null ;
            isset($_POST['long']) && !empty($_POST['long'])? $cur_user['long'] =  $_POST['long'] :  $cur_user['long'] = null ;

            return  $cur_user;
        }

        public static function listen_open(WP_REST_Request $request){
            global $wpdb;
            $tbl_contacts = DV_CONTACTS_TABLE;
            $tbl_contacts_filed = DV_CONTACTS_FILEDS;

            // Step1: Validate user
             if ( DV_Verification::is_verified() == false ) {
                return array(
                    "status" => "unknown",
                    "message" => "Please contact your administrator. Verification Issue!",
                );
            }

            // Step 1 : Check if the fields are passed
            if( !isset($_POST['type']) || !isset($_POST['co']) || !isset($_POST['pv']) || !isset($_POST['ct']) || !isset($_POST['bg']) || !isset($_POST['st']) ||  !isset($_POST['cnt'])   || !isset($_POST['cnt_type']) ){
                return array(
                    "status"  => "unknown",
                    "message" => "Please contact your administrator. Request unknown!",
                );
            }

             // Step 1 : Check if the fields are passed
             if( empty($_POST['type']) || empty($_POST['co']) || empty($_POST['pv']) || empty($_POST['ct']) || empty($_POST['bg']) || empty($_POST['st']) ||  empty($_POST['cnt'])  || empty($_POST['cnt_type']) ){
                return array(
                    "status"  => "unknown",
                    "message" => "Please contact your administrator. Request Empty!",
                );
            }

            // Step5: Check if ID is in valid format (integer)
            if( !is_numeric($_POST['pv']) || !is_numeric($_POST['ct']) || !is_numeric($_POST['bg']) ){
                return array(
                    "status" => "unknown",
                    "message" => "Please contact your administrator. Request not in valid format!",
                );
            }

            // Get user object.
            $user = self::catch_post();
            $created_id = $user['created_by'];
            $image_add = '';

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
                }
            }

            $data = array(
                "st" => $user["street"],
                "bg" => $user["brgy"],
                "ct" => $user["city"],
                "pv" => $user["province"],
                "co" => $user["country"],
                "type" =>  $user["type"]
            );

            $address = DV_Address_Config::add_address( $data, $created_id, 0, $user["lat"], $user["long"],  $image_add);

                $contact = $wpdb->query("INSERT INTO $tbl_contacts ($tbl_contacts_filed) VALUES ( '{$address["data"]}', '{$user["contact"]}', '{$user["contact_type"]}', $created_id) ");
                $contact_id = $wpdb->insert_id;

                if (isset($_POST['cnt_person'])){
                    $contact_person = $_POST['cnt_person'];
                    $import_contact_person = $wpdb->query("UPDATE $tbl_contacts SET contact_person = '$contact_person' WHERE ID = '$contact_id' ");

                    if ($import_contact_person < 1) {
                        $wpdb->query("ROLLBACK");

                        return  array(
                            "status" => "error",
                            "message" => "An error occured while submitting data to the server."
                        );
                    }
                }
                $hash_id = DV_Globals::generating_hash_id($contact_id, $tbl_contacts, 'hash_id',  false, 64);
            // End

            //Check if any of the insert queries above failed
            if ( $address < 1 ) {

                //If failed, do mysql rollback (discard the insert queries(no inserted data))
                $wpdb->query("ROLLBACK");

                return array(
                    "status" => "error",
                    "message" => "An error occured while submitting data to the server."
                );
            }

            //If no problems found in queries above, do mysql commit (do changes(insert rows))
            $wpdb->query("COMMIT");

            return array(
                "status" => "success",
                "message" => "Address added successfully."
            );

        } // End of listen function
    }
