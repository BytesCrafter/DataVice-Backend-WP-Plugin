<?php
	// Exit if accessed directly
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	/**
        * @package datavice-wp-plugin
		* @version 0.1.0
		* This is the primary gateway of all the rest api request.
    */

    class DV_Change_Password{

        //REST API Call
        public static function listen(){
            return rest_ensure_response(
                self::listen_open()
            );
        }

        public static function listen_open(){

            if ( DV_Verification::is_verified() == false ) {
                return array(
                    "status" => "unknown",
                    "message" => "Please contact your administrator. Verification Issue!",
                );
            }

            if (!isset($_POST['pas']) || !isset($_POST['npas']) || !isset($_POST['cpas']) ) {
                return array(
                    "status" => "unknown",
                    "message" => "Please contact your administrator. Request Unknown!",
                );
            }

            $user = get_userdata($_POST['wpid']);

            if ($_POST['npas'] !== $_POST['cpas']) {
                return array(
                    "status" => "failed",
                    "message" => "Password does not match."
                );
            }

            if ( $user && !wp_check_password( $_POST['pas'], $user->data->user_pass, $user->ID ) ) {
                return array(
                    "status" => "failed",
                    "message" => "Old password does not match."
                );
            } else {

                global $wpdb;

                $new_pass = wp_hash_password($_POST['cpas']);
                $updatepass = $wpdb->query("UPDATE {$wpdb->prefix}users
                    SET `user_pass` = '{$new_pass}'
                    WHERE `ID` = '{$_POST['wpid']}';");

                if (is_wp_error($updatepass)) {
                    return array(
                        "status" => "failed",
                        "message" => "An error occured while submitting data to server."
                    );
                }else{
                    return array(
                        "status" => "success",
                        "message" => "Data has been update successfully."
                    );
                }
            }
        }
    }