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
    class DV_Signup {

        public static function listen(){

            // Step 1 : Check if the fields are passed
            if( !isset($_POST['un']) || !isset($_POST['em']) || 
                !isset($_POST['fn']) || !isset($_POST['ln']) || 
                !isset($_POST['gd']) || !isset($_POST['bd']) || 
                !isset($_POST['co']) || !isset($_POST['pr']) || 
                !isset($_POST['ct']) || !isset($_POST['bg']) ){
                return rest_ensure_response( 
                    array(
                            "status" => "unknown",
                            "message" => "Please contact your administrator. Request Unknown!",
                    )
                );
            }

            // Step 2 : Check if username or email is existing.
            if ( empty($_POST['un']) || empty($_POST['em'])
                || empty($_POST['fn']) || empty($_POST['ln'])
                || empty($_POST['gd']) || empty($_POST['bd'])
                || empty($_POST['co']) || empty($_POST['pr']) 
                || empty($_POST['ct']) || empty($_POST['bg']) ) {
                return rest_ensure_response( 
                    array(
                            "status" => "failed",
                            "message" => "Required field cannot be empty.",
                    )
                );
            }

            // Step 2 : Check if username or email is existing.
            if ( username_exists( $_POST['un'] ) ||  email_exists($_POST['em']) ) {
                return rest_ensure_response( 
                    array(
                            "status" => "failed",
                            "message" => "Username or Email already exist.",
                    )
                );
            }

            //TODO: Check gd, bd, co, pr, ct has a proper value.

            // Step 3 : Actual creation of user.
            // Initialize WordPress Core DB.
            global $wpdb;

            // Get user object.
            $user = DV_Signup::catch_post();

            // Try to create a User.
            $created_id = wp_insert_user( $user );

            // Handle user creation events.
            if( !is_wp_error($created_id) ) {

                // Insert Gender etc. 
                $add_key_meta = update_user_meta( $user_id->id, 'gender', $user['gender'] );
                $add_key_meta = update_user_meta( $user_id->id, 'birthday', $user['birthday'] );

                // TODO: Insert Address. 
                // $add_key_meta = update_user_meta( $user_id->id, 'brgy_code', $user['br'] );
                // $add_key_meta = update_user_meta( $user_id->id, 'city_code', $user['ct'] );
                // $add_key_meta = update_user_meta( $user_id->id, 'province_code', $user['pv'] );
                // $add_key_meta = update_user_meta( $user_id->id, 'country', $user['co'] );
                $add_key_meta = update_user_meta( $user_id->id, 'address_home', "ID of address" );                

                // Insert user meta for expiration of current activation_key.
                $expiration_date = date( 'Y-m-d H:i:s', strtotime("now") + 1800 ); // seconds = 30 minutes
                $add_key_meta = update_user_meta( $user_id->id, 'reset_pword_expiry', $expiration_date );

                // Update the user activation key.
                $wpdb->get_row("UPDATE {$wpdb->prefix}users 
                    SET `user_activation_key` = {$user['user_activation_key']} 
                    WHERE `ID` = {$created_id};");
                
                // Try to send mail.
                if( DV_Signup::is_success_sendmail( $user ) ) {
                    return rest_ensure_response( 
                        array(
                                "status" => "success",
                                "message" => "Please check your email for password reset key.",
                        )
                    );
                } else {
                    return rest_ensure_response( 
                        array(
                                "status" => "failed",
                                "message" => "Please contact site administrator. Email not sent!",
                        )
                    );
                }
                
            } else {
                return rest_ensure_response( 
                    array(
                            "status" => "error",
                            "message" => "WordPress Error!",
                    )
                );
            }
        }

        // Return of SignUp user object from POST.
        public static function catch_post()
        {
            $cur_user = array();

            $cur_user['user_login'] = "Research.";
            $cur_user['user_pass'] = wp_generate_password( 49, false, false );
            $cur_user['user_email'] = $_POST['em'];

            $cur_user['user_nicename'] = ""; //user post url
            $cur_user['user_url'] = ""; //referral url

            $cur_user['first_name'] = $_POST['fn'];
            $cur_user['last_name'] = $_POST['ln'];
            $cur_user['display_name'] = $cur_user['first_name'] ." ". $cur_user['last_name'];

            $cur_user['gender'] = $_POST['gd']; //Male, Female
            $cur_user['birthday'] = $_POST['bd']; //Y-m-d

            $cur_user['country'] = $_POST['gd'];
            $cur_user['province'] = $_POST['pv'];
            $cur_user['city'] = $_POST['ct'];
            $cur_user['brgy'] = $_POST['bg'];

            $cur_user['show_admin_bar_front'] = false;
            $cur_user['role'] = "subscriber";
            $cur_user['user_activation_key'] = wp_generate_password( 12, false, false );
            $cur_user['user_registered'] = Date("Y-m-d H:i:s");

            return  $cur_user;
        }

        // Try to Send email for a new verification or activation key.
        public static function is_success_sendmail($user) {
            $message = "Hello " .$user['display_name']. ",";
            $message .= "\n\nWelcome to PasaBuy.App! We're happy that your here.";
            $message .= "\nPassword Reset Key: " .$user['user_activation_key'];
            $message .= "\n\nPasaBuy.App";
            $message .= "\nsupport@pasabuy.app";
            return wp_mail( $user['user_email'], "Bytes Crafter - Forgot Password", $message );
        }

    }
?>