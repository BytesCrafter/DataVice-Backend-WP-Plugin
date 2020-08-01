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
	class DV_Reset {

        // REST API for creating new password
        public static function listen(){
            
            // Step 1: Check if key and new password is passed
			if (!isset($_POST["ak"]) || !isset($_POST["un"]) || !isset($_POST["pw"])) {
				return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request Unknown!",
					)
				);
            }

            // Step 2 : Check if username or email is existing.
            if ( empty($_POST['ak']) || empty($_POST['un']) || empty($_POST['pw']) ) {
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
            
            // Step 3: Check for cur_user. Return a message if null
            if ( !$cur_user ) {
                return rest_ensure_response( 
					array(
						"status" => "failed",
						"message" => "Password reset key and username is invalid!",
					)
				);
            }

            // Get user meta of current password reset expiration.
            $expiry_meta = get_user_meta($cur_user->ID, 'reset_pword_expiry', true);

            // Check if password reset key is used.
            if( empty($expiry_meta) ) {
                return rest_ensure_response( 
                    array(
                            "status" => "failed",
                            "message" => "Password reset key is already used.",
                    )
                );
            }

            // Check if activation key is expired.
            if( time() >= strtotime($expiry_meta) )
            {
                return rest_ensure_response( 
                    array(
                            "status" => "failed",
                            "message" => "Password reset key is already expired.",
                    )
                );
            }

            // Hash the new password
            $pword_hash = wp_hash_password($_POST['pw']);

            // Update users activation key.
            $result = $wpdb->update(
                $wpdb->users,array(
                    'user_pass' => $pword_hash
                ),
                array( 'user_activation_key' => $_POST['ak'] )
            );

            // Check if row successfully updated or not
            if (!$result) {
                return rest_ensure_response(
                    array(
                        "status" => "failed",
                        "message" => "Password was not change successfully!"
                    )
                );
            }

            //Removed expirate date forcing activation key unusable.
            $add_key_meta = update_user_meta( $cur_user->ID, 'reset_pword_expiry', "" );   

            // If success, return status and message
            return rest_ensure_response(
                array(
                    "status" => "success",
                    "message" => "Password updated successfully!"
                )
            );

        }

	}

?>