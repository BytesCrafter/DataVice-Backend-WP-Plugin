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

            if (!isset($_POST['platform']) || !isset($_POST['device_ip']) || !isset($_POST['public_ip'])  ) {
                return array(
                    "status" => "unknown",
                    "message" => "Please contact your administrator. Request unknown!"
                );
            }


            if (empty($_POST['platform']) || empty($_POST['device_ip']) || empty($_POST['public_ip'])  ) {
                return array(
                    "status" => "failed",
                    "message" => "Required fields cannot be empty."
                );
            }

            $platform = $_POST['platform'];
            $deveice_ip = $_POST['device_ip'];
            $public_ip = $_POST['public_ip'];

            $wpdb->query("START TRANSACTION");
                $insert = $wpdb->query($wpdb->prepare("INSERT INTO $table_error ($table_error_fileds, `status`) VALUES ('%s', '%s', '%s', %d) ", $platform, $deveice_ip, $public_ip, 1 ));
                $id = $wpdb->insert_id;

                $wpdb->query("UPDATE $table_error SET hash_id = sha2($id, 256) WHERE ID = $id");

                if (isset($_POST['error_key'])) {
                    if ( empty($_POST['error_key']) ) {
                        return array(
                            "status" => "failed",
                            "message" => "Required fields cannot be empty."
                        );
                    }

                    $error = $_POST['error_key'];
                    $update_error_key = $wpdb->query("UPDATE $table_error SET error_key = '$error' WHERE ID = $id ");

                    if ($update_error_key == false) {
                        return array(
                            "status" => "failed",
                            "message" => "An error occured while submitting data to server."
                        );
                    }
                }

                if (isset($_POST['error_code'])) {
                    if ( empty($_POST['error_code']) ) {
                        return array(
                            "status" => "failed",
                            "message" => "Required fields cannot be empty."
                        );
                    }

                    $error_code = $_POST['error_code'];
                    $update_error_code = $wpdb->query("UPDATE $table_error SET error_code = '$error_code' WHERE ID = $id ");

                    if ($update_error_code == false) {
                        return array(
                            "status" => "failed",
                            "message" => "An error occured while submitting data to server."
                        );
                    }
                }

                if ($insert == false) {
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