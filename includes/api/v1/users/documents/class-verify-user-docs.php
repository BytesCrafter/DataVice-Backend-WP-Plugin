<?php
	// Exit if accessed directly
	if ( ! defined( 'ABSPATH' ) )
	{
		exit;
	}

	/**
        * @package datavice-wp-plugin
        * @version 0.1.0
    */

    class DV_Verify_User_Documents{

        public static function listen(){
            return rest_ensure_response(
				self::listen_open()
			);
        }

        public static function listen_open(){

            global $wpdb;
            $dv_docs = DV_DOCUMENTS;
            $approved = 0;

            // Step 2: Validate user
            if (DV_Verification::is_verified() == false) {
                return array(
                    "status" => "failed",
                    "message" => "Please contact your administrator. Verification Issues!",
                );
            }

            $wpid = $_POST["wpid"];

            $get_data = $wpdb->get_results("SELECT * FROM $dv_docs WHERE wpid = $wpid AND id IN ( SELECT MAX( id ) FROM $dv_docs d WHERE d.hash_id = hash_id GROUP BY hash_id )");

            if(empty($get_data)){
                return array(
                    "status" => "failed",
                    "message" => "This user does not exits in document list."
                );
            }

            foreach ($get_data as $key => $value) {

                if ($value->executed_by != null && $value->activated == "true") {
                    $approved = $approved + 1;
                }
            }

            if ($approved < 2) {
                return array(
                    "status" => "failed",
                    "message" => "This user documents is not verified."
                );
            }else{
                return array(
                    "status" => "success",
                    "message" => "This user is fully verified."
                );
            }
        }
    }