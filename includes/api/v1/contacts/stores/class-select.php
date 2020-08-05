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
    class DV_Contact_Stores_Select{

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
            //REVISE REVISE:contact id & store_id
			if (!isset($_POST["wpid"]) || !isset($_POST["snky"])  || !isset($_POST['ctc']) || !isset($_POST['stid'])) {
				return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request unknown!",
					)
                );
                
            }
            
            if (empty($_POST["wpid"]) || empty($_POST["snky"])  || empty($_POST['ctc']) || empty($_POST['stid'])) {
				return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request unknown!",
					)
                );
                
            }

              // Step 2: Check if ID is in valid format (integer)
			if (!is_numeric($_POST["wpid"]) ) {
				return rest_ensure_response( 
					array(
						"status" => "failed",
						"message" => "Please contact your administrator. ID not in valid format!",
					)
                );
                
            }
            

            $stid = $_POST['stid'];
            $get_contact = $wpdb->get_row("SELECT ID FROM tp_stores  WHERE ID = $stid ");
            
            //Check if wpid match the created_by value
             if ( !$get_contact ) {
                return rest_ensure_response( 
                    array(
                        "status" => "error",
                        "message" => "An error occurred while submiting data to the server.",
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

            $contact_id = $_POST['ctc'];


            $result  = $wpdb->get_results("SELECT
                dv_ctcs.ID,
                dv_ctcs.types,
                dv_revs.child_val AS `phone`,
                dv_ctcs.date_created 
            FROM
                $table_contact dv_ctcs
                INNER JOIN $table_revs dv_revs ON dv_revs.ID = dv_ctcs.revs 
            WHERE
                dv_ctcs.ID = $contact_id
                AND dv_ctcs.stid = $stid AND dv_ctcs.`status` = 1");


            if (!$result) {
                return rest_ensure_response( 
					array(
						"status" => "failed",
						"message" => "No contacts found!.",
					)
                );
            }

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