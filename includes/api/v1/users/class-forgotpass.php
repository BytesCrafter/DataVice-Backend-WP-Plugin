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
?>
<?php
	class DV_Forgotpassword {

        // REST API for Forgotten Passwords
		public static function initialize() {

            // Initialize WP global variable
            global $wpdb;

            // Step 1: Check if UN field is passed
			if (!isset($_POST["UN"])) {
				return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request Unknown!",
					)
				);
            }

            // Step 2: Check if user input is email or username
            if (is_email($_POST['UN'])) {

                //Sanitize email
                $email = sanitize_email($_POST['UN']);

                // if email, use email in where clause
                $result = $wpdb->get_row("SELECT id, user_login, display_name, user_email
                    FROM {$wpdb->prefix}users 
                    WHERE user_email = '$email'", OBJECT );

            } else {

                //Sanitize username
                $username = sanitize_user($_POST['UN']);

                // if username, use username in where clause
                $result = $wpdb->get_row("SELECT id, user_login, display_name, user_email
                    FROM {$wpdb->prefix}users 
                    WHERE user_login = '$username'", OBJECT );
            }
            
            
            // Step 3: Check for results. Return a message if null
            if (!$result) {
                return rest_ensure_response( 
					array(
						"status" => "failed",
						"message" => "Email or username doesn't exists!",
					)
				);
            } 

            if (DV_Forgotpassword::send_mail_success() == false) {
                return rest_ensure_response( 
					array(
						"status" => "failed",
						"message" => "Failed to send an email!",
					)
				);
            } 
             
            return rest_ensure_response( 
                array(
                    "status" => "success",
                    "message" => "An email has been sent to your email address.",
					
                )
            );

        }

        
        //Sending email for lost password verification
        public static function send_mail_success(){
            //Temporary. Returns true only
            return false;
        }



	}

?>