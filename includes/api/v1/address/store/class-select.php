<?php
	// Exit if accessed directly
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	/** 
        * @package datavice-wp-plugin
		* @version 0.1.0
		* This is the primary gateway of all the rest api request.
	*/
?>
<?php
    class DV_Select_Store_Address{
        public static function listen(){
			
			global $wpdb;
			
            // Step 1: Validate user
            if ( DV_Verification::is_verified() == false ) {
                return rest_ensure_response( 
                    array(
                        "status" => "unknown",
                        "message" => "Please contact your administrator. Request Unknown!",
                    )
                );
            }


            // Step 2: Sanitize and validate all requests
			if (!isset($_POST["wpid"]) || !isset($_POST["snky"])  || !isset($_POST['add']) || !isset($_POST['stid'])) {
				return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request unknown!",
					)
                );
                
            }
            
            //Check if passed values are not null
            if (empty($_POST["wpid"]) || empty($_POST["snky"])  || empty($_POST['add']) || empty($_POST['stid'])) {
				return rest_ensure_response( 
					array(
						"status" => "failed",
						"message" => "Required fields cannot be empty.",
					)
                );
                
            }

            //Check if ID is in valid format (integer)
			if (!is_numeric($_POST["wpid"]) || !is_numeric($_POST['add']) || !is_numeric($_POST['stid']) ) {
				return rest_ensure_response( 
					array(
						"status" => "failed",
						"message" => "Please contact your administrator. Id not in valid format!",
					)
                );
                
            }
            

            $stid = $_POST['stid'];
            $get_contact = $wpdb->get_row("SELECT ID FROM tp_stores  WHERE ID = $stid ");
            
            //Check if this store id exists
             if ( !$get_contact ) {
                return rest_ensure_response( 
                    array(
                        "status" => "error",
                        "message" => "An error occurred while fetching data to the server.",
                    )
                );
            }

			// Check if ID exists
			if (!get_user_by("ID", $_POST['wpid'])) {
				return rest_ensure_response( 
					array(
						"status" => "failed",
						"message" => "User not found!",
					)
                );
                
			}

			
            $user = DV_Update_Store_Address::catch_post();

            $result  = $wpdb->get_results("");
			return $resultl;


		}
		

		public static function catch_post()
        {
			$cur_user = array();
			
            $cur_user['store_id']      = $_POST['stid'];
			$cur_user['created_by']  = $_POST['wpid'];
			
            $cur_user['add_id']      = $_POST['add'];


            return  $cur_user;
        }
	}
	
	