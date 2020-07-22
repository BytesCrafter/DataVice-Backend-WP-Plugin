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
	class DVC_Changepassword {


        // REST API for creating new password
        public static function initialize(){
            
            // Initialize WP global variable
            global $wpdb;

            // Step 1: Check if key and new password is passed
			if (!isset($_POST["key"]) || !isset($_POST["PW"])) {
				return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request Unknown!",
					)
				);
            }

            // Pass to variables
            $key = $_POST['key'];
            $new_pass = $_POST['PW'];

            // Hash the new password
            $hash = wp_hash_password($new_pass);

            // Update users tabe
            $result = $wpdb->update(
                $wpdb->users,array(
                    'user_pass' => $hash
                ),
                array( 'user_activation_key' => $key )
            );

            // Check if row successfully updated or not
            if (!$result) {
                return rest_ensure_response(
                    array(
                        "status" => "failed",
                        "message" => "Invalid key!"
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