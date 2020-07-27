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
	class DVC_Userdata{

                // REST API for getting the user data
                public static function initialize(){

                        //User verification
                        $verified = DVC_Verification::initialize();

                        //Convert object to array
                        $array =  (array) $verified;

                        // Pass the ID in a variable
                        $user_id =  $array['data']['wpid']; 

                        //Get user data and pass it to $wp_user
                        $wp_user = get_user_by("ID", $user_id);
                        
                        // Check if $wp_user has value
                        if ($wp_user == false) {
                                return rest_ensure_response( 
                                        array(
                                                "status" => "failed",
                                                "message" => "Please contact your administrator. User Not Found!"
                                        )
                                );
                        }
                        
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
                        $verified = DVC_Verification::initialize();

                        //Convert object to array
                        $array =  (array) $verified;

                        // Pass request status in a variable
                        $response =  $array['data']['status'];
                        
                        // Pass wpid in a variable
                        $wpid =  $array['data']['wpid'];

                        if ($response != 'success') {
                                return rest_ensure_response( 
                                        array(
                                                "status" => "failed",
                                                "message" => "Please contact your administrator. User Not Found!"
                                        )
                                );
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
                       
                        // Validate user
                        if (DVC_Userdata::validate_user() == false) {
                                return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request Unknown!",
					)
				);
                        }

                        //Initialize wordpress global variable
                        global $wpdb;

                        //Table name creation
                        $ctry_table = DV_PREFIX.'countries'; 

                        $countries =  $wpdb->get_results("SELECT * 
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
                        
                        // Validate user
                        if (DVC_Userdata::validate_user() == false) {
                                return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request Unknown!",
					)
				);
                        }
                        
                        //Initialize wordpress global variable
                        global $wpdb;

                        //Table name creation
                        $prv_table = DV_PREFIX.'provinces';
                        
                        $provinces =  $wpdb->get_results("SELECT id, prov_name as prov
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
                        
                        // Validate user
                        if (DVC_Userdata::validate_user() == false) {
                                return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request Unknown!",
					)
				);
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

                        //Table name creation
                        $cty_table = DV_PREFIX.'cities'; 

                        $cities =  $wpdb->get_results("SELECT *
                                FROM $cty_table
                                WHERE prov_code = $province_code
                                ORDER BY city_name ASC
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
                        
                        // Validate user
                        if (DVC_Userdata::validate_user() == false) {
                                return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request Unknown!",
					)
				);
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

                        //Table name creation
                        $brgy_table = DV_PREFIX.'brgys'; 

                        $brgys =  $wpdb->get_results("SELECT *
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
                        $verified = DVC_Verification::initialize();
            
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


