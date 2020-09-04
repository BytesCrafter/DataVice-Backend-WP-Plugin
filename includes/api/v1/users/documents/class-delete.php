
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

	class DV_Delete_docs {

		public static function listen(WP_REST_Request $request) {
			return rest_ensure_response( 
                
                self::listen_open($request)
            );
		}
        public static function listen_open($request){

            global $wpdb;

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

            // Step 3: Sanitize all Request
            if (!isset($_POST['docid']) ) {
				return array(
					"status" => "unknown",
					"message" => "Please contact your administrator. Request unknown!",
                );
            }
            
            // Step 4: Sanitize all Request if emply
            if (empty($_POST['docid']) ) {
				return array(
					"status" => "failed",
					"message" => "Required fields cannot be empty.",
                );
            }

            // Put all request to variable
            $wpid = $_POST['wpid'];
            $doc_id = $_POST['docid'];
            $date_created = TP_Globals::date_stamp();
            $tp_docs = DV_DOCUMENTS;
            $doc_fields = DOCS_FIELDS;
            $table_revs = DV_REVS_TABLE;  
            $revs_fields = DV_INSERT_REV_FIELDS; 

            // Step 5: Check document if exist using document id, store id and document type
            $check_doc =  $wpdb->get_row("SELECT 
                ID, 
                (SELECT child_val FROM $table_revs WHERE parent_id = doc.status) AS `status` 
            FROM 
                $tp_docs doc
            WHERE 
                hash_id = '$doc_id'  
            AND 
                wpid = '$wpid' ");


            if (!$check_doc || $check_doc->status === '0') {
                return array(
                    "status" => "failed",
                    "message" => "This document does not exist."
                );
            } 

            //  Step 6: Return Success
            $wpdb->query("START TRANSACTION");

            $insert = $wpdb->query("INSERT INTO $table_revs ($revs_fields, parent_id) VALUES ('documents', 'status', '0', '$wpid', '$date_created', '$doc_id' ) ");
            $last_id_doc = $wpdb->insert_id;

            $wpdb->query("UPDATE $table_revs SET hash_id = sha2($last_id_doc, 256) WHERE ID = $last_id_doc");

            $update = $wpdb->query("UPDATE $tp_docs SET status = $last_id_doc WHERE hash_id = '$doc_id' ");

            //  Step 7: Return Success
            if ($insert < 1 || $update < 1 ) {
                $wpdb->query("ROLLBACK");
                return array(
					"status" => "failed",
                    "message" => "An error occurred while submitting data to server."
                );
            }else {
                $wpdb->query("COMMIT");
                return array(
					"status" => "success",
                    "message" => "Data has been deleted successfully."
                );
            }
        }
    }