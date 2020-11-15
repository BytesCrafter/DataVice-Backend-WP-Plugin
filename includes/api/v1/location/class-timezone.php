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
    
    class DV_Timezone{

        public static function listen(){
            return rest_ensure_response( 
				DV_Timezone::get_timezone()
			);
        }

        public static function get_timezone(){
            
            global $wpdb;
            
             //Step 1: Validate and sanitize request
            if ( !isset($_POST["country_code"]) || !isset($_POST["mkey"]) ) {
				return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request Unknown!",
					)
				);
			}

			// Check if value passed is not null
            if ( empty($_POST['country_code']) || empty($_POST['mkey'])  ) {
                return rest_ensure_response( 
                    array(
                            "status" => "failed",
                            "message" => "Required fields cannot be empty.",
                    )
                );
            }
            
            //Step 2: Master key validation
            //Get master key from database
            $master_key = DV_Library_Config::dv_get_config('master_key', "datavice");
            
            //Check if master key matches
            if ($master_key !== $_POST['mkey']) {
                return  array(
                    "status" => "error",
                    "message" => "Master key does not match.",
                );
            }
            
            $country_code = $_POST["country_code"];

           // Step 3: Pass constants to variables and catch post values 
            $tz_table = DV_TZ_TABLE;
            $ctry_table = DV_COUNTRY_TABLE;

            // Step 4: Start query
            $timezone =  $wpdb->get_row("SELECT t.tzone_name as timezone, t.utc_offset as offset, t.utc_dst_offset as daylight
                FROM $ctry_table c
                LEFT JOIN dv_geo_timezone t ON t.country_code = c.country_code
                WHERE c.status = 1
                AND c.country_code = '$country_code'
            ");
            // Step 5: Check if no rows found
            if (!$timezone) {
                    return array(
                        "status" => "failed",
                        "message" => "No results found",
                    );
            }
            
            // Return a success message and complete object
            return array(
                "status" => "success",
                "data" => $timezone
            );
            
        }
    
    
    }//end of class