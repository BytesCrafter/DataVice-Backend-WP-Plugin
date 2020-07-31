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
			if (!isset($_POST["ak"]) || !isset($_POST["pw"])) {
				return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request Unknown!",
					)
				);
            }

            // Step 2 : Check if username or email is existing.
            if ( empty($_POST['ak']) || empty($_POST['pw']) ) {
                return rest_ensure_response( 
                    array(
                            "status" => "failed",
                            "message" => "Required field cannot be empty.",
                    )
                );
            }

            // Initialize WP global variable
            global $wpdb;

            //TODO: check if ak is existing and password reset key expiration. Check signup class for meta KEY.
            $check = $wpdb->get_results("SELECT ID 
                FROM {$wpdb->prefix}users 
                WHERE user_activation_key = '{$_POST['ak']}'", OBJECT );

            if(!$check)
            {
                return rest_ensure_response( 
                    array(
                            "status" => "failed",
                            "message" => "Password reset key does not belong to any user.",
                    )
                );
            }

            // Get user meta and if empty create expiration supposed to be expired.
            $expiration_date = date( 'Y-m-d H:i:s', strtotime("now") - 1801 );
            $expiry_meta = get_user_meta($check[0]->ID, 'reset_pword_expiry', true);
            $expiry_date = empty($expiry_meta) ? $expiration_date : $expiry_meta;
            if( strtotime($expiry_date) <= time() )
            {
                return rest_ensure_response( 
                    array(
                            "status" => "failed",
                            "message" => "Password reset key is already expired or used.",
                    )
                );
            }

            //Removed expirate date forcing activation key unusable.
            $add_key_meta = update_user_meta( $check[0]->ID, 'reset_pword_expiry', "" );    

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