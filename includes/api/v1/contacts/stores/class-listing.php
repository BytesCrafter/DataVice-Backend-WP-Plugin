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
    class DV_Contact_Stores_Listing{

        public static function listen(){

            global $wpdb;

            if ( DV_Verification::is_verified() == false ) {
                return rest_ensure_response( 
                    array(
                        "status" => "unknown",
                        "message" => "Please contact your administrator. Request Unknown!",
                    )
                );
            }


            // Step1 : Sanitize all Request
			if (!isset($_POST["wpid"]) || !isset($_POST["snky"]) || !isset($_POST['stid']) ) {
				return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request unknown!",
					)
                );
                
            }
            
              // Step 2: Check if ID is in valid format (integer)
			if (!is_numeric($_POST["wpid"]) || !is_numeric($_POST['stid']) ) {
				return rest_ensure_response( 
					array(
						"status" => "failed",
						"message" => "Please contact your administrator. ID not in valid format!",
					)
                );
                
            }
            
               // Step1 : Sanitize all Request
			if (empty($_POST["wpid"]) || empty($_POST["snky"]) || empty($_POST['stid']) ) {
				return rest_ensure_response( 
					array(
						"status" => "failed",
						"message" => "Required Fileds cannot be empty",
					)
                );
                
            }

			// Step 3: Check if ID exists
			if (!get_user_by("ID", $_POST['wpid'])) {
				return rest_ensure_response( 
					array(
						"status" => "failed",
						"message" => "User not found!",
					)
                );
                
            }

            
            $table_contact = DV_CONTACTS_TABLE;
            $table_revs = DV_REVS_TABLE;


            $created_by = $_POST['wpid'];

            $stid = $_POST['stid'];

            $result = $wpdb->get_results("SELECT
                    dv_cont.ID,
                    dv_cont.`status`,
                    dv_cont.types,
                    dv_rev.child_val,
                    dv_cont.stid,
                    dv_cont.created_by,
                    dv_cont.date_created 
                FROM
                    $table_contact dv_cont
                    INNER JOIN $table_revs dv_rev ON dv_rev.ID = dv_cont.revs
                WHERE dv_cont.`status` = 1 AND dv_cont.stid = $stid ", OBJECT);

            if (!$result) {
                return rest_ensure_response( 
                    array(
                        "status" => "failed",
                        "message" => "An error occurred while submiting data to the server."
                    )
                );

            }else {
                return rest_ensure_response( 
                    array(
                        "status" => "success",
                        "data" => array(
                            'list' => $result, 
                        
                        )
                    )
                );

            }
            
        }
    }