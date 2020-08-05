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
    
    //Store id and Type


?>
<?php
    class DV_Contact_Stores_Type{
        public static function listen(){
            global $wpdb;

            if ( DV_Verification::is_verified() == false) {
                return rest_ensure_response( 
                    array(
                        "status" => "unknown",
                        "message" => "Please contact your administrator. Request Unknown!",
                    )
                );
            }

            // Step1 : Sanitize all Request
			if (!isset($_POST["wpid"]) || !isset($_POST["snky"]) || !isset($_POST['type']) || !isset($_POST['stid']) ) {
				return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request unknown!",
					)
                );
            }

            //Check if params passed has values
            if (empty($_POST["wpid"]) || empty($_POST["snky"]) || empty($_POST['type']) || empty($_POST['stid']) ) {
				return rest_ensure_response( 
					array(
						"status" => "failed",
						"message" => "Required fields cannot be empty",
					)
                );
                
            }

            //Check if contact id and user id is valid
            if (!is_numeric($_POST['stid']) ||  !is_numeric($_POST['wpid']) ) {
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

            // Step 2: Check if id(owner) of this contact exists
			if (!get_user_by("ID", $_POST['wpid'] )) {
				return rest_ensure_response( 
					array(
						"status" => "failed",
						"message" => "User not found.",
					)
                );
            }

            $table_contact = DV_CONTACTS_TABLE;
            
            $table_revs = DV_REVS_TABLE;

            // $owner_id = $_POST['id'];

            $type = $_POST['type'];

            $result  = $wpdb->get_results("SELECT
                ctc.ID,
                ctc.types,
                ctc.`status`,
                revs.child_val as $type
            FROM
                $table_revs revs
                INNER JOIN $table_contact ctc ON revs.parent_id = ctc.ID 
            WHERE
                revs.child_key = '$type'
                AND ctc.stid = $stid 
                AND ctc.`status` = 1");


            if (!$result) {
                return rest_ensure_response( 
					array(
						"status" => "failed",
						"message" => "No results found.",
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