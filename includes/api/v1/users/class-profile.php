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
	class DV_Userprofile{

                public static function listen(){
                        return rest_ensure_response( 
                                DV_Userprofile:: get_profile()
                        ); 
                }

                // REST API for getting the user data
                public static function get_profile(){

                        //User validation
                        if (DV_Verification::is_verified() == false) {
				return array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request Unknown!",
                                );
			        
                        }

                        // Find user in db using wpid
                        $wp_user = get_user_by("ID", $_POST['wpid']);

                        if (!$wp_user) {
                                return array(
                                        "status" => "unknown",
                                        "message" => "Please contact your administrator. User Not Found!",
                                );
                        }
                        
                        // Return success status and complete object.
                        return  array("status" => 'success',
                                        "data" => array(
                                                "uname" => $wp_user->data->user_nicename,
                                                "dname" => $wp_user->data->display_name,
                                                "email" => $wp_user->data->user_email,
                                                "roles" => $wp_user->roles,
                                                "fname" => $wp_user->first_name,
                                                "lname" => $wp_user->last_name,
                                                "avatar" => $wp_user->avatar,
                                                "banner" => $wp_user->banner
                                )
                        );

                
                }// End of function initialize()

	} // End of class

?>