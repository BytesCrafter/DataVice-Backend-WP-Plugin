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

	class DV_Link_Account_Delete {

         // REST API for Forgotten Passwords
		public static function listen() {
            return rest_ensure_response(
				self::listen_open()
			);
        }

		public static function listen_open() {
            global $wpdb;
            $tbl_link_acc = DV_LINK_ACCOUNT;


            if (!isset($_POST['acid']) ) {
                return array(
                    "status" => "unknown",
                    "message" => "Please contact your administrator. Request unknown!"
                );
            }


            if (empty($_POST['acid']) ) {
                return array(
                    "status" => "failed",
                    "message" => "Required fileds cannot be empty"
                );
            }

            $id = $_POST['acid'];

            $wpdb->query("START TRANSACTION");

                $wpdb->query("UPDATE $tbl_link_acc SET `status` = '0' where hash_id = '$id'");

            if ($insert_account == false) {
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