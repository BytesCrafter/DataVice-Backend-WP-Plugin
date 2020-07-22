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

            if( !isset($_POST['UN']) && !isset($_POST['email']) && !isset($_POST['FN']) && !isset($_POST['LN']) && !isset($_POST['gender']) && !isset($_POST['province']) && !isset($_POST['city']) ){
                return rest_ensure_response( 
                    array(
                            "status" => "unknown",
                            "message" => "Please contact your administrator. Verification Unknown!",
                    )
                );
            }

            $user_name = sanitize_user($_POST['UN']);
            $user_email = sanitize_email($_POST['email']);
            $first_name = $_POST['FN'];
            $last_name = $_POST['LN'];


            // $user_id = username_exists( $user_name );
 
            // if ( ! $user_id && false == email_exists( $user_email ) ) {
            //     // $random_password = wp_generate_password( $length = 12, $include_standard_special_chars = false );
            //     $user_id = wp_create_user( $user_name, $user_pass, $user_email );
            // } else {
            //     $random_password = __( 'User already exists.  Password inherited.', 'textdomain' );
            // }
        
           
             $user_result =  DVC_Globals::user_create($user_name,  $user_email);

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
                WHERE user_login = '$user_name' AND user_email = '$user_email' ", OBJECT );
               $user = new WP_User( (int) $result );

               $adt_rp_key = get_password_reset_key( $user );
                // important to sending mail activation
               $user_login = $user->user_login;


            //    $update_usermeta = $wpdb->get_result("UPDATE SET meta_value = $first_name  WHERE meta_key = 'first_name' AND user_id = $result ");
               
            //    $update_usermeta = wp_update_user([
            //     'ID' => $result, // this is the ID of the user you want to update.
            //     'first_name' => 'awd',
            //     'last_name' => 'awdawd',
            //     ]);
                // return $update_usermeta;

                if ( is_wp_error( $adt_rp_key ) ) {
                    return rest_ensure_response( 
                        array(
                                "status" => "unknown",
                                "message" => "Please contact your administrator. User Creation Unknown!",
                        )
                    );

                }else{
                    $send_email = DVC_Signup::send_mail_activation($result, $adt_rp_key);
                    
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

            
        //Sending email for account verification
        public static function send_mail_activation($user_id, $adt_rp_key){
            global $wpdb;
            $now = current_time( 'mysql' ); 

            $later = date( 'Y-m-d H:i:s', strtotime( $now ) + 7200 ); //7200 seconds = 2 hours
           
            $add_key_meta = add_user_meta( $user_id->id, 'key_expiry', $later );
            return $add_key_meta;
        }
    }
?>