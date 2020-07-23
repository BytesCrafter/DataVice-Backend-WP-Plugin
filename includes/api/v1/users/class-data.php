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
	class DVC_Userdata{

        
        // REST API for getting the user data
        public static function initialize(){


                //User verification
                $verified = DVC_Verification::initialize();

                // Pass the ID in a variable
                 $user_id = $verified['wpid'];

                //Get user data and pass it to $wp_user
                 $wp_user = get_user_by("ID", $user_id);
                 
                // Check if $wp_user has value
                 if ($wp_user == false) {
                        return rest_ensure_response( 
                                array(
                                        "status" => "failed",
                                        "message" => "Please contact your administrator. User Not Found!"
                                )
                        );
                 }
                
                 // Return success status and complete object.
                 return rest_ensure_response( 
                        array(
                                "status" => "success",
                                "data" => array(
                                        "uname" => $wp_user->data->user_nicename,
                                        "dname" => $wp_user->data->display_name,
                                        "email" => $wp_user->data->user_email,
                                        "ro" => $wp_user->roles,
                                        "fn" => $wp_user->first_name,
                                        "ln" => $wp_user->last_name,
                                        "av" => 'avatar'
                                )
                        )
                );
        
        }

        

	}

?>