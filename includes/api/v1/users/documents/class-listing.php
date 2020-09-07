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

        public static function listen(WP_REST_Request $request){
            return rest_ensure_response(
				self::listen_open($request)
			);
        }

        public static function listen_open($request){

            global $wpdb;

            $sql = "SELECT
                doc.hash_id as ID,
                prev.child_val as preview,
                IF ( sts.child_val = 1, 'Active', 'Inactive') as `status`,
                ( SELECT child_val FROM dv_revisions WHERE parent_id = doc.ID AND child_key ='approve_status' AND revs_type ='documents' ) as `approve_status`,
                ( SELECT date_created FROM dv_revisions WHERE parent_id = doc.ID AND child_key ='approve_status' AND revs_type ='documents' ) as `approve_date`,
                ( SELECT created_by FROM dv_revisions WHERE parent_id = doc.ID AND child_key ='approve_status' AND revs_type ='documents' ) as `approve_by`
            FROM
                dv_documents doc
            LEFT JOIN dv_revisions sts ON sts.ID = doc.`status`
            LEFT JOIN dv_revisions prev ON prev.ID = doc.`preview`";

            $results = $wpdb->get_results($sql);

            return $results;

        }
    }
