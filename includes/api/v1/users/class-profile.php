<?php
	if ( ! defined( 'ABSPATH' ) ) 
	{
	        exit;
	}

	/** 
         * @package datavice-wp-plugin
         * @version 0.1.0
         * @author BytesCrafter
         * @date 21 Nov 2020
	 */

	class DV_Userprofile{

                public static function listen(){

                        //User validation
                        if (DV_Verification::is_verified() == false) {
				return array(
					"status" => "unknown",
					"message" => "Please contact your administrator. Request Unknown!",
                                );
                        }

                        return rest_ensure_response( 
                                DV_Userprofile::get_profile($_POST['wpid'])
                        ); 

                }

                // REST API for getting the user data
                public static function get_profile($wpid){

                        // Find user in db using wpid
                        $wp_user = get_userdata($wpid);

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
                                                "email" => $wp_user->data->user_email,
                                                "avatar" => $wp_user->avatar == null ? DV_Globals::get_default_avatar() : $wp_user->avatar,
                                                "banner" => $wp_user->banner == null ? DV_Globals::get_default_banner() : $wp_user->banner,
                                                "dname" => $wp_user->data->display_name,
                                                "about" => $wp_user->description,
                                                "fname" => $wp_user->first_name,
                                                "lname" => $wp_user->last_name,
                                                "roles" => $wp_user->roles,
                                                "phone" => get_user_meta($wpid, 'datavice_phone', true),
                                )
                        );

                
                }// End of function initialize()

	} // End of class
