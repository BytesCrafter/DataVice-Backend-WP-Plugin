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

            // Step 1 : Check if city code is passed.
            if ( !isset($_POST["ctc"]) ) {
				return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request Unknown!",
					)
				);
			}

			// Step 2 : Check if city code is empty.
            if ( empty($_POST['ctc']) ) {
                return rest_ensure_response( 
                    array(
                            "status" => "failed",
                            "message" => "Required fields cannot be empty.",
                    )
                );
			}
            
            $city_code = $_POST["ctc"];

            //Table details creation
            $brgy_table = DV_BRGY_TABLE;
            $brgy_fields = DV_BRGY_FIELDS; 
            $where = DV_BRGY_WHERE . "'$city_code'";

            $barangays =  DV_Globals::retrieve($brgy_table, $brgy_fields, $where, 'ORDER BY name', 'ASC');

            if (!$barangays) {
                    return array(
                        "status" => "error",
                        "message" => "An error occured while fetching data from the server",
                    );
            }
           
            return array(
                "status" => "success",
                "data" => $barangays
            );
            
        }
    
    
    }//end of class