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

            if ( DV_Verification::is_verified() ) {
                return rest_ensure_response( 
                    array(
                        "status" => "unknown",
                        "message" => "Please contact your administrator. Request Unknown!",
                    )
                );
            }


            // Step1 : Sanitize all Request
			if (!isset($_POST["wpid"]) || !isset($_POST["snky"]) ) {
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

            $result = $wpdb->get_results("SELECT
                revs.parent_id,
                ctc.type,
                ctc.`status`,
                max( IF ( revs.child_key = 'phone', revs.child_val, '') ) AS phones,
                max( IF ( revs.child_key = 'email', revs.child_val, '') ) AS emails,
                max( IF ( revs.child_key = 'name', revs.child_val, '') ) AS NAME,
                revs.revs_type 
            FROM
                $table_revs revs
                INNER JOIN $table_contact ctc ON ctc.`status` = revs.ID 
                OR ctc.phone = revs.ID 
                OR revs.parent_id = ctc.ID 
            WHERE
            revs.revs_type = 'contacts' 
                AND revs.created_by = $created_by
                AND ctc.created_by = $created_by 
            GROUP BY
            revs.parent_id");

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