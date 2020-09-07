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

    class DV_Activate_Account{

        public static function listen(){
            return rest_ensure_response(
				self::listen_open()
			);
        }

        public static function listen_open(){
            global $wpdb;


                if (!isset($_POST['ak']) || !isset($_POST['un'])) {
                    return array(
                        "status" => "failed",
                        "message" => "Required fields cannot be empty.",
                    );
                }

                if (empty($_POST['ak']) || empty($_POST['un'])) {
                    return array(
                        "status" => "failed",
                        "message" => "Required fields cannot be empty.",
                    );
                }

                // Check if user input is email or username
                if (is_email($_POST['un'])) {

                    // Sanitize email
                    $email = sanitize_email($_POST['un']);

                    // If email, use email in where clause
                    $cur_user = $wpdb->get_row("SELECT ID, display_name, user_email
                        FROM {$wpdb->prefix}users
                        WHERE user_email = '$email'
                        AND `user_activation_key` = '{$_POST['ak']}'", OBJECT );

                } else {

                    //Sanitize username
                    $uname = sanitize_user($_POST['un']);

                    // if username, use username in where clause
                    $cur_user = $wpdb->get_row("SELECT ID, display_name, user_email
                        FROM {$wpdb->prefix}users
                        WHERE user_login = '$uname'
                        AND `user_activation_key` = '{$_POST['ak']}'", OBJECT );
                }

                // Check for cur_user. Return a message if null
                if ( !$cur_user ) {
                    return array(
                        "status" => "failed",
                        "message" => "Password reset key and username/email is invalid!",
                    );
                }

                $expiry_meta = get_user_meta($cur_user->ID, 'reset_pword_expiry', true);

                // Check if password reset key is used.
                if( empty($expiry_meta) ) {
                    return array(
                        "status" => "failed",
                        "message" => "Password reset key is already used.",
                    );
                }


                // Check if activation key is expired.
                if( time() >= strtotime($expiry_meta) )
                {
                    return array(
                        "status" => "failed",
                        "message" => "Password reset key is already expired.",
                    );
                }
                 $key = DV_Globals::old_tiger(true);

                if (is_email($_POST['un'])) {

                    $un = $_POST['un'];
                    $result = $wpdb -> query( "UPDATE wp_users  SET user_activation_key = '$key' WHERE user_email = '$un' " );

                }else{

                    $un = $_POST['un'];
                    $result = $wpdb -> query( "UPDATE wp_users  SET user_activation_key = '$key' WHERE user_login = '$un' " );


                }

                // Check if row successfully updated or not
                if (!$result) {
                    return array(
                        "status" => "failed",
                        "message" => "An error occured while submitting data to server."
                    );
                }

                return array(
                    "status" => "success",
                    "key" => $key
                );
        }
    }