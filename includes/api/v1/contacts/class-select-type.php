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
    class DV_Contact_Type{

        public static function listen(){
            
            global $wpdb;
            
            // Step 1: Validate user
            if ( DV_Verification::is_verified() == false ) {
                return rest_ensure_response( 
                    array(
                        "status" => "unknown",
                        "message" => "Please contact your administrator. Verification Issue!",
                    )
                );
            }

            // Step 2: Sanitize and validate all requests
			if ( !isset($_POST["type"]) ) {
				return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request Unknown!",
					)
                );
            }

            // Step 3: Check if passed values are not null
            if ( empty($_POST["type"]) ) {
				return rest_ensure_response( 
					array(
						"status" => "failed",
						"message" => "Please contact your administrator. Request Empty!",
					)
                );
            }

            // Step 3: Check if passed values are standard
            if ( !($_POST['type'] === 'phone') && !($_POST['type'] === 'email') && !($_POST['type'] === 'emergency') ) {
                return rest_ensure_response( 
					array(
						"status" => "invalid",
						"message" => "Please contact your administrator. Request Invalid!",
					)
                );
            }

            // Step 4: Pass constants to variables and catch post values 
            $table_contact = DV_CONTACTS_TABLE;
            $table_revs = DV_REVS_TABLE;
            $wpid = $_POST['wpid'];
            $type = $_POST['type'];

            $result = $wpdb->get_results("SELECT
                dc.ID,
                dr.child_val as `value`,
                dc.date_created 
            FROM
                $table_contact dc
                INNER JOIN $table_revs dr ON dr.ID = dc.revs
            WHERE dc.`status` = 1 AND dc.types = '$type' AND dc.wpid = $wpid ", OBJECT);

            // Check for results
            if (!$result) {
                return rest_ensure_response( 
                    array(
                        "status" => "error",
                        "message" => "Please contact your administrator. Request Error!",
                    )
                );
            }

            // return success message and complete object
            return rest_ensure_response( 
                array(
                    "status" => "success",
                    "data" => $result,
                )
            );
            
        }
    }