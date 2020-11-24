
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

	class DV_Send_Activation_key {

        public static function listen() {
            return rest_ensure_response(
				self::listen_open()
			);
        }



        public static function listen_open(){
            global $wpdb;

            $activation_key = DV_Globals::activation_key();


            $user_login = $_POST['un'];
            $unames = explode("@", $user_login);
            $user_login = $unames[0]."-".crc32($unames[1]);

            $get_wpid = $wpdb->get_row("SELECT ID FROM  {$wpdb->prefix}users  WHERE user_login = '$user_login' ");

            if (empty($get_wpid)) {
                return array(
                    "status" => "failed",
                    "message" => "This user does not exists."
                );
            }

            // Update action key of user in database
                $import = wp_update_user( array( 'ID' => $get_wpid->ID, 'user_activation_key' => $activation_key ) );
            // End


            $message = "Hello " .$user['display_name']. ",";
            $message .= "\n\nWelcome to ".get_bloginfo('name')."! We're happy that your here.";
            $message .= "\nPassword Activation Key: " .$activation_key;
            $message .= "\n\n".get_bloginfo('name');
            $message .= "\n".get_bloginfo('admin_email');
            $pasabuy = EMAIL_HEADER;
            $subject = EMAIL_HEADER_SUBJECT_ACTIVATE;

            $mail = wp_mail( $user_login, $pasabuy." - ".$subject, $message );
            if (is_wp_error($mail)) {
                return array(
                    "status" => "failed",
                    "message" => "Please contact your administrator. email not sent."
                );
            }else{
                return array(
                    "status" => "success",
                    "message" => "Please check your email for password reset key."
                );
            }
        }
    }