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
    class DV_Update_Profile{

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

            // Step 2 : Sanitize and validate all request
            if ( !isset($_POST['ln']) || !isset($_POST["fn"]) || !isset($_POST['gd']) || !isset($_POST['bd']) ) {
				return rest_ensure_response( 
                    array(
                        "status" => "unknown",
                        "message" => "Please contact your administrator. Request Unknown!",
                    )
                );
            }

            // Check if required fields are not empty
            if ( empty($_POST['ln']) || empty($_POST["fn"])  || empty($_POST['gd']) || empty($_POST['bd']) ){
				return rest_ensure_response( 
                    array(
                        "status" => "failed",
                        "message" => "Required fields cannot be empty",
                    )
                );
            }

            // Check if gender value is either Male or Female only.
            if (!($_POST['gd'] === 'Male') && !($_POST['gd'] === 'Female')) {
                return rest_ensure_response( 
                    array(
                        "status" => "failed",
                        "message" => "Invalid value for gender",
                    )
                );
            }

            // Check if birthday is in valid format (eg. 2020-08-02).
			if ( date('Y-m-d', strtotime($_POST['bd'])) !== date($_POST['bd']) ) {
                return rest_ensure_response( 
                    array(
                        "status" => "failed",
                        "message" => "Invalid value for birthday",
                    )
                );
            }

            // Step 3: Catch all post values and store it in array
            $data = DV_Update_Profile:: catching_post();

            $wpid = $_POST['wpid'];

            // Step 4: Do a loop to update all keys in user_meta
            foreach($data as $key => $value) {
                update_user_meta( $wpid, $key, $value );
            }

            // Step 5: Return a success message
            return rest_ensure_response( 
                array(
                    "status" => "success",
                    "message" => "Data has been updated successfully.",
                )
            );

        }

        public static function catching_post(){
           
            $profile_data = array();

            $profile_data['first_name'] = $_POST['fn'];
            $profile_data['last_name'] = $_POST['ln'];
            $profile_data['gender'] = $_POST['gd']; //Male, Female
            $profile_data['birthday'] = $_POST['bd']; //Y-m-d

            return $profile_data;
        }

       

    }