
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
?>
<?php
	class DV_Upload {

		public static function listen(WP_REST_Request $request) {
			return rest_ensure_response( 
				DV_Upload::listen_open($request)
			);
		}

		public static function listen_open($request) {
            global $wpdb;

            
			$result = DV_Globals::upload_image( $request);
            if ($result['status'] != 'success') {
                return array(
                    "status" => $result['status'],
                    "message" => $result['message']
                );
            }else{
                return array(
                    "status" => $result['status'],
                    "data" => $result['data']
                );
            }
            

        }
    }