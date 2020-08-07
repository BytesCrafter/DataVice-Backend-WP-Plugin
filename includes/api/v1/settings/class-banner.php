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
    class DV_Banner_update{
       
        // image upload
        public static function listen(WP_REST_Request $request) {
    
            // Step 1: validate user
            if ( DV_Verification::is_verified() == false ) {
                return rest_ensure_response( 
                    array(
                        "status" => "unknown",
                        "message" => "Please contact your administrator. Request Unknown!",
                    )
                );
            }

            $wpid = $_POST['wpid'];
           
            $files = $request->get_file_params();
            
            if ( !isset($files['img'])) {
				return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Request Unknown!",
					)
				);
            }

            if ( $files['img']['name'] == NULL  || $files['img']['type'] == NULL) {
				return rest_ensure_response( 
					array(
						"status" => "failed",
						"message" => "Please select an image!",
					)
				);
            }
            
            //Get the directory of uploading folder
            $target_dir = wp_upload_dir();

            //Get the file extension of the uploaded image
            $file_type = strtolower(pathinfo($target_dir['path'] . '/' . basename($files['img']['name']),PATHINFO_EXTENSION));

            if (!isset($_POST['in'])) {
                $img_name = $files['img']['name'];
            } else {
                $img_name = sanitize_file_name($_POST['in']);
            }

            $completed_file_name = $img_name.'.'.$file_type;

            $target_file = $target_dir['path'] . '/' . basename($completed_file_name);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            
            $check = getimagesize($files['img']['tmp_name']);
            
            if($check !== false) {
                $uploadOk = 1;
            } else {
                $uploadOk = 0;
                return rest_ensure_response( 
					array(
						"status" => "failed",
						"message" => "Invalid file type. Only image are allowed.",
					)
				);
            }
            // Check if file already exists
            if (file_exists($target_file)) {
                $uploadOk = 0;
                return rest_ensure_response( 
					array(
						"status" => "failed",
						"message" => "A file with this name already exists",
					)
				);
            }
            // Check file size
            if ($files['img']['size'] > 500000) {
                $uploadOk = 0;
                return rest_ensure_response( 
					array(
						"status" => "errfailedor",
						"message" => "Your image file size was too big.",
					)
				);
            }
            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != 
                "jpeg"
            && $imageFileType != "gif" ) {
                $uploadOk = 0;
                return rest_ensure_response( 
					array(
						"status" => "failed",
						"message" => "Invalid image file type. JPG, PNG, JPEG and GIF types are only accepted",
					)
				);
            }
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                return rest_ensure_response( 
					array(
						"status" => "error",
						"message" => "An error occured while submitting data to the server.",
					)
				);
            } else {
                $var = $target_dir['path'];
                if (move_uploaded_file($files['img']['tmp_name'], $target_file)) {
              
                    $banner_name = trailingslashit($target_dir['subdir']).$completed_file_name;
                
                    update_user_meta( $wpid, 'banner', $banner_name);

                    return rest_ensure_response( 
                        array(
                            "status" => "success",
                            "message" => "Data has been updated successfully.",
                        )
                    );  
               
                } else {
                    return rest_ensure_response( 
                        array(
                            "status" => "error",
                            "message" => "An error occured while submitting data to the server.",
                        )
                    );
                }
            }


		}

    }
