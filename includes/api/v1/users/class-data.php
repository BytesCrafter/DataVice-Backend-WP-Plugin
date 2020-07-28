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
?>
<?php
	class DV_Userdata{

                // REST API for getting the user data
                public static function initialize(){

                        //User validation
                        $result = DV_Globals::validate_user();
			
			if ($result['status'] !== 'success') {
				return $result;
                        }

                        //Find user in db using wpid
                        $wp_user = get_user_by("ID", $result['wpid']);
                        
                        // Return success status and complete object.
                        return rest_ensure_response( 
                                array(
                                        "status" => "success",
                                        "data" => array(
                                                "uname" => $wp_user->data->user_nicename,
                                                "dname" => $wp_user->data->display_name,
                                                "email" => $wp_user->data->user_email,
                                                "ro" => $wp_user->roles,
                                                "fn" => $wp_user->first_name,
                                                "ln" => $wp_user->last_name,
                                                "av" => 'avatar'
                                        )
                                )
                        );
                
                }// End of function initialize()

                //REST API for adding user address
                public static function add_address(){

                        //Initialize wp global variable
                        global $wpdb;
                        
                        //User verification
                        $result = DV_Globals::validate_user();
			
			if ($result['status'] !== 'success') {
				return $result;
                        }

                        //Ensures that fields are set
                        if (!isset($_POST["st"]) || !isset($_POST["br"]) || !isset($_POST["ct"]) || !isset($_POST["pr"]) || !isset($_POST["ctr"]) ) {
				return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request Unknown!",
					)
				);
                        }

                } 

                //Rest API for retrieving countries
                public static function get_countries(){
                       
                        //User verification
                        $result = DV_Globals::validate_user();
			
			if ($result['status'] !== 'success') {
				return $result;
                        }

                        //Initialize wordpress global variable
                        global $wpdb;

                        //Table details creation
                        $ctry_table = COUNTRY_TABLE;
                        $ctry_fields = COUNTRY_FIELDS; 

                        $countries =  $wpdb->get_results("SELECT $ctry_fields 
                                FROM $ctry_table
                                ORDER BY country_name ASC
                                ");

                        if (!$countries) {
                                return rest_ensure_response( 
					array(
						"status" => "error",
						"message" => "An error occured while fetching data from the server",
					)
				);
                        }

                        return rest_ensure_response( 
                                array(
                                        "status" => "success",
                                        "data" => $countries
                                )
                        );



                }

                //Rest API for retrieving provinces
                public static function get_provinces(){
                        
                         //User verification
                         $result = DV_Globals::validate_user();
			
                         if ($result['status'] !== 'success') {
                                 return $result;
                         }

                        //Initialize wordpress global variable
                        global $wpdb;

                        //Table details creation
                        $prv_table = PRV_TABLE;
                        $prv_fields = PRV_FIELDS;
                        
                        $provinces =  $wpdb->get_results("SELECT $prv_fields
                                FROM $prv_table
                                ORDER BY prov_name ASC
                        ");

                        if (!$provinces) {
                                return rest_ensure_response( 
                                        array(
                                                "status" => "error",
                                                "message" => "An error occured while fetching data from the server",
                                        )
                                );
                        }

                        return rest_ensure_response( 
                                array(
                                        "status" => "success",
                                        "data" => $provinces
                                )
                        );


                } 

                //Rest API for retrieving cities
                public static function get_cities(){
                        
                        //User verification
                        $result = DV_Globals::validate_user();
			
                        if ($result['status'] !== 'success') {
                                return $result;
                        }

                        //Initialize wordpress global variable
                        global $wpdb;

                        //Check if province code is passed
                        if (!isset($_POST["PC"]) ) {
				return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request Unknown",
					)
				);
                        }

                        if (!is_numeric($_POST["PC"]) ) {
				return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Invalid province code!",
					)
				);
                        }


                        
                        //Pass province code to a variable
                        $province_code = $_POST["PC"];

                        //Table details creation
                        $cty_table = CTY_TABLE;
                        $cty_fields = CTY_FIELDS; 

                        $cities =  $wpdb->get_results("SELECT $cty_fields
                                FROM $cty_table
                                WHERE prov_code = $province_code
                                ORDER BY citymun_name ASC
                        ");

                        if (!$cities) {
                                return rest_ensure_response( 
                                        array(
                                                "status" => "error",
                                                "message" => "An error occured while fetching data from the server",
                                        )
                                );
                        }

                        return rest_ensure_response( 
                                array(
                                        "status" => "success",
                                        "data" => $cities
                                )
                        );


                }


                //Rest API for retrieving barangays
                public static function get_brgy(){
                        
                        //User verification
                        $result = DV_Globals::validate_user();
			
                        if ($result['status'] !== 'success') {
                                return $result;
                        }

                        //Initialize wordpress global variable
                        global $wpdb;

                        //Check if city code is passed
                        if (!isset($_POST["CC"]) ) {
				return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request Unknown",
					)
				);
                        }

                        if (!is_numeric($_POST["CC"]) ) {
				return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Invalid city code!",
					)
				);
                        }


                        
                        //Pass province code to a variable
                        $city_code = $_POST["CC"];

                        //Table details creation
                        $brgy_table = BRGY_TABLE; 
                        $brgy_fields = BRGY_FIELDS;

                        $brgys =  $wpdb->get_results("SELECT $brgy_fields
                                FROM $brgy_table
                                WHERE city_code = $city_code
                                ORDER BY brgy_name ASC
                        ");

                        if (!$brgys) {
                                return rest_ensure_response( 
                                        array(
                                                "status" => "error",
                                                "message" => "An error occured while fetching data from the server",
                                        )
                                );
                        }

                        return rest_ensure_response( 
                                array(
                                        "status" => "success",
                                        "data" => $brgys
                                )
                        );

                }


                //Function for user validation and verification
                public static function validate_user(){
            
                        //User verification
                        $verified = DV_Verification::initialize();
            
                        //Convert object to array
                        $array =  (array) $verified;
            
                        // Pass request status in a variable
                        $response =  $array['data']['status'];
                        
                        if ($response == 'success') {
                                return true;
                        } else {
                                return false;
                        }
                        
                }
        
	} // End of class

?>