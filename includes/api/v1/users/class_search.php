
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

	class DV_Search_User {

		public static function listen() {
			return rest_ensure_response(
				self::verify()
			);
        }

        public static function listen_open(){
            global $wpdb;

            
        }
    }