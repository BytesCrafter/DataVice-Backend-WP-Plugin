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

            //Table details creation
            $ctry_table = DV_COUNTRY_TABLE;
            $ctry_fields = DV_COUNTRY_FIELDS; 
            $where = DV_CTRY_WHERE;

            $countries =  DV_Globals::retrieve($ctry_table, $ctry_fields, $where, 'ORDER BY name', 'ASC');

            if (!$countries) {
                    return array(
                        "status" => "error",
                        "message" => "An error occured while fetching data from the server",
                    );
            }
           
            return array(
                "status" => "success",
                "data" => $countries
            );
            
        }
    
    
    }//end of class