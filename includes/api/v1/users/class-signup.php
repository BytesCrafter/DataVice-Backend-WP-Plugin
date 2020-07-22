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
    class DVC_Signup{
        public static function initialize(){

            // Initialize WP global variable
            global $wpdb;

            // Step1 : check of fields are past
            if( !isset($_POST['UN']) && !isset($_POST['email']) && !isset($_POST['FN']) && !isset($_POST['LN']) && !isset($_POST['gender']) && !isset($_POST['province']) && !isset($_POST['city']) ){
                return rest_ensure_response( 
                    array(
                            "status" => "unknown",
                            "message" => "Please contact your administrator. Verification Unknown!",
                    )
                );
                
            }

            // Sanitaion
            $username = sanitize_user($_POST['UN']);

            $user_email = sanitize_email($_POST['email']);

            $user_meta = array(
                'first_name' => $_POST['FN'],
                'last_name' => $_POST['LN'],
            );


            if ( username_exists( $username ) ) {
                return rest_ensure_response( 
                    array(
                            "status" => "unknown",
                            "message" => "Username Already Exists",
                    )
                );

            } else {
                $user_result =  DVC_Globals::user_create($username,  $user_email);

                if ($user_result == false) {
    
                    return rest_ensure_response( 
                        array(
                                "status" => "unknown",
                                "message" => "Please contact your administrator. User Creation Unknown!",
                        )
                    );
    
                }else{
    
                    global $wpdb;
    
                    $result = $wpdb->get_row("SELECT id
                        FROM {$wpdb->prefix}users 
                        WHERE user_login = '$username' AND user_email = '$user_email' ", OBJECT );

                    $user = new WP_User( (int) $result->id );

                    foreach ($user_meta as $key => $value) {

                        $update_meta = update_user_meta($result->id, $key, $value  );
                       
                    }


                    $adt_rp_key = get_password_reset_key( $user );

                    // important to sending mail activation
                    $user_login = $user->user_login;
    
                    if ( is_wp_error( $adt_rp_key ) ) {
                        return rest_ensure_response( 
                            array(
                                    "status" => "unknown",
                                    "message" => "Please contact your administrator. User Authentication key Creation Unknown!",
                            )
                        );

                    }else{
                        $send_email = DVC_Signup::send_mail_activation($result, $adt_rp_key, $user_login);
                        
                        if ($send_email == false) {
                            return rest_ensure_response( 
                                array(
                                        "status" => "unknown",
                                        "message" => "Please contact your administrator. User Creation Unknown!",
                                )
                            );

                        }else{
                            return rest_ensure_response( 
                                array(
                                        "status" => "Success",
                                        "message" => "Please check your email account for activation.",
                                )
                            );

                        }

                    }
                   
                }    
                
            }


           
            
        }

            
        //Sending email for account verification
        public static function send_mail_activation($user_id, $adt_rp_key, $user_login){
            global $wpdb;

            $now = current_time( 'mysql' ); 

            $later = date( 'Y-m-d H:i:s', strtotime( $now ) + 7200 ); //7200 seconds = 2 hours
           
            $add_key_meta = add_user_meta( $user_id->id, 'key_expiry', $later );
            
            if ($add_key_meta < 0 ) {

                return false;

            }else{

                return true;

            }

        }
    }
?>