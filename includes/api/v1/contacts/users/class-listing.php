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
    class DV_Contact_Listing{

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
			if (!isset($_POST["wpid"]) || !isset($_POST["snky"]) || !isset($_POST['id']) ) {
				return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request unknown!",
					)
                );
                
            }
            
            //Check if id is valid
			if (!is_numeric($_POST["wpid"]) || !is_numeric($_POST['id']) ) {
				return rest_ensure_response( 
					array(
						"status" => "failed",
						"message" => "Please contact your administrator. ID not in valid format!",
					)
                );
                
            }
            
            // Check if request passed is not null
			if (empty($_POST["wpid"]) || empty($_POST["snky"]) || empty($_POST['id']) ) {
				return rest_ensure_response( 
					array(
						"status" => "failed",
						"message" => "Required Fileds cannot be empty",
					)
                );
                
            }

			// Check if this user exists
			if (!get_user_by("ID", $_POST['id'])) {
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
            $id = $_POST['id'];

            // Step 4: Select query
            $result = $wpdb->get_results("SELECT
                    dv_cont.ID,
                    dv_cont.`status`,
                    dv_cont.types,
                    dv_rev.child_val as `value`,
                    dv_cont.created_by,
                    dv_cont.date_created 
                FROM
                    $table_contact dv_cont
                    INNER JOIN $table_revs dv_rev ON dv_rev.ID = dv_cont.revs
                WHERE dv_cont.`status` = 1 AND dv_cont.wpid = $id ", OBJECT);

            // Step 5: Check if no rows found
            if (!$result) {
                return rest_ensure_response( 
                    array(
                        "status" => "error",
                        "message" => "An error occurred while submitting data to the server."
                    )
                );
           // return success message and complete object
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