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
            if ( !isset($_POST["mkey"]) ) {
				return array(
					"status" => "unknown",
					"message" => "Please contact your administrator. Request Unknown!",
                );
			}

			// Check if value passed is not null
            if ( empty($_POST['mkey']) ) {
                return  array(
                    "status" => "failed",
                    "message" => "Please contact your administrator. Request Empty!",
                );
                
			}

            //Step 2: Master key validation
            $master_key = DV_Library_Config::dv_get_config('master_key', "datavice");
            
            //Check if master key matches
            if ($master_key !== $_POST['mkey']) {
                return  array(
                    "status" => "error",
                    "message" => "Master key does not match.",
                );
            }

            return self::get_countries_raw(0);
        }

        public static function get_countries_raw($status) {

            global $wpdb;

            // Step 3: Pass constants to variables and catch post values 
            $ctry_table = DV_COUNTRY_TABLE;
            $tzone_table = DV_TZ_TABLE;
            $ctry_fields = DV_COUNTRY_FIELDS; 
            $where = DV_COUNTRY_WHERE;

            // Step 4: Start query
            $countries =  $wpdb->get_results("SELECT $ctry_table.ID, $ctry_table.country_code as code, $ctry_table.country_name as name, $tzone_table.tzone_name as tzone
                FROM $ctry_table INNER JOIN $tzone_table ON $tzone_table.country_code = $ctry_table.country_code where $ctry_table.status = $status");

            // Step 5: Check if no rows found
            if (!$countries) {
                    return array(
                        "status" => "error",
                        "message" => "No results found."
                    );
            }
            
            // Return a success message and complete object
            return array(
                "status" => "success",
                "data" => $countries
            );
        }
    
    }//end of class