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

    class DV_Verify_Account{

        public static function listen(){
            return rest_ensure_response(
				self::listen_open()
			);
        }

        public static function listen_open(){
            global $wpdb;

            if (isset($_POST['ak']) && isset($_POST['npas']) && isset($_POST['cpas']) && !isset($_POST['un'])) {

                if (empty($_POST['ak']) || empty($_POST['npas']) || empty($_POST['cpas'])) {
                    return array(
                        "status" => "failed",
                        "message" => "Required fields cannot be empty.",
                    );
                }

                if ($_POST['cpas'] !== $_POST['npas']) {
                    return array(
                        "status" => "failed",
                        "message" => "Password does not match.",
                    );
                }

                $actkey = md5($_POST['ak']);
                $cur_user = $wpdb->get_row("SELECT ID, display_name, user_email, user_login
                    FROM {$wpdb->prefix}users
                    WHERE `user_activation_key` = '{$actkey}'", OBJECT );

                if ( !$cur_user ) {
                    return rest_ensure_response(
                        array(
                            "status" => "failed",
                            "message" => "Password reset key and username/email is invalid!",
                        )
                    );
                }

                // Hash the new password
                $pword_hash = wp_hash_password($_POST['cpas']);

                // Update users activation key.
                // $result = $wpdb->update( $wpdb->users, array( 'user_pass' => $pword_hash  ), array( 'user_activation_key' => $_POST['ak'] ) );
                $result = $wpdb->update( $wpdb->users, array( 'user_pass' => $pword_hash  ), array( 'user_activation_key' => md5($cur_user->user_login) ) );

                // Check if row successfully updated or not
                if (!$result) {
                    return array(
                        "status" => "failed",
                        "message" => "Password was not change"
                    );
                }

                //Removed expirate date forcing activation key unusable.
                $add_key_meta = update_user_meta( $cur_user->ID, 'reset_pword_expiry', "" );

                // If success, return status and message
                return array(
                    "status" => "success",
                    "message" => "Password updated successfully!"
                );
            }

            // when activating after registration

            if (!isset($_POST['ak']) || !isset($_POST['npas']) || !isset($_POST['cpas']) || !isset($_POST['un'])) {
                return array(
                    "status" => "unknown",
                    "message" => "Please contact your administrator. Request unknown.",
                );
            }

            if (empty($_POST['ak']) || empty($_POST['npas']) || empty($_POST['cpas']) || empty($_POST['un'])) {
                return array(
                    "status" => "failed",
                    "message" => "Required fields cannot be empty.",
                );
            }

            if ($_POST['cpas'] !== $_POST['npas']) {
                return array(
                    "status" => "failed",
                    "message" => "Password does not match.",
                );
            }

            if (is_email($_POST['un'])) {

                // Sanitize email
                $email = sanitize_email($_POST['un']);

                $cur_user = $wpdb->get_row("SELECT ID, display_name, user_email, user_login
                FROM {$wpdb->prefix}users
                WHERE user_email = '{$_POST['un']}'
                AND `user_activation_key` = '{$_POST['ak']}'", OBJECT );

            } else {

                //Sanitize username
                $uname = sanitize_user($_POST['un']);

                $cur_user = $wpdb->get_row("SELECT ID, display_name, user_email, user_login
                FROM {$wpdb->prefix}users
                WHERE user_login = '{$_POST['un']}'
                AND `user_activation_key` = '{$_POST['ak']}'", OBJECT );

            }

            if ( !$cur_user ) {
                return rest_ensure_response(
					array(
						"status" => "failed",
						"message" => "Password reset key and username/email is invalid!",
					)
				);
            }

            // Hash the new password
            $pword_hash = wp_hash_password($_POST['cpas']);

            $status = md5($cur_user->user_login);

            // Update users activation key.

            if (is_email($_POST['un'])) {
                $result = $wpdb->update(
                    $wpdb->users,array(
                        'user_pass' => $pword_hash,
                        'user_activation_key' => $status
                    ),
                    array( 'user_email' => $_POST['un'] )
                );
            }else{
                $result = $wpdb->update(
                    $wpdb->users,array(
                        'user_pass' => $pword_hash,
                        'user_activation_key' => $status
                    ),
                    array( 'user_login' => $_POST['un'] )
                );
            }


            // Check if row successfully updated or not
            if (!$result) {
                return array(
                    "status" => "failed",
                    "message" => "Password was not change"
                );
            }

            //Removed expirate date forcing activation key unusable.
            $add_key_meta = update_user_meta( $cur_user->ID, 'reset_pword_expiry', "" );

            // If success, return status and message
            return array(
                "status" => "success",
                "message" => "Password updated successfully!"
            );
        }
    }