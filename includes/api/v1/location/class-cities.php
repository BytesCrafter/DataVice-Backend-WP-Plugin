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
    class DV_Cities{

        public static function listen(){
            return rest_ensure_response( 
				DV_Cities::get_cities()
			);
        }

        public static function get_cities(){
            

            // Check if country code is passed
			if (!isset($_POST["pc"]) || empty($_POST["pc"])) {
				return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request Unknown!",
					)
				);
            }
            
            $prov_code = $_POST["pc"];

            //Table details creation
            $cty_table = DV_CTY_TABLE;
            $cty_fields = DV_CTY_FIELDS; 
            $where = DV_CTY_WHERE . "'$prov_code'";

            $cities =  DV_Globals::retrieve($cty_table, $cty_fields, $where, 'ORDER BY name', 'ASC');

            if (!$cities) {
                    return array(
                        "status" => "error",
                        "message" => "An error occured while fetching data from the server",
                    );
            }
           
            return array(
                "status" => "success",
                "data" => $cities
            );
            
        }
    
    
    }//end of class