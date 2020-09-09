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

    class DV_Error_Log_Insert{

        public static function listen(){
            return rest_ensure_response(
				self::listen_open()
			);
        }

        public static function listen_open(){
            global $wpdb;
            $table_error = DV_ERROR_LOG;
            $table_error_fileds = DV_ERROR_LOG_FIELDS;

            if (!isset($_POST['erid'])) {
                return array(
                    "status" => "unknown",
                    "message" => "Please contact your administrator. Request unknown!"
                );
            }

            if (empty($_POST['erid'])) {
                return array(
                    "status" => "failed",
                    "message" => "Required fields cannot be empty."
                );
            }

            $error_id = $_POST['erid'];
            $update = $wpdb->query("UPDATE $table_error SET `status` = '0' WHERE hash_id = $error_id ");

            if ($update == false) {
                return array(
                    "status" => "failed",
                    "message" => "An error occured while submitting data to server."
                );
            }else{
                return array(
                    "status" => "success",
                    "message" => "Data has been added successfully."
                );
            }
        }
    }