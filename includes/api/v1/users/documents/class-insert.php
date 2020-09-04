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
    
    class DV_Create_Documents{

        public static function listen(WP_REST_Request $request){
            return rest_ensure_response( 
				self::listen_open($request)
			);
        }

        public static function listen_open($request){
            
            global $wpdb;
           
            // Step 2: Validate user
            if (DV_Verification::is_verified() == false) {
                return array(
                    "status" => "unknown",
                    "message" => "Please contact your administrator. Verification Issues!",
                );
            }
            
            $files = $request->get_file_params();
            
            if ( !isset($files['img'])) {
				return  array(
                    "status" => "unknown",
                    "message" => "Please contact your administrator. Request Unknown!",
                );
            }

            // Step 3: Sanitize if all variables at POST
            if ( !isset($_POST['type']) ) {
				return array(
					"status" => "unknown",
					"message" => "Please contact your administrator. Request unknown!",
                );
                
            }
                
            // Step 4: Check if all variables is not empty 
            if ( empty($_POST['type']) ) {
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
            $dv_docs = DV_DOCUMENTS;
            $doc_fields = DV_DOCS_FIELDS;
            $table_revs = DV_REVS_TABLE;  
            $revs_fields = DV_INSERT_REV_FIELDS;
            $doc_type = $_POST['type'];
            $wpid = $_POST['wpid'];
            $date_created = TP_Globals::date_stamp();

            // Step 5: Check document if exist using store id and document type
            $check_doc =  $wpdb->get_row("SELECT doctype, (SELECT child_val FROM $table_revs WHERE ID = doc.status) AS status  FROM $dv_docs doc WHERE wpid = '$wpid' AND doctype = '$doc_type'  ");
            if ($check_doc !== NULL) {
                if ($check_doc->doctype === $doc_type || $check_doc->status === '1' ) {
                    return array(
                        "status" => "failed",
                        "message" => "This document has already exist."
                    );
                } 
            }
            
            // Step 6: Start Query
            $wpdb->query("START TRANSACTION");
            
            $result = DV_Globals::upload_image( $request, $files); // upload image

            $doc_prev = substr($result['data'], 45); // get /year/month/filename to save in database
            
            $child_key = array( //stored in array
                'preview'   =>$doc_prev, 
                'status'    =>'1'
            );
            
             $insert1 = $wpdb->query("INSERT INTO $dv_docs ($doc_fields) VALUES ($wpid, 0, '$doc_type')");
                $last_id_doc = $wpdb->insert_id;

            $id = array();

            foreach ( $child_key as $key => $child_val) {                                     
                $insert2 = $wpdb->query("INSERT INTO $table_revs ($revs_fields, `parent_id`) VALUES ('documents', '$key', '$child_val', '$wpid', '$date_created', sha2($last_id_doc, 256) ) ");
                $id[] = $wpdb->insert_id; 
            }

                $update1 = $wpdb->query("UPDATE $table_revs SET `hash_id` = sha2($id[0], 256) WHERE ID = $id[0]");
                $update2 = $wpdb->query("UPDATE $table_revs SET `hash_id` = sha2($id[1], 256) WHERE ID = $id[1]");
            
            $update = $wpdb->query("UPDATE $dv_docs SET preview = $id[0], status = '$id[1]', date_created = '$date_created', `hash_id` = sha2($last_id_doc, 256) WHERE ID = $last_id_doc ");
           
            // Step 7: Check if query has result
            if ($insert2 < 1 || $insert1 < 1 || $update < 1 || !$result) {
                $wpdb->query("ROLLBACK");
                return array(
                    "status" => "failed",
                    "message" => "Please contact your administrator. Submitting document failed."
                );
            }else {
                $wpdb->query("COMMIT");
                return array(
                    "status" => "success",
                    "message" => "Data has been added successfully."
                );
            }
        }
    }