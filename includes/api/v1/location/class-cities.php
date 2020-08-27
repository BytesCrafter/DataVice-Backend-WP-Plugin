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
            
             //Step 1: Validate and sanitize request
             if ( !isset($_POST["prov_code"]) || !isset($_POST["mkey"]) ) {
				return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request Unknown!",
					)
				);
			}

			// Check if value passed is not null
            if ( empty($_POST['prov_code']) || empty($_POST['mkey'])  ) {
                return rest_ensure_response( 
                    array(
                            "status" => "failed",
                            "message" => "Required fields cannot be empty.",
                    )
                );
            }
            
            //Step 2: Master key validation
            //Get master key from database
            $master_key = DV_Library_Config::dv_get_config('master_key', 123);
            
            //Check if master key matches
            if ($master_key !== $_POST['mkey']) {
                return  array(
                    "status" => "error",
                    "message" => "Master key does not match.",
                );
            }

            // Step 3: Pass constants to variables and catch post values 
            $prov_code = $_POST["prov_code"];
            $cty_table = DV_CITY_TABLE;
            $cty_fields = DV_CITY_FIELDS; 
            $where = DV_CITY_WHERE . "'$prov_code'";

            // Step 4: Start query
            $cities =  DV_Globals::retrieve($cty_table, $cty_fields, $where, 'ORDER BY name', 'ASC');

            // Step 5: Check if no rows found
            if (!$cities) {
                    return array(
                        "status" => "error",
                        "message" => "An error occured while fetching data from the server",
                    );
            }
            
            // Return a success message and complete object
            return array(
                "status" => "success",
                "data" => $cities
            );
            
        }
    
    }//end of class