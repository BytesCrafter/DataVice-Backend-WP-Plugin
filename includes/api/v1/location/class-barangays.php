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
    
    class DV_Barangays{

        public static function listen(){
            return rest_ensure_response( 
				DV_Barangays::get_brgys()
			);
        }

        public static function get_brgys(){

            //Step 1: Validate and sanitize request
            if ( !isset($_POST["city_code"]) || !isset($_POST["mkey"]) ) {
				return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request Unknown!",
					)
				);
			}

			// Check if value passed is not null
            if ( empty($_POST['city_code']) || empty($_POST['mkey'])  ) {
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
            $city_code = $_POST["city_code"];
            $brgy_table = DV_BRGY_TABLE;
            $brgy_fields = DV_BRGY_FIELDS; 
            $where = DV_BRGY_WHERE . "'$city_code'";

            // Step 4: Start query
            $barangays =  DV_Globals::retrieve($brgy_table, $brgy_fields, $where, 'ORDER BY name', 'ASC');

            // Step 5: Check if no rows found
            if (!$barangays) {
                    return array(
                        "status" => "failed",
                        "message" => "No results found.",
                    );
            }
            
            // Return a success message and complete object
            return array(
                "status" => "success",
                "data" => $barangays
            );
            
        }
    
    
    }//end of class