
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
            $dv_docs = DV_DOCUMENTS;
            $doc_fields = DV_DOCS_FIELDS;
            $table_revs = DV_REVS_TABLE;
            $revs_fields = DV_INSERT_REV_FIELDS;
            $date_created = TP_Globals::date_stamp();

            // Step 2: Validate user
            if (DV_Verification::is_verified() == false) {
                return array(
                    "status" => "unknown",
                    "message" => "Please contact your administrator. Verification Issues!",
                );
            }

            if (!isset($_POST['docid']) || !isset($_POST['status']) ) {
                return array(
                    "status" => "unknown",
                    "message" => "Please contact your admnistrator. Request Unknown!"
                );
            }

            if (empty($_POST['docid']) || empty($_POST['status'])) {
                return array(
                    "status" => "failed",
                    "message" => "Required fields cannot be empty."
                );
            }

            $document_id = $_POST['docid'];
            $status = $_POST['status'];
            $wpid = $_POST['wpid'];

            $wpdb->query("START TRANSACTION");

            /* Check first if user has a document data in server */
            $check_doc = $wpdb->get_row("SELECT * FROM $dv_docs WHERE hash_id = '$document_id' AND parent_id = 0 ");

            if (!$check_doc) {
                return array(
                    "status" => "failed",
                    "message" => "This document does not exits.",
                );
            }

            $check_doc_child = $wpdb->get_row("SELECT COUNT(ID) as docs FROM $dv_docs WHERE parent_id = '$check_doc->ID' ");

            if ($check_doc_child->docs !== '2') {
                return array(
                    "status" => "failed",
                    "message" => "This user must complete his/her document first.",
                );
            }

            $check_status = $wpdb->get_row("SELECT child_val as `status` FROM $table_revs WHERE parent_id = $check_doc->ID AND revs_type = 'documents' AND child_key = 'approve_status' ");

            if (!empty($check_status)) {
                if ($check_status->status == '1') {
                    return array(
                        "status" => "failed",
                        "message" => "This user is already approved.",
                    );
                }
            }

            /* End */

            /**
             * status = 1 : approve
             * status = 0 : disapprove
             */
            if ($status === '0') {

                $status = $wpdb->query("INSERT INTO $table_revs (revs_type, parent_id, child_key, child_val, created_by, date_created) VALUES ('documents', '$check_doc->ID', 'approve_status', '0', '$wpid', '$date_created')");
                $status_id = $wpdb->insert_id;
                $wpdb->query("UPDATE $table_revs SET hash_id = sha2($status_id, 256) WHERE ID = $status_id");

            }else{

                $status = $wpdb->query("INSERT INTO $table_revs (revs_type, parent_id, child_key, child_val, created_by, date_created) VALUES ('documents', '$check_doc->ID', 'approve_status', '1', '$wpid', '$date_created')");
                $status_id = $wpdb->insert_id;
                $wpdb->query("UPDATE $table_revs SET hash_id = sha2($status_id, 256) WHERE ID = $status_id");

            }

            if ($status == false) {
                $wpdb->query("ROLLBACK");
                return array(
                    "status" => "failed",
                    "message" => "An error occured while submitting data to server."
                );
            }else{
                $wpdb->query("COMMIT");
                return array(
                    "status" => "success",
                    "message" => "Data has been added successfully."
                );
            }
        }
    }