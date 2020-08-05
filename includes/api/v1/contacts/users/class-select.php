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
    class DV_Contact_Select{

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
			if (!isset($_POST["wpid"]) || !isset($_POST["snky"]) || !isset($_POST['ctc']) || !isset($_POST['id']) ) {
				return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request unknown!",
					)
                );
            }

            //Check if params passed has values
            if (empty($_POST["wpid"]) || empty($_POST["snky"]) || empty($_POST['ctc']) || empty($_POST['id']) ) {
				return rest_ensure_response( 
					array(
						"status" => "failed",
						"message" => "Required fields cannot be empty",
					)
                );
                
            }

            //Check if contact id and user id is valid
            if ( !is_numeric($_POST['id']) ||  !is_numeric($_POST['ctc']) || !is_numeric($_POST['wpid']) ) {
                return rest_ensure_response( 
                    array(
                        "status" => "failed",
                        "message" => "Please contact your administrator. ID not in valid format!",
                    )
                );
                
            } 

            // Step 2: Check if id(owner) of this contact exists
			if ( !get_user_by("ID", $_POST['id']) ) {
				return rest_ensure_response( 
					array(
						"status" => "failed",
						"message" => "User not found.",
					)
                );
            }

            $table_contact = DV_CONTACTS_TABLE;
            
            $table_revs = DV_REVS_TABLE;

            $owner_id = $_POST['id'];

            $contact_id = $_POST['ctc'];

            $result  = $wpdb->get_results("SELECT
                dv_ctcs.ID,
                dv_ctcs.types,
                dv_revs.child_val AS `value`,
                dv_ctcs.date_created 
            FROM
                $table_contact dv_ctcs
                INNER JOIN $table_revs dv_revs ON dv_revs.ID = dv_ctcs.revs 
            WHERE
                dv_ctcs.ID = $contact_id
                AND dv_ctcs.wpid = $owner_id AND dv_ctcs.`status` = 1");


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