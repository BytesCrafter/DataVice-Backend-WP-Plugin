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

	class DV_Forgot {

        // REST API for Forgotten Passwords
		public static function listen() {
            // Step 1: Check if UN field is passed
			if (!isset($_POST["un"])) {
				return rest_ensure_response(
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request Unknown!",
					)
				);
            }

            // Step 2 : Check if username or email is not empty.
            if ( empty($_POST['un']) ) {
                return rest_ensure_response(
                    array(
                            "status" => "failed",
                            "message" => "Required field cannot be empty.",
                    )
                );
            }

            // Initialize WP global variable
            global $wpdb;

            // Step 2: Check if user input is email or username
            if (is_email($_POST['un'])) {

                // Sanitize email
                $email = sanitize_email($_POST['un']);

                // If email, use email in where clause
                $cur_user = $wpdb->get_row("SELECT ID, display_name, user_email, user_activation_key
                    FROM {$wpdb->prefix}users
                    WHERE user_login = '$email'", OBJECT );

            }
            // else {

            //     //Sanitize username
            //     $uname = sanitize_user($_POST['un']);

            //     // if username, use username in where clause
            //     $cur_user = $wpdb->get_row("SELECT ID, display_name, user_email,user_activation_key
            //         FROM {$wpdb->prefix}users
            //         WHERE user_login = '$uname'", OBJECT );
            // }

            // Step 3: Check for cur_user. Return a message if null
            if ( !$cur_user ) {
                return rest_ensure_response(
					array(
						"status" => "failed",
						"message" => "Email or username doesn't exists!",
					)
				);
            }

            // Step 4: Check if existing reset email is ongoing and not expired yet.
            $expiry_meta = get_user_meta($cur_user->ID, 'reset_pword_expiry', true);
            if( !empty($expiry_meta) ) {
                if( time() <= strtotime($expiry_meta) )
                {
                    return rest_ensure_response(
                        array(
                                "status" => "failed",
                                "message" => "Password reset is ongoing. Wait for 30 minutes then try again.",
                        )
                    );
                }
            }

            /** Getting the length of password reset key
			 * Returns the value of the length of password reset key from the database
			 * Returns default value if not exists
			 * @param1 = {key};
			 * @param2 = {default value}
			 */

            //$cur_user->activation_key = wp_generate_password( $pword_resetkey_length, false, false );
            $cur_user->activation_key = DV_Globals::activation_key();
            $smp = md5( $cur_user->activation_key );

            // Set the new activation key.
            $inserted_key = $wpdb->query("UPDATE {$wpdb->prefix}users
                SET `user_activation_key` = '{$smp}'
                WHERE `ID` = '{$cur_user->ID}';");

            // Check if we successfully inserted password reset key.
            if( !$inserted_key ) {
                return rest_ensure_response(
                    array(
                            "status" => "failed",
                            "message" => "Password reset key failed to updated.",
                    )
                );
            }

            /** Getting the value of password reset expiration time
			 * Returns the value of the length of password reset expiration time from the database
			 * Returns default value if not exists
			 * @param1 = {key};
			 * @param2 = {default value}
			 */
            $pword_expiry_span = DV_Library_Config::dv_get_config('pword_expiry_span', 1800);

            $expiration_date = date( 'Y-m-d H:i:s', strtotime("now") + (int)$pword_expiry_span );

            $add_key_meta = update_user_meta( $cur_user->ID, 'reset_pword_expiry', $expiration_date );

            if (DV_Forgot::is_success_sendmail($cur_user) == false) {
                return rest_ensure_response(
					array(
						"status" => "failed",
						"message" => "Please contact site administrator. Email not sent!",
					)
				);
            } else {
                return rest_ensure_response(
                    array(
                        "status" => "success",
                        "message" => "An email has been sent to your email address.",

                    )
                );
            }
        }

        // Try to Send email for a new verification or activation key.
        public static function is_success_sendmail($user) {
            // TODO: PENDING! Put this on config (HTML SOURCE). Need a research about this.
            // TODO: PENDING! We get the value by a global function named, dv_get_config('key') which return value.
            // TODO: PENDING! We set the value by a global function named, dv_get_config('key', {value}) which bool.
            $message = "Hello " .$user->display_name. ",";
            $message .= "\n\nThere was a request to change your password!";
            $message .= "\nPassword Reset Key: " .$user->activation_key;
            $message .= "\n\nIf did not make this request, just ignore this email.";
            $message .= "\n\n".get_bloginfo('name');
            $message .= "\n".get_bloginfo('admin_email');
            $pasabuy = EMAIL_HEADER;
            $subject = EMAIL_HEADER_SUBJECT_FORGOT;
            return wp_mail( $user->user_email, $pasabuy." - ".$subject, $message );
        }

	}