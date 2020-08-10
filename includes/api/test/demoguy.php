
<?php
	// Exit if accessed directly
	if ( ! defined( 'ABSPATH' ) ) 
	{
		exit;
	}

	/** 
        * @package datavice-wp-plugin
        * @version 0.1.0
	*/
?>
<?php
	class DV_Demoguy {

		// Rest Api routing.
		public static function listen() {
		
			// Check that we're trying to authenticate
			if (!isset($_POST["val1"]) ) {
				return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request Unknown!",
					)
				);
			}

			// Step 2 : Check if username or password is not empty.
            if ( empty($_POST['val1']) ) {
                return rest_ensure_response( 
                    array(
                            "status" => "failed",
                            "message" => "Required fields cannot be empty.",
                    )
                );
			}
			
			// Call any function to test here.


				
			// update_option( 'upload_path', untrailingslashit(ABSPATH) . '\wp-content\uploads\avatars' );
			// update_option( 'upload_path_url', site_url( '/uploads/' ) );
		}
		
		// Rest Api routing.
		public static function upld_image(WP_REST_Request $request) {
			
			// call upload image function in global
			$result = DV_Globals::upload_image( $request);

			if ($result == false) {
				return rest_ensure_response( 
                    array(
                        "status" => "failed",
                        "message" => "Please contact your administrator. Uploading Image failed",
                    )
                );
			}else{
				// returning file path
				return $result;
			}
			
		}
	}

?>