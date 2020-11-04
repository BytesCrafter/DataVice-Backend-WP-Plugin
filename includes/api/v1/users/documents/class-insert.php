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

            if ( !isset($files['img']) ) {
				return  array(
                    "status" => "unknown",
                    "message" => "Please contact your administrator. Image not Set!",
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


            // Declare variables
            $dv_docs = DV_DOCUMENTS;
            $doc_fields = DV_DOCS_FIELDS;
            $table_revs = DV_REVS_TABLE;
            $revs_fields = DV_INSERT_REV_FIELDS;
            $doc_type = $_POST['type'];
            $wpid = $_POST['wpid'];
            $number_contact = 0;
            $doctype = 'face';
            $date_created = TP_Globals::date_stamp();

            if ($_POST['type'] !== "id" && $_POST['type'] !== "face") {
                return array(
                    "status" => "failed",
                    "message" => "Invalid type of documents.",
                );
            }

            if ( $_POST['type'] == 'id'  ) {

                if ( !isset($_POST['doctype']) || !isset($_POST['number_contact']) ) {
                    return array(
                        "status" => "unknown",
                        "message" => "Please contact your administrator. Request unknown!",
                    );
                }

                if ( empty($_POST['doctype']) || empty($_POST['number_contact']) ) {
                    return array(
                        "status" => "failed",
                        "message" => "Required fileds cannot be empty.",
                    );
                }

                if ($_POST['doctype'] !== "sss"
                && $_POST['doctype'] !== "driver"
                && $_POST['doctype'] !== "prc"
                && $_POST['doctype'] !== "owwa"
                && $_POST['doctype'] !== "voters"
                && $_POST['doctype'] !== "pnp"
                && $_POST['doctype'] !== "senior"
                && $_POST['doctype'] !== "postal"
                && $_POST['doctype'] !== "school"
                && $_POST['doctype'] !== "passport"
                ) {
                    return array(
                        "status" => "failed",
                        "message" => "Invalid type of ID.",
                    );
                }
                $doctype = $_POST['doctype'];
            }

            if ( $_POST['type'] == 'face'  ){
                if ( !isset($_POST['nationality']) ) {
                    return array(
                        "status" => "unknown",
                        "message" => "Please contact your administrator. Request unknown!",
                    );
                }
                $nationality = $_POST['nationality'];
            }

            $wpdb->query("START TRANSACTION");

            /* Check first if user has a document data in server */
                $check_doc = $wpdb->get_row("SELECT * FROM $dv_docs WHERE wpid = $wpid AND parent_id = 0 ");

                // Check child documents
                if (!empty($check_doc)) {
                    $check_doc_child = $wpdb->get_row("SELECT COUNT(ID) as docs FROM $dv_docs WHERE parent_id = '$check_doc->ID' ");

                    if ($check_doc_child->docs == "2") {
                        return array(
                            "status" => "failed",
                            "message" => "This user has already have two documents.",
                        );
                    }

                    $get_parent = $wpdb->get_results("SELECT
                            doc.ID AS ID,
                            wpid,
                            date_created,
                            ( SELECT child_val FROM dv_revisions WHERE parent_id = doc.ID AND child_key = 'status' AND revs_type = 'documents' ) AS `status`
                        FROM
                            dv_documents doc
                        WHERE
                            doc.parent_id = 0
                        AND
                            doc.wpid = $wpid");

                    $var = array();
                    // Verift if doucment is already exits in database
                    foreach ($get_parent as $key => $value) {

                        $get_child_data = $wpdb->get_results("SELECT
                            (SELECT child_val FROM dv_revisions WHERE parent_id = doc.ID AND revs_type ='documents' AND child_key ='name' AND ID = (SELECT MAX(ID) FROM dv_revisions rev WHERE parent_id = doc.ID AND ID = rev.ID AND revs_type ='documents' AND child_key ='name'  )  ) as `doctype`,
                            (SELECT child_val FROM dv_revisions WHERE parent_id = doc.ID AND revs_type ='documents' AND child_key ='preview' AND ID = (SELECT MAX(ID) FROM dv_revisions rev WHERE parent_id = doc.ID AND ID = rev.ID AND revs_type ='documents' AND child_key ='preview'  )  ) as `preview`
                        FROM
                            dv_documents doc
                        WHERE
                            doc.parent_id = $value->ID
                            AND wpid = $wpid;");

                        foreach ($get_child_data as $key => $value) {
                            $var[] = $value->doctype;
                        }
                    }

                    if (in_array($doctype, $var, true)) {
                        return array(
                            "status" => "failed",
                            "message" => "This document is already exits."
                        );
                    }
                }

            /* End */

            /* Upload image */
                $result = DV_Globals::upload_image( $request, $files); // upload image

                if ($result['status'] == 'failed') {
                    return array(
                        "status" => "failed",
                        "message" => $result['message']
                    );
                }

                $doc_prev = $result['data'];
            /* End */


            /* inset parent Document */
                if (!$check_doc) {

                    $p_doc = $wpdb->query("INSERT INTO $dv_docs (wpid, parent_id) VALUES ( $wpid, '0' )");
                    $p_doc_id = $wpdb->insert_id;
                    $wpdb->query("UPDATE $dv_docs SET hash_id = sha2($p_doc_id, 256) WHERE ID = $p_doc_id");
                    //dv_rev table
                    $p_doc_rev = $wpdb->query("INSERT INTO $table_revs (revs_type, parent_id, child_key, child_val, created_by, date_created) VALUES ('documents', '$p_doc_id', 'status', '1', '$wpid', '$date_created')");
                    $p_doc_rev_id = $wpdb->insert_id;
                    $wpdb->query("UPDATE $table_revs SET hash_id = sha2($p_doc_rev_id, 256) WHERE ID = $p_doc_rev_id");

                    if ($p_doc == false || $p_doc_rev == false) {
                        $wpdb->query("ROLLBACK");
                        return array(
                            "status" => "failed",
                            "message" => "An error occured while submitting data to server."
                        );
                    }
                }else{
                    $p_doc_id = $check_doc->ID;
                }
            /* End */


            /* insert child document */
                $c_doc = $wpdb->query("INSERT INTO $dv_docs (wpid, parent_id) VALUES ( $wpid, $p_doc_id )");
                $c_doc_id = $wpdb->insert_id;
                $wpdb->query("UPDATE $dv_docs SET hash_id = sha2($c_doc_id, 256) WHERE ID = $c_doc_id");

                //dv_rev table name
                $c_doc_rev_name = $wpdb->query("INSERT INTO $table_revs (revs_type, parent_id, child_key, child_val, created_by, date_created) VALUES ('documents', '$c_doc_id', 'name', '$doctype', '$wpid', '$date_created')");
                $c_doc_rev_name_id = $wpdb->insert_id;
                $wpdb->query("UPDATE $table_revs SET hash_id = sha2($c_doc_rev_name_id, 256) WHERE ID = $c_doc_rev_name_id");

                // preview
                $c_doc_rev_preview = $wpdb->query("INSERT INTO $table_revs (revs_type, parent_id, child_key, child_val, created_by, date_created) VALUES ('documents', '$c_doc_id', 'preview', '$doc_prev', '$wpid', '$date_created')");
                $c_doc_rev_preview_id = $wpdb->insert_id;
                $wpdb->query("UPDATE $table_revs SET hash_id = sha2($c_doc_rev_preview_id, 256) WHERE ID = $c_doc_rev_preview_id");

                if ($_POST['type'] == "face"){

                    $c_doc_rev_nationality = $wpdb->query("INSERT INTO $table_revs (revs_type, parent_id, child_key, child_val, created_by, date_created) VALUES ('documents', '$c_doc_id', 'nationality', '$nationality', '$wpid', '$date_created')");
                    $wpdb->query("UPDATE $table_revs SET hash_id = sha2($c_doc_rev_name_id, 256) WHERE ID = $wpdb->insert_id;");

                    $c_doc_rev_contact = $wpdb->query("INSERT INTO $table_revs (revs_type, parent_id, child_key, child_val, created_by, date_created) VALUES ('documents', '$c_doc_id', 'contact', '$number_contact', '$wpid', '$date_created')");
                    $wpdb->query("UPDATE $table_revs SET hash_id = sha2($c_doc_rev_name_id, 256) WHERE ID = $wpdb->insert_id;");
                }
                if ($_POST['type'] == "id"){

                    $c_doc_rev_num = $wpdb->query("INSERT INTO $table_revs (revs_type, parent_id, child_key, child_val, created_by, date_created) VALUES ('documents', '$c_doc_id', 'id_number', '$number_contact', '$wpid', '$date_created')");
                    $wpdb->query("UPDATE $table_revs SET hash_id = sha2($c_doc_rev_name_id, 256) WHERE ID = $wpdb->insert_id;");
                }

            /* End */

            if ($c_doc == false || $c_doc_rev_name == false || $c_doc_rev_preview == false) {
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