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
    class DV_Provinces{

        public static function listen(){
            return rest_ensure_response( 
				DV_Provinces::get_provinces()
			);
        }

        public static function get_provinces(){
            

             // Step 1 : Check if country code is passed.
            if ( !isset($_POST["cc"]) ) {
				return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request Unknown!",
					)
				);
			}

			// Step 2 : Check if country code is empty.
            if ( empty($_POST['cc']) ) {
                return rest_ensure_response( 
                    array(
                            "status" => "failed",
                            "message" => "Required fields cannot be empty.",
                    )
                );
			}
            
            $country_code = $_POST["cc"];

            //Table details creation
            $prv_table = DV_PRV_TABLE;
            $prv_fields = DV_PRV_FIELDS; 
            $where = DV_PRV_WHERE . "'$country_code'";

            $provinces =  DV_Globals::retrieve($prv_table, $prv_fields, $where, 'ORDER BY name', 'ASC');

            if (!$provinces) {
                    return array(
                        "status" => "error",
                        "message" => "An error occured while fetching data from the server",
                    );
            }
           
            return array(
                "status" => "success",
                "data" => $provinces
            );
            
        }
    
    
    }//end of class