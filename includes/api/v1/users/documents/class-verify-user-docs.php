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
            $verified = self::listen_open();
            return rest_ensure_response(
                $verified['status'] == true ? true : $verified
            );
        }

        public static function listen_open(){

            global $wpdb;

            // Step 2: Validate user
            if (DV_Verification::is_verified() == false) {
                return array(
                    "status" => false,
                    "message" => "Please contact your administrator. Verification Issues!",
                );
            }

            $wpid = $_POST["wpid"];

            $get_data = $wpdb->get_results("SELECT *,
                IF((SELECT child_val FROM dv_revisions WHERE parent_id = doc.ID AND revs_type ='documents' AND child_key ='approve_status' AND ID = (SELECT MAX(ID) FROM dv_revisions rev WHERE parent_id = doc.ID AND ID = rev.ID AND revs_type ='documents' AND child_key ='approve_status'  )  ) = 1 , 'Approved',
                IF((SELECT child_val FROM dv_revisions WHERE parent_id = doc.ID AND revs_type ='documents' AND child_key ='approve_status' AND ID = (SELECT MAX(ID) FROM dv_revisions rev WHERE parent_id = doc.ID AND ID = rev.ID AND revs_type ='documents' AND child_key ='approve_status'  )  ) is null, 'Pending', 'Not approved' )
                    )as `approve_status`
            FROM dv_documents doc WHERE parent_id != 0 AND wpid = '$wpid'  ");

            if(!$get_data){
                return array(
                    "status" => false,
                    "message" => "This user does not exits in document list."
                );
            }

            if (count($get_data) != 2) {
                return array(
                    "status" => false,
                    "message" => "user must have 2 documents"
                );
            }

            $var = 0;
            foreach ($get_data as $key => $value) {
                if ($value->approve_status == "Approved") {
                    $var ++;
                }
            }

            if ($var != 2) {
                return array(
                    "status" => false,
                    "message" => "Only ".$var." document is approve. All documents must be approved to be fully verified."
                );
            }else{
                return array(
                    "status" => true,
                    "message" => "This user is fully verified."
                );
            }
        }
    }