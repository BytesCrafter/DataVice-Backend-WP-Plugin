<?php
	// Exit if accessed directly
	if ( ! defined( 'ABSPATH' ) )
	{
		exit;
	}

	/**
        * @package datavice-wp-plugin
        * @version 0.1.0
        * @author bytescrafter
        * Quality Controlled since 15/11/2020
    */

    class DV_Verify_Account{

        public static function listen() {
            return rest_ensure_response(
				self::listen_open()
			);
        }

        public static function listen_open(){
            global $wpdb;

            // Step 1 : Check if the fields are passed
            if (!isset($_POST['ak']) || !isset($_POST['npas']) || !isset($_POST['cpas'])) {
                return array(
                    "status" => "unknown",
                    "message" => "Please contact your administrator. Request unknown.",
                );
            }

            // Step 2 : Check if fields are empty.
            if (empty($_POST['ak']) || empty($_POST['npas']) || empty($_POST['cpas'])) {
                return array(
                    "status" => "failed",
                    "message" => "Required fields cannot be empty.",
                );
            }

            // Step 3 : Check if passwords are same.
            if ($_POST['cpas'] !== $_POST['npas']) {
                return array(
                    "status" => "failed",
                    "message" => "Password does not match.",
                );
            }

            // Step 4 : Check if password length is less than minimum requirement from config.
            $min_pw_req = 7; // TODO: Get from Config.
            if ( strlen($_POST['npas']) < 7) {
                return array(
                    "status" => "failed",
                    "message" => "Password length does not met the min requirement of 7.",
                );
            }

            // Step 5 : Check if the activation key is active.
            $check_akey = DV_Activate_Account::check_akey();
            
            if($check_akey['status'] != "success") {
                return array(
                    "status" => "failed",
                    "message" => $check_akey['message'],
                );
            }

            // Step 6 - Update users password and activation key.
            $activation_key = md5($_POST['ak']);
            $pword_hash = wp_hash_password($_POST['cpas']);

            $result = $wpdb->update(
                $wpdb->users, array(
                    'user_pass' => $pword_hash,
                    'user_activation_key' => ""
                ),
                array( 'user_activation_key' => $activation_key )
            );

            // Check if row successfully updated or not
            if (!$result) {
                return array(
                    "status" => "failed",
                    "message" => "Password was not change."
                );
            }

            // Step 7 : Removed expirate date forcing activation key unusable.
            $add_key_meta = update_user_meta( $check_akey['data']['ID'], 'reset_pword_expiry', "" );

            // Step 8 : If success, return status and message
            return array(
                "status" => "success",
                "message" => "Password updated successfully!"
            );
        }
    }