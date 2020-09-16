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

            


        }
    }
