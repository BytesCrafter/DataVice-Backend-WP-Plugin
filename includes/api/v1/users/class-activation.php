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

    class DV_Activate_Account{

        public static function listen() {
            return rest_ensure_response(
				self::listen_open()
			);
        }

        public static function listen_open(){

            // Step 1 : Check if the fields are passed
            if (!isset($_POST['ak'])) {
                return array(
                    "status" => "unknown",
                    "message" => "Please contact your administrator. Request Unknown!",
                );
            }

            // Step 2 : Check if fields are empty.
            if (empty($_POST['ak'])) {
                return array(
                    "status" => "failed",
                    "message" => "Required fields cannot be empty.",
                );
            }

            return self::check_akey();
        }

        public static function check_akey() {
            // Step 1 : Check if activation key is valid format.
            global $wpdb; 

            $actkey = md5($_POST['ak']);
            $cur_user = $wpdb->get_row("SELECT ID, user_email FROM {$wpdb->prefix}users 
                WHERE `user_activation_key` = '{$actkey}'", OBJECT );

            // Check for cur_user. Return a message if null
            if ( !$cur_user ) {
                return array(
                    "status" => "failed",
                    "message" => "Activation key is invalid!",
                );
            }

            // Step 2 : Check if password reset key is used.
            $expiry_meta = get_user_meta($cur_user->ID, 'reset_pword_expiry', true);

            if( empty($expiry_meta) ) {
                return array(
                    "status" => "failed",
                    "message" => "Password activation key is not valid.",
                );
            }

            // Step 3 : Check if activation key is expired.
            if( time() >= strtotime($expiry_meta) )
            {
                return array(
                    "status" => "failed",
                    "message" => "Password reset key is already expired.",
                );
            }

            // Step 4 : Return success and email.
            return array(
                "status" => "success",
                "data" => array(
                    "ID" => $cur_user->ID,
                    "email" => $cur_user->user_email
                )
            );
        }
    }

    //$key = md5($_POST['un']);
    //$result = $wpdb -> query( "UPDATE wp_users  SET user_activation_key = '$key' WHERE user_email = '$un' " );