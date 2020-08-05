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
			if (!isset($_POST["wpid"]) || !isset($_POST["snky"])  || !isset($_POST['ctc']) || !isset($_POST['stid'])) {
				return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request unknown!",
					)
                );
                
            }
            
            //Check if passed values are not null
            if (empty($_POST["wpid"]) || empty($_POST["snky"])  || empty($_POST['ctc']) || empty($_POST['stid'])) {
				return rest_ensure_response( 
					array(
						"status" => "failed",
						"message" => "Required fields cannot be empty.",
					)
                );
                
            }

            //Check if ID is in valid format (integer)
			if (!is_numeric($_POST["wpid"]) ) {
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

            // Step 3: Pass constants to variables and catch post values

            $table_contact = DV_CONTACTS_TABLE;
            $table_revs = DV_REVS_TABLE;
            $created_by = $_POST['wpid'];
            $contact_id = $_POST['ctc'];

            // Step 4: Start query
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

            // Step 5: Check if no rows found
            if (!$result) {
                return rest_ensure_response( 
					array(
						"status" => "failed",
						"message" => "No results found.",
					)
                );
            }

            // Return a success message and complete object
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