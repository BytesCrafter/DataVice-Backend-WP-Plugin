
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

	class DV_Authenticate {

		//Get the user session token string and if nothing, create and return one.
		public static function get_session( $user_id ) {
			//Grab WP_Session_Token from wordpress.
			$wp_session_token = WP_Session_Tokens::get_instance($user_id);

			/** Token Expiry Function
			 * Returns the value of token_expiry_span in the database
			 * Returns default value if not exists
			 * @param1 = {key};
			 * @param2 = {default value}
			 */
			$token_expiry = DV_Library_Config::dv_get_config('token_expiry_span', 3600);

			//Create a session entry unto the session tokens of user with X expiry.
			$expiration = time() + apply_filters('auth_cookie_expiration', (int)$token_expiry * DAY_IN_SECONDS, $user_id, true);

			// TODO: PENDING! Consider not inserting new session on database. For example, we must check if
			// there is a session with the same device id as we currently use then reused that session.
			// Current issue is, everytime user authenticate, we also create new session for that.
			$session_now = $wp_session_token->create($expiration);

			return $session_now;
		}

		public static function listen() {
			return rest_ensure_response(
				DV_Authenticate::submit()
			);
		}

		// Rest Api routing.
		public static function submit() {
			global $wpdb;
			$users_table = WP_USERS;
			$usermeta_table = WP_USERS_META;
			$events_table = DV_EVENTS_TABLE;
			$events_fields = DV_EVENTS_FIELDS;

			// Check that we're trying to authenticate
			if (!isset($_POST["un"]) || !isset($_POST["pw"])) {
				return array(
					"status" => "unknown",
					"message" => "Please contact your administrator. Request Unknown!",
				);
			}

			// Step 2 : Check if username or password is not empty.
            if ( empty($_POST['un']) || empty($_POST['pw']) ) {
				return array(
					"status" => "failed",
					"message" => "Required fields cannot be empty.",
                );
			}
			$uname = $_POST["un"];
			$pword = $_POST["pw"];


			// Store post variable into vars.

			// Check account if activated or not

				/* $validate_account = $wpdb->get_row("SELECT user_login, user_activation_key FROM $users_table WHERE `user_email` = '$uname' OR `user_login` = '$uname' ");

				if( $validate_account ) {
					if (md5($validate_account->user_login) != $validate_account->user_activation_key) {

						return array(
							"status" => "failed",
							"message" => "Please activate your account first.",
						);

					}
				}
 */
			// End check account if activated or not

			// Check if account is locked due to incorrect login attempts
			$check_account = $wpdb->get_row("SELECT um.meta_value as lock_expiry
					FROM $users_table u
					INNER JOIN $usermeta_table um ON um.user_id = u.id
					WHERE u.`user_login` = '$uname'
					AND um.meta_key = 'lock_expiry_span'");

			if ( $check_account && date('Y-m-d H:i:s', strtotime("now")) <  $check_account->lock_expiry ) {

				//Get remaining time of releasing lock account
				$now = strtotime("now");
				$lock_expiry = strtotime($check_account->lock_expiry);
				$interval  = abs($lock_expiry - $now);
				$time_left = round($interval / 60);

				return array(
					"status" => "failed",
					"message" => "Your account is currently locked. Please wait $time_left minutes before trying again",
                );
			}

			//Initialize wp authentication process.
			$user = wp_authenticate($uname, $pword);
			if ($user->errors !== "") {
				$error_code = array_keys( $user->errors );
			}



			// Check for WordPress authentication issue.
			if ( is_wp_error($user) ) {

				if ( $error_code[0] == "incorrect_password") {

					$get_id = $wpdb->get_row("SELECT `ID` FROM $users_table WHERE user_login = '$uname'");
					$wpdb->query("INSERT INTO $events_table $events_fields VALUES ('$get_id->ID', 'incorrect_password', ' ')");
					$attempts = $wpdb->get_results("SELECT `ID` FROM $events_table WHERE date_created > now() - interval 30 minute AND wpid = $get_id->ID");

					if (count($attempts) >= 3) {

						$lock_expiry_span = DV_Library_Config::dv_get_config('lock_expiry_span', 1800);

						$expiration_date = date( 'Y-m-d H:i:s', strtotime("now") + (int)$lock_expiry_span );

						$add_key_meta = update_user_meta( $get_id->ID, 'lock_expiry_span', $expiration_date );

						return array(
							"status" => "error",
							"message" => "Your account has been locked due to multiple failed login attempts.",
						);

					}

				}

				return array(
					"status" => "error",
					"message" => $user->get_error_message(),
				);

			}

			// Return User ID and Session KEY as success data.
			return array(
				"status" => "success",
				"data" => array(
					"wpid" => $user->ID,
					"snky" => DV_Authenticate::get_session($user->ID),
				)
			);
		}
	}