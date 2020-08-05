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
               
             //Step 1: Validate and sanitize request
            if ( !isset($_POST["cc"]) || !isset($_POST["mk"]) ) {
				return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request Unknown!",
					)
				);
			}

			// Check if value passed is not null
            if ( empty($_POST['cc']) || empty($_POST['mk'])  ) {
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
            if (!((int)$master_key === (int)$_POST['mk'])) {
                return  array(
                    "status" => "error",
                    "message" => "Master keys does not match.",
                );
            }
            
            $country_code = $_POST["cc"];

           // Step 3: Pass constants to variables and catch post values 
            $prv_table = DV_PROVINCE_TABLE;
            $prv_fields = DV_PROVINCE_FIELDS; 
            $where = DV_PROVINCE_WHERE . "'$country_code'";

            // Step 4: Start query
            $provinces =  DV_Globals::retrieve($prv_table, $prv_fields, $where, 'ORDER BY name', 'ASC');

            // Step 5: Check if no rows found
            if (!$provinces) {
                    return array(
                        "status" => "failed",
                        "message" => "No results found",
                    );
            }
            
            // Return a success message and complete object
            return array(
                "status" => "success",
                "data" => $provinces
            );
            
        }
    
    
    }//end of class