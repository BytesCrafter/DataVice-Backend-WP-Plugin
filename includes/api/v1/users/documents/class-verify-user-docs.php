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
            // $verified = self::listen_open();
            // return rest_ensure_response(
            //     $verified['status'] == true ? true : $verified
            // );
            return rest_ensure_response(
				self::listen_open()
			);
        }

        public static function listen_open(){

            global $wpdb;
            $dv_docs = DV_DOCUMENTS;

            // Step 2: Validate user
            if (DV_Verification::is_verified() == false) {
                return array(
                    "status" => "failed",
                    "message" => "Please contact your administrator. Verification Issues!",
                );
            }

            $wpid = $_POST["wpid"];

            $get_data = $wpdb->get_row("SELECT
                IF((SELECT child_val FROM dv_revisions WHERE parent_id = doc.ID AND revs_type ='documents' AND child_key ='approve_status' AND ID = (SELECT MAX(ID) FROM dv_revisions rev WHERE parent_id = doc.ID AND ID = rev.ID AND revs_type ='documents' AND child_key ='approve_status'  )  ) = 1 , 'Approved',
                IF((SELECT child_val FROM dv_revisions WHERE parent_id = doc.ID AND revs_type ='documents' AND child_key ='approve_status' AND ID = (SELECT MAX(ID) FROM dv_revisions rev WHERE parent_id = doc.ID AND ID = rev.ID AND revs_type ='documents' AND child_key ='approve_status'  )  ) is null, 'Pending', 'Not approved' )
                    )as `approve_status`
            FROM $dv_docs doc WHERE parent_id = 0  AND wpid = '$wpid' ");

            if(empty($get_data)){
                return array(
                    "status" => "failed",
                    "message" => "This user does not exits in document list."
                );
            }


            if ($get_data->approve_status != "Approved") {
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