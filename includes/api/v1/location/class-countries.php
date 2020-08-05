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
    class DV_Countries{

        public static function listen(){
            return rest_ensure_response( 
				DV_Countries::get_countries()
			);
        }

        public static function get_countries(){
            
            //Step 1: Validate and sanitize request
            if ( !isset($_POST["mk"]) ) {
				return array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request Unknown!",
                );
				
			}

			// Check if value passed is not null
            if ( empty($_POST['mk']) ) {
                return  array(
                        "status" => "failed",
                        "message" => "Required fields cannot be empty.",
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

            // Step 3: Pass constants to variables and catch post values 
            $ctry_table = DV_COUNTRY_TABLE;
            $ctry_fields = DV_COUNTRY_FIELDS; 
            $where = DV_COUNTRY_WHERE;

            // Step 4: Start query
            $countries =  DV_Globals::retrieve($ctry_table, $ctry_fields, $where, 'ORDER BY name', 'ASC');

            // Step 5: Check if no rows found
            if (!$countries) {
                    return array(
                        "status" => "error",
                        "message" => "No results found.",
                    );
            }
            
            // Return a success message and complete object
            return array(
                "status" => "success",
                "data" => $countries
            );
            
        }
    
    
    }//end of class