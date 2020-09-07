
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

	class DV_Approve_docs {

		public static function listen(WP_REST_Request $request) {
			return rest_ensure_response(

                self::listen_open($request)
            );
		}
        public static function listen_open($request){

            global $wpdb;
            $revs_fields = DV_INSERT_REV_FIELDS;
            $date_created = TP_Globals::date_stamp();

            // Step 2: Validate user
            if (DV_Verification::is_verified() == false) {
                return array(
                    "status" => "unknown",
                    "message" => "Please contact your administrator. Verification Issues!",
                );
            }

            if (!isset($_POST['docid'])) {
                return array(
                    "status" => "unknown",
                    "message" => "Please contact your admnistrator. Request Unknown!"
                );
            }

            if (empty($_POST['docid'])) {
                return array(
                    "status" => "failed",
                    "message" => "Required fields cannot be empty."
                );
            }

            $document_id = $_POST['docid'];

            $sql = "SELECT
                doc.ID,
                prev.child_val as preview,
                sts.child_val as `status`,
                ( SELECT child_val FROM dv_revisions WHERE parent_id = doc.ID AND child_key ='approve_status' AND revs_type ='documents' ) as `approve_status`,
                ( SELECT date_created FROM dv_revisions WHERE parent_id = doc.ID AND child_key ='approve_status' AND revs_type ='documents' ) as `approve_date`,
                ( SELECT created_by FROM dv_revisions WHERE parent_id = doc.ID AND child_key ='approve_status' AND revs_type ='documents' ) as `approve_by`
            FROM
                dv_documents doc
            LEFT JOIN dv_revisions sts ON sts.ID = doc.`status`
            LEFT JOIN dv_revisions prev ON prev.ID = doc.`preview`
            WHERE doc.hash_id = '$document_id'
            ";

            $get_docu = $wpdb->get_row($sql);

            if ($get_docu->status == 0 ) {
                return array(
                    "status" => "failed",
                    "message" => "This document does not exists"
                );
            }

            if ($get_docu->approve_status !== NULL ) {
                return array(
                    "status" => "failed",
                    "message" => "This document is already approved by ".$get_docu->approve_by
                );
            }

            $user_id = $_POST['wpid'];

            $wpdb->query("START TRANSACTION");

            $result_approve_status = $wpdb->query("INSERT INTO dv_revisions ($revs_fields, parent_id) VALUES ('documents', 'approve_status', '1', '$user_id', '$date_created',  '$get_docu->ID' )");
            $revs_id = $wpdb->insert_id;

            $wpdb->query("UPDATE dv_revisions SET hash_id = sha2($revs_id, 256) WHERE ID = $revs_id ");

            if ($result_approve_status === false ) {
                $wpdb->query("ROLLBACK");
                return array(
                    "status" => "failed",
                    "message" => "An error occured while submitting data to server."
                );

            }else{
                $wpdb->query("COMMIT");
                return array(
                    "status" => "success",
                    "message" => "Data has been submitted successfully."
                );
            }
        }
    }