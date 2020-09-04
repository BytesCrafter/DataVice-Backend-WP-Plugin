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
    
    class DV_Edit_Profile{

        //REST API Call
        public static function listen(){
            return rest_ensure_response( 
                self::listen_open()
            );
        }
        
        public static function listen_open(){
            global $wpdb;

            if ( DV_Verification::is_verified() == false ) {
                return array(
                    "status" => "unknown",
                    "message" => "Please contact your administrator. Verification Issue!",
                );
            }

            if (!isset($_POST['fn']) || !isset($_POST['ln']) || !isset($_POST['dpn']) ) {
                return array(
                    "status" => "unknown",
                    "message" => "Please contact your administrator. Request unknown."
                );
            }

            $display_name = $_POST['dpn'];
            $user_id = $_POST['wpid'];
            $user_fn = $_POST['fn'];
            $user_ln = $_POST['ln'];

            $user_data_dn = wp_update_user( array( 'ID' => $user_id, 'display_name' => $display_name ) );

            $user_data_fn = update_user_meta( $user_id, 'first_name', $user_fn,  );
            
            $user_data_ln = update_user_meta( $user_id, 'last_name', $user_ln,  );

            if ( is_wp_error( $user_data_dn ) || is_wp_error( $user_data_fn ) || is_wp_error($user_data_ln) ) {
                // There was an error; possibly this user doesn't exist.
                return array(
                    "status" => "failed",
                    "message" => "Please contact your administrator. Updating failed"
                );
            }else{
                return array(
                    "status" => "success",
                    "message" => "Data has been updated successfully"
                );
            }
        }
    }