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
    class DV_Offset{

        public static function listen(){
            return rest_ensure_response( 
				DV_Offset::get_offset()
			);
        }

        public static function get_offset(){
            
            global $wpdb;
            
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
            
            $country_code= $_POST["cc"];

           // Step 3: Pass constants to variables and catch post values 
            $tz_table = DV_TZ_TABLE;
            $ctry_table = DV_COUNTRY_TABLE;

            // Step 4: Start query
            $offset =  $wpdb->get_row("SELECT c.country_code as code, c.country_name as name, t.utc_offset as offset
                FROM $ctry_table c
                LEFT JOIN dv_geo_timezone t ON t.country_code = c.country_code
                WHERE c.status = 1
                AND c.country_code = '$country_code'
            ");
            // Step 5: Check if no rows found
            if (!$offset) {
                    return array(
                        "status" => "failed",
                        "message" => "No results found",
                    );
            }
            
            // Return a success message and complete object
            return array(
                "status" => "success",
                "data" => $offset
            );
            
        }
    
    
    }//end of class