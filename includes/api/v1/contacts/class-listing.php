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
			if (!isset($_POST["id"]) || !isset($_POST["own"]) ) {
				return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request unknown!",
					)
                );
                
            }

            // Check if passed values are not null
            if (empty($_POST["id"]) || empty($_POST["own"]) ) {
				return rest_ensure_response( 
					array(
						"status" => "failed",
						"message" => "Required fields cannot be empty.",
					)
                );
            }

            // Check if owner type is either user or store only.
            if (!($_POST['own'] === 'user') && !($_POST['own'] === 'store')) {
                return rest_ensure_response( 
                    array(
						"status" => "unknown",
						"message" => "Invalid owner type",
					)
                );
            }

            // Step 3: Pass constants to variables and catch post values 
            $table_contact = DV_CONTACTS_TABLE;
            $table_revs = DV_REVS_TABLE;
            $id = $_POST['id'];

            
            // Step 4: Check if either contact owner is user or store
            // Do a query for each type of owner
            if ($_POST['own'] == 'user') {
                if ( !get_user_by("ID", $id) ) {
                    return rest_ensure_response( 
                        array(
                            "status" => "failed",
                            "message" => "User not found.",
                        )
                    );
                }
                $result = $wpdb->get_results("SELECT
                    dc.ID,
                    dc.`status`,
                    dc.types,
                    dr.child_val as `value`,
                    dc.created_by,
                    dc.date_created 
                FROM
                    $table_contact dc
                    INNER JOIN $table_revs dr ON dr.ID = dc.revs
                WHERE dc.`status` = 1 AND dc.wpid = $id ", OBJECT);
                
            }

            if ($_POST['own'] == 'store') {

                $get_store = $wpdb->get_row("SELECT `ID` FROM `tp_stores` WHERE ID = '{$_POST["id"]}' ");
                
                //Check if this store id exists
                 if ( !$get_store ) {
                    return rest_ensure_response( 
                        array(
                            "status" => "failed",
                            "message" => "Store not found.",
                        )
                    );
                }

                $result = $wpdb->get_results("SELECT
                    dc.ID,
                    dc.`status`,
                    dc.types,
                    dr.child_val as `value`,
                    dc.created_by,
                    dc.date_created 
                FROM
                    $table_contact dc
                    INNER JOIN $table_revs dr ON dr.ID = dc.revs
                WHERE dc.`status` = 1 AND dc.stid = $id ", OBJECT);
            }

            
            // Check for results
            if (!$result) {
                return rest_ensure_response( 
                    array(
                        "status" => "error",
                        "message" => "No contacts found."
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