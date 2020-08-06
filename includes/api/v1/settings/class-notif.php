<?php
	// Exit if accessed directly
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	/** 
        * @package datavice-wp-plugin
		* @version 0.1.0
		* This is the primary gateway of all the rest api request.
	*/
?>
<?php
    class DV_Notification{

        public static function listen() {
			return rest_ensure_response( 
				DV_Notification::switch_notif()
			);
        }
        
        public static function switch_notif(){

            // Step 1: validate user
            if ( DV_Verification::is_verified() == false ) {
                return rest_ensure_response( 
                    array(
                        "status" => "unknown",
                        "message" => "Please contact your administrator. Request Unknown!",
                    )
                );
            }
            
            // Step 2: Sanitize and validate all requests
			if (!is_numeric($_POST["wpid"]) || !is_numeric($_POST["nf"])  ) {
				return array(
						"status" => "failed",
						"message" => "Please contact your administrator. Request Unknown!",
                );
                
            }
            
            //Check if parameter is passed
            if (!isset($_POST['nf'])) {
                return array(
                    "status" => "failed",
                    "message" => "Please contact your administrator. Request Unknown!",
                );
            }
            
             //Check if parameter has value
            if (is_null($_POST['nf'])) {
                return array(
                    "status" => "failed",
                    "message" => "Parameters not found",
                );
            }

            //Store in variable
            $wpid = $_POST['wpid'];
            $notif = $_POST['nf'];
            
            //Update user meta based on the request value
            $result = update_user_meta( $wpid, 'notification', $notif);

            //return a success message
            return array(
                "status" => "success",
                "message" => "Data has been updated successfully.",
            );
            
        }
    }