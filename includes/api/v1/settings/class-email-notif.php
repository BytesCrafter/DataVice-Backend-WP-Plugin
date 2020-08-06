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
    class DV_Notification_email{
        public static function listen(){

            if ( DV_Verification::is_verified() == false ) {
                return rest_ensure_response( 
                    array(
                        "status" => "unknown",
                        "message" => "Please contact your administrator. Request Unknown!",
                    )
                );
            }
            
              // Check if ID is in valid format (integer)
			if (!is_numeric($_POST["wpid"]) || !is_numeric($_POST["email_notif"])  ) {
				return array(
						"status" => "failed",
						"message" => "Please contact your administrator. ID not in valid format!",
                );
                
            }
            
            if (!isset($_POST['email_notif'])) {
                return array(
                    "status" => "failed",
                    "message" => "Please contact your administrator. ID not in valid format!",
                );
            }
            
            if (is_null($_POST['email_notif'])) {
                return array(
                    "status" => "failed",
                    "message" => "Please contact your administrator. ID not in valid format!",
                );
            }

            $wpid = $_POST['wpid'];
            $notif = $_POST['email_notif'];
            
            $result = update_user_meta( $wpid, 'email_notification', $notif);
            
            if ($result == false) {
                return false;

            }else{

                return true;
            
            }
        }
    }