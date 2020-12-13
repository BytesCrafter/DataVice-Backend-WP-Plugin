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

	class DV_Stats{

                public static function listen(){
                        return rest_ensure_response( 
                            DV_Stats:: get_stats()
                        ); 
                }

                // REST API for getting the user data
                public static function get_stats(){
                        
                        //User validation
                        if (DV_Verification::is_verified() == false) {
				            return rest_ensure_response(
                                array(
                                    "status" => "unknown",
                                    "message" => "Please contact your administrator. Request unknown!"
                                )
                            );
			        
                        }

                        // Find user in db using wpid
                        $wp_user = get_userdata($_POST['wpid']);

                        if (!$wp_user) {
                                return array(
                                        "status" => "unknown",
                                        "message" => "Please contact your administrator. User Not Found!",
                                );
                        }

                        //TODO : Real data. 
                        $reviews['total'] = 123;
                        $reviews['ratings'] = 4.6;

                        // Return success status and complete object.
                        return  array("status" => 'success',
                                        "data" => array(
                                                "posts" => 56,
                                                "transacts" => 15,
                                                "reviews" => $reviews
                                                
                                )
                        );

                
                }// End of function initialize()

	} // End of class