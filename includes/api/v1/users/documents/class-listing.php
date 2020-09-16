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

    class DV_Listing_Documents{

        public static function listen(){
            return rest_ensure_response(
				self::listen_open()
			);
        }

        public static function listen_open(){

            global $wpdb;
            $dv_docs = DV_DOCUMENTS;
            $doc_fields = DV_DOCS_FIELDS;
            $table_revs = DV_REVS_TABLE;
            $revs_fields = DV_INSERT_REV_FIELDS;

             // Step 2: Validate user
             if (DV_Verification::is_verified() == false) {
                return array(
                    "status" => "unknown",
                    "message" => "Please contact your administrator. Verification Issues!",
                );
            }

            isset($_POST['status']) ? $status = $_POST['status'] : $status = NULL  ;
            isset($_POST['user_id']) ? $user_id = $_POST['user_id'] : $user_id = NULL  ;
            isset($_POST['docid']) ? $document = $_POST['docid'] : $document = NULL  ;

            $sql = "SELECT *,
                IF((SELECT child_val FROM dv_revisions WHERE parent_id = doc.ID AND revs_type ='documents' AND child_key ='approve_status' AND ID = (SELECT MAX(ID) FROM dv_revisions rev WHERE parent_id = doc.ID AND ID = rev.ID AND revs_type ='documents' AND child_key ='approve_status'  )  ) = 1 , 'Approved',
                IF((SELECT child_val FROM dv_revisions WHERE parent_id = doc.ID AND revs_type ='documents' AND child_key ='approve_status' AND ID = (SELECT MAX(ID) FROM dv_revisions rev WHERE parent_id = doc.ID AND ID = rev.ID AND revs_type ='documents' AND child_key ='approve_status'  )  ) is null, 'Pending', 'Not approved' )
                    )as `approve_status`,
                null as documents
            FROM $dv_docs doc WHERE parent_id = 0 ";

            if (isset($_POST['user_id'])) {
                if ($user_id !== null) {
                    $sql .= " WHERE wpid = '$user_id' ";
                }
            }

            if (isset($_POST['docid'])) {
                if ($document !== null && $user_id === null) {
                    $sql .= " WHERE hash_id = '$document' ";
                }
                if ($document !== null && $user_id !== null) {
                    $sql .= " AND hash_id = '$document' ";
                }
            }

            if (isset($_POST['status'])) {
                if ($status !== null) {

                    if ($status == '1') {
                        $sql .= " HAVING approve_status = 'Approved' ";
                    }else{
                        $sql .= " HAVING approve_status = 'Pending' ";
                    }
                }
            }

            $get_parent = $wpdb->get_results($sql);

            $var = array();
            foreach ($get_parent as $key => $value) {

                $value->documents = $get_child = $wpdb->get_results("SELECT
                    (SELECT child_val FROM dv_revisions WHERE parent_id = doc.ID AND revs_type ='documents' AND child_key ='name' AND ID = (SELECT MAX(ID) FROM dv_revisions rev WHERE parent_id = doc.ID AND ID = rev.ID AND revs_type ='documents' AND child_key ='name'  )  ) as `doctype`,
                    (SELECT child_val FROM dv_revisions WHERE parent_id = doc.ID AND revs_type ='documents' AND child_key ='preview' AND ID = (SELECT MAX(ID) FROM dv_revisions rev WHERE parent_id = doc.ID AND ID = rev.ID AND revs_type ='documents' AND child_key ='preview'  )  ) as `preview`
                FROM
                    dv_documents doc
                WHERE
                    doc.parent_id = $value->ID ");
            }

            return array(
                "status" => "success",
                "data" => $get_parent
            );

        }
    }
