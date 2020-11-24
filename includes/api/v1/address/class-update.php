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

    class DV_Update_Address {

        public static function listen(){
            return rest_ensure_response(
                self::listen_open()
            );
        }

        // Return of Insert user address object from POST.
        public static function catch_post()
        {
            $cur_user = array();

            $cur_user['id'] = $_POST['id'];
            $cur_user['created_by'] = $_POST['wpid'];

            return  $cur_user;
        }

        public static function listen_open(){
            global $wpdb;
            $tbl_contacts = DV_CONTACTS_TABLE;
            $tbl_contacts_filed = DV_CONTACTS_FILEDS;
            $tbl_brgy = DV_BRGY_TABLE;
            $tbl_city = DV_CITY_TABLE;
            $tb_countries = DV_COUNTRY_TABLE;
            $tbl_province = DV_PROVINCE_TABLE;

            // Step1: Validate user
             if ( DV_Verification::is_verified() == false ) {
                return array(
                    "status" => "unknown",
                    "message" => "Please contact your administrator. Verification Issue!",
                );
            }

            // Step 1 : Check if the fields are passed
            if( !isset($_POST['id'])){
                return array(
                    "status"  => "unknown",
                    "message" => "Please contact your administrator. Request unknown!",
                );
            }

            // Step 1 : Check if the fields are passed
            if( empty($_POST['id'])){
                return array(
                    "status"  => "unknown",
                    "message" => "Please contact your administrator. Request Empty!",
                );
            }

            // Get user object.
            $user = self::catch_post();
            $created_id = $user['created_by'];
            $wpdb->query("START TRANSACTION");

			$data = DV_Address_Config::get_address( null, null, null, $_POST['id'] );

            if ($data["status"] == "failed") {
                return array(
                    "status" => "failed",
                    "message" => $data["message"]
                );
            }

            isset($_POST['bg']) && !empty($_POST['bg'])? $user['bg'] =  $_POST['bg'] :  $user['bg'] = DV_Address_Config::get_geo_location( $tbl_brgy, 'brgy_name', $data["data"][0]->brgy )['data'][0]->ID;
            isset($_POST['ct']) && !empty($_POST['ct'])? $user['ct'] =  $_POST['ct'] :  $user['ct'] = DV_Address_Config::get_geo_location( $tbl_city, 'city_name', $data["data"][0]->city )['data'][0]->city_code;
            isset($_POST['pv']) && !empty($_POST['pv'])? $user['pv'] =  $_POST['pv'] :  $user['pv'] = DV_Address_Config::get_geo_location( $tbl_province, 'prov_name', $data["data"][0]->province )['data'][0]->prov_code;
            isset($_POST['co']) && !empty($_POST['co'])? $user['co'] =  $_POST['co'] :  $user['co'] = DV_Address_Config::get_geo_location( $tb_countries, 'country_name', $data["data"][0]->country )['data'][0]->country_code;
            isset($_POST['type']) && !empty($_POST['type'])? $user['type'] =  $_POST['type'] :  $user['type'] = $data["data"][0]->types;
            isset($_POST['lat']) && !empty($_POST['lat'])? $user['lat'] =  $_POST['lat'] :  $user['lat'] = $data["data"][0]->latitude;
            isset($_POST['long']) && !empty($_POST['long'])? $user['long'] =  $_POST['long'] :  $user['long'] =  $data["data"][0]->longitude;

            $address = array(
                "st" =>  $data["data"][0]->street,
                "bg" =>  $user['bg'],
                "ct" =>  $user['ct'],
                "pv" =>  $user['pv'],
                "co" =>  $user['co'],
                "type" => $user['type']
            );

            $address = DV_Address_Config::add_address(
                    $address,
                    $data["data"][0]->wpid,
                    $data["data"][0]->stid,
                    $user['lat'],
                    $user['long'],
                    $data["data"][0]->img_url,
                    'active',
                    $user["adid"]
                );

            //Check if any of the insert queries above failed
            if ( $address["status"] == "failed" ) {

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
                "message" => "Address updated successfully."
            );

        } // End of listen function
    }
