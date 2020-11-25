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

        public static function catch_post(){
            $curl_user = array();
            $curl_user['data'] = $_POST['data'];
            $curl_user['wpid'] = $_POST['wpid'];
            return $curl_user;
        }

        public static function listen_open($request){
            global $wpdb;

            // Declare variables
            $dv_docs = DV_DOCUMENTS;
            $doc_fields = DV_DOCS_FIELDS;
            $table_revs = DV_REVS_TABLE;
            $revs_fields = DV_INSERT_REV_FIELDS;
            $user = self::catch_post();
            $date_created = DV_Globals::date_stamp();

            // Step 2: Validate user
            if (DV_Verification::is_verified() == false) {
                return array(
                    "status" => "unknown",
                    "message" => "Please contact your administrator. Verification Issues!",
                );
            }

            $files = $request->get_file_params();

            if ( !isset($_POST['data']) ) {
				return  array(
                    "status" => "unknown",
                    "message" => "Please contact your administrator. Request unknown!",
                );
            }

            $check_documents = $wpdb->get_row("SELECT COUNT(ID) as docs FROM $dv_docs WHERE wpid = '{$user["wpid"]}' ");

            if (!empty($check_documents)) {
                if ($check_documents->docs == "2") {
                    return array(
                        "status" => "failed",
                        "message" => "This user has already have two documents.",
                    );
                }
            }

            // Optional Upload for avatar and banner
            if (isset($files['id']) || isset($files['face'])) {

                if (empty($files['id']['name'])) {
                    return array(
                        "status" => "failed",
                        "message" => "ID document image is required"
                    );
                }
                if (empty($files['face']['name'])) {
                    return array(
                        "status" => "failed",
                        "message" => "Face document image is required"
                    );
                }

                $image = DV_Globals::multiple_upload_image( $request, $files);
                if ($image['status'] != 'success') {
                    return array(
                        "status" => $image['status'],
                        "message" => $image['message']
                    );
                }

            }

            $wpdb->query("START TRANSACTION");

            foreach ($user['data'] as $key => $value) {

                if ($value['type'] != "face" && $value['type'] != "id" ) {
                    return array(
                        "status" => "failed",
                        "message" => "Invalid value of document type '{$value['type']}'.",
                    );
                }

                $check_document = $wpdb->get_row("SELECT ID  FROM $dv_docs doc  WHERE doc.wpid = '{$user["wpid"]}'  AND doc.types = '{$value["type"]}' ");

                if (!empty($check_document)) {
                    return array(
                        "status" => "failed",
                        "message" => "This document is already exits."
                    );
                }

                switch ($value['type']) {
                    case 'face':
                        /* Import data */
                            $face = $wpdb->query("INSERT INTO $dv_docs
                                    (`wpid`, `preview`, `types`,  `instructions`)
                                VALUES
                                    ('{$user["wpid"]}', '{$image["data"][0]["face_id"]}', 'face', '{$value["instruction"]}'  ) ");
                            $face_id = $wpdb->insert_id;

                            $face_hsid = $wpdb->query("UPDATE $dv_docs SET hash_id = sha2($face_id, 256) WHERE ID = '$face_id' ");
                        /* End */
                        break;

                    case 'id':
                        /* Import data */
                            $id = $wpdb->query("INSERT INTO $dv_docs
                                    (`wpid`, `preview`, `types`, `id_number`, `doctype`)
                                VALUES
                                    ('{$user["wpid"]}', '{$image["data"][0]["id_id"]}', 'id', '{$value["number_contact"]}', '{$value["doctype"]}'  ) ");
                            $id_id = $wpdb->insert_id;

                            $id_hsid = $wpdb->query("UPDATE $dv_docs SET hash_id = sha2($id_id, 256) WHERE ID = '$id_id' ");
                        /* End */
                        break;
                }
            }

            if ($id < 1 || $face < 1) {
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