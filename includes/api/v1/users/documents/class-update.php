
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

	class DV_Update_docs {

		public static function listen(WP_REST_Request $request) {
			return rest_ensure_response(

                self::listen_open($request)
            );
		}

        public static function listen_open($request){

            global $wpdb;
            $files = $request->get_file_params();

            // Step 1: Check if prerequisites plugin are missing
            $plugin = TP_Globals::verify_prerequisites();
            if ($plugin !== true) {
                return array(
                    "status" => "unknown",
                    "message" => "Please contact your administrator. ".$plugin." plugin missing!",
                );
            }

            // Step 2: Validate user
            if (DV_Verification::is_verified() == false) {
                return array(
                    "status" => "unknown",
                    "message" => "Please contact your administrator. Verification Issues!",
                );
            }

            // Step 3: Sanitize if all variables at POST
            if ( !isset($_POST['type'])
                || !isset($_POST['docid']) ) {
				return array(
					"status" => "unknown",
					"message" => "Please contact your administrator. Request unknown!",
                );

            }

            // Step 4: Check if all variables is not empty
            if ( empty($_POST['type'])
                || empty($_POST['docid'])) {
                return array(
                    "status" => "failed",
                    "message" => "Required fileds cannot be empty.",
                );
            }

            if ($_POST['type'] !== "sss"
            && $_POST['type'] !== "drivers_license"
            && $_POST['type'] !== "prc"
            && $_POST['type'] !== "owwa"
            && $_POST['type'] !== "voters_id"
            && $_POST['type'] !== "pnp"
            && $_POST['type'] !== "senior_id"
            && $_POST['type'] !== "postal_id"
            && $_POST['type'] !== "school_id"
            && $_POST['type'] !== "passport"
            ) {
                return array(
                    "status" => "failed",
                    "message" => "Invalid type of documents.",
                );
            }

            // Declare variables
            $tp_docs = DV_DOCUMENTS;
            $doc_fields = DV_DOCS_FIELDS;
            $table_revs = DV_REVS_TABLE;
            $revs_fields = DV_INSERT_REV_FIELDS;
            $doc_type = $_POST['type'];
            $wpid = $_POST['wpid'];
            $doc_id = $_POST['docid'];
            $date_created = TP_Globals::date_stamp();

            // Step 5: Check document if exist using document id, store id and document type
            $check_doc =  $wpdb->get_row("SELECT ID, (SELECT child_val FROM $table_revs WHERE ID = $tp_docs.status) AS `status` FROM $tp_docs WHERE hash_id = '$doc_id'  AND wpid = '$wpid' AND doctype = '$doc_type' ");
            if (!$check_doc || $check_doc->status === '0') {
                return array(
                    "status" => "failed",
                    "message" => "This document does not exist."
                );
            }

            // Step 6: Start Query
            $wpdb->query("START TRANSACTION");

            $result = DV_Globals::upload_image($request, $files); // upload image
            $doc_prev = substr($result['data'], 45); // get /year/month/filename to save in database
                                                                                            // `revs_type`, `child_key`, `child_val`, `created_by`, `date_created`
            $insert = $wpdb->query("INSERT INTO $table_revs ($revs_fields, parent_id) VALUES ('documents', 'preview', '$doc_prev', '$wpid','$date_created', '$doc_id' ) ");
            $last_id_doc = $wpdb->insert_id;

            $wpdb->query("UPDATE $table_revs SET hash_id = sha2($last_id_doc, 256) WHERE ID = $last_id_doc");

            $update = $wpdb->query("UPDATE $tp_docs SET preview = $last_id_doc WHERE `hash_id` = '$doc_id' ");

            // Step 7: Check if query has result
            if ($result == false|| $insert < 1 || $update < 1) {
                $wpdb->query("ROLLBACK");
                // Step 8: return result
                return array(
                    "status" => "failed",
                    "message" => "An error occurred while submitting data to server."
                );

            }else {
                //  Step 9: Return Success
                $wpdb->query("COMMIT");
                return array(
                    "status" => "success",
                    "message" => "Data has been updated successfully."
                );
            }
        }
    }