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

    class DV_Delete_Address {

        public static function listen(){
            global $wpdb;

               // Step1: Validate user
               if ( DV_Verification::is_verified() == false ) {
                return rest_ensure_response(
                    array(
                        "status" => "unknown",
                        "message" => "Please contact your administrator. Verification Issue!",
                    )
                );
            }

            // Step 1 : Check if the fields are passed
            if( !isset($_POST['id'])){
                return rest_ensure_response(
                    array(
                            "status" => "unknown",
                            "message" => "Please contact your administrator. Request Unknown!",
                    )
                );
            }

             // Step 1 : Check if the fields are passed
             if( empty($_POST['id']) ){
                return rest_ensure_response(
                    array(
                            "status" => "unknown",
                            "message" => "Please contact your administrator. Request Empty!",
                    )
                );
            }

            // Step5: Check if ID is in valid format (integer)
            if( !is_numeric($_POST['id']) ){
                return rest_ensure_response(
                    array(
                            "status" => "unknown",
                            "message" => "Please contact your administrator. Request not in valid format!",
                    )
                );
            }


            $table_address = DV_ADDRESS_TABLE;
            $dv_rev_table = DV_REVS_TABLE;
            $rev_fields = DV_INSERT_REV_FIELDS;
            $created_by = $_POST['wpid'];
            $address_id = $_POST['id'];
            $date = DV_Globals:: date_stamp();

            // Select last address information using address ID
            $last_add_data =  $wpdb->get_row("SELECT * FROM $table_address WHERE id = $address_id AND wpid = $created_by ", OBJECT);

            if (!$last_add_data ) {
                return rest_ensure_response(
                    array(
                        "status" => "error",
                        "message" => "An error while validating addres."
                    )
                );
            }

            //Start for mysql transaction - This is crucial in inserting data with connection with each other
            $wpdb->query("START TRANSACTION");

            $wpdb->query("INSERT INTO $dv_rev_table (parent_id, $rev_fields) VALUES ('$address_id', 'address', 'status', '0', $created_by, '$date');");
            $child_item = $wpdb->insert_id;

            $wpdb->query("UPDATE $table_address SET `status` = '$child_item' WHERE ID = '{$last_add_data->ID}' ");
            $parent_item = $wpdb->insert_id;

            //Check if any of the insert queries above failed
            if ( $child_item < 1 || $parent_item < 1 ) {

                //If failed, do mysql rollback (discard the insert queries(no inserted data))
                $wpdb->query("ROLLBACK");

                return rest_ensure_response(
                    array(
                        "status" => "error",
                        "message" => "Address failed to updated."
                    )
                );
            }

            //If no problems found in queries above, do mysql commit (do changes(insert rows))
            $wpdb->query("COMMIT");

            return rest_ensure_response(
                array(
                    "status" => "success",
                    "message" => "Address had been deactivated successfully."
                )
            );
        }
    }