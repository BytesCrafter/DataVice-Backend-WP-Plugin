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
    class DV_Signup{
        public static function initialize(){

            // Initialize WP global variable
            global $wpdb;

            // Step1 : check of fields are past
            if( !isset($_POST['un']) && !isset($_POST['em']) && !isset($_POST['fn']) && !isset($_POST['ln']) && !isset($_POST['gd']) && !isset($_POST['pr']) && !isset($_POST['ct']) ){
                return rest_ensure_response( 
                    array(
                            "status" => "unknown",
                            "message" => "Please contact your administrator. Verification Unknown!",
                    )
                );
                
            }

            // Sanitaion
            $username = sanitize_user($_POST['un']);

            $user_email = sanitize_email($_POST['em']);

            $user_meta = array(
                'first_name' => $_POST['fn'],
                'last_name' => $_POST['ln'],
            );

            $gender = $_POST['gd'];

            // step1 : check if user exist
            if ( username_exists( $username ) ) {
                return rest_ensure_response( 
                    array(
                            "status" => "unknown",
                            "message" => "Username Already Exists",
                    )
                );

            } else {
                // step2 : check call user create function from global
                $user_result =  DV_Globals::user_create($username,  $user_email);

                // step3 : check if user creation false
                if ($user_result == false) {
    
                    return rest_ensure_response( 
                        array(
                                "status" => "unknown",
                                "message" => "Please contact your administrator. User Creation Unknown!",
                        )
                    );
                    
                }else{

		        	// Initialize WP global variable
                    global $wpdb;
                    
                   // step4 : Fetch id from wp_users table
                    $result = $wpdb->get_row("SELECT id
                        FROM {$wpdb->prefix}users 
                        WHERE user_login = '$username' AND user_email = '$user_email' ", OBJECT );
                    
                   // step5 : Create WP User data 
                    $user = new WP_User( (int) $result->id );

                    // step6 : Update user_meta table  
                    foreach ($user_meta as $key => $value) {

                        $update_meta = update_user_meta($result->id, $key, $value  );
                       
                    }
                    
                    $add_key_meta_gender = add_user_meta( $result->id, 'gender', $gender );


                    // step7 : Create password rest key for User Account Activation   
                    $adt_rp_key = get_password_reset_key( $user );

                    // important to sending mail activation
                    $user_login = $user->user_login;
                    
                    // step8 : Check if Account Activation key is False   
                    if ( is_wp_error( $adt_rp_key ) ) {
                        return rest_ensure_response( 
                            array(
                                    "status" => "unknown",
                                    "message" => "Please contact your administrator. User Authentication key Creation Unknown!",
                            )
                        );

                    }else{
                    
                         // step9 : Activate send mail function   
                        $send_email = DV_Signup::send_mail_activation($result, $adt_rp_key, $user_login);
                        
                         // step9 : Fetch if send mail function come true   
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