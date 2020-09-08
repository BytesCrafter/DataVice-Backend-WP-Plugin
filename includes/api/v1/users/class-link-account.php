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

	class DV_Link_Account {

        // REST API for Forgotten Passwords
		public static function listen() {
            global $wpdb;

            $tbl_link_acc = DV_LINK_ACCOUNT;

            if ( DV_Verification::is_verified() == false ) {
                return array(
                    "status" => "unknown",
                    "message" => "Please contact your administrator. Verification Issue!",
                );
            }

            if (!isset($_POST['token']) || !isset($_POST['platform']) ) {
                return array(
                    "status" => "unknown",
                    "message" => "Please contact your administrator. Request unknown!"
                );
            }

            if (empty($_POST['token']) || empty($_POST['platform']) ) {
                return array(
                    "status" => "failed",
                    "message" => "Required fileds cannot be empty"
                );
            }


            if ($_POST['platform'] !== 'google' && $_POST['platform'] !== 'facebook' ) {
                return array(
                    "status" => "failed",
                    "message" => "Invalid value of platform."
                );
            }

            $wpdb->query("START TRANSACTION");

                $insert_account = $wpdb->query($wpdb->prepare("INSERT INTO $tbl_link_acc (`wpid`, `platform`, `token`) VALUES (%d, '%s', '%s')", $_POST['wpid'], $_POST['platform'], $_POST['token']));
                $acc_id = $wpdb->insert_id;

                $wpdb->query("UPDATE $tbl_link_acc SET hash_id = sha2($acc_id, 256) WHERE ID = $acc_id");

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