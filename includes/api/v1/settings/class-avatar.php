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
    class DV_Avatar_update{

        public static function listen(){
            global $wpdb;
            return get_avatar_url( 1,  $args = null );
        }
        // image upload
        public static function initialize(WP_REST_Request $request) {
                
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
						"status" => "unknown",
						"message" => "Please select an image!",
					)
				);
            }
            
            //Get the directory of uploading folder
            $target_dir = wp_upload_dir();

            //Get the file extension of the uploaded image
            $file_type = strtolower(pathinfo($target_dir['path'] . '/' . basename($files['img']['name']),PATHINFO_EXTENSION));


            if (!isset($_POST['IN'])) {
                $img_name = $files['img']['name'];
            } else {
                $img_name = sanitize_file_name($_POST['IN']);
            }


            $completed_file_name = $img_name.'.'.$file_type;

            $target_file = $target_dir['path'] . '/' . basename($completed_file_name);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            
            $check = getimagesize($files['img']['tmp_name']);
            
            
            
            if($check !== false) {
                return  "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
            // Check if file already exists
            if (file_exists($target_file)) {
                return "Sorry, file already exists.";
                $uploadOk = 0;
            }
            // Check file size
            if ($files['img']['size'] > 500000) {
                return "Sorry, your file is too large.";
                $uploadOk = 0;
            }
            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != 
                "jpeg"
            && $imageFileType != "gif" ) {
                return "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                return "Sorry, your file was not uploaded.";
                // if everything is ok, try to upload file
            } else {
                $var = $target_dir['path'];
                if (move_uploaded_file($files['img']['tmp_name'], $target_file)) {
                    return "The file ". basename( $files['img']['name']). " has been 
                        uploaded.  ".$var;
                        
                        // add_user_meta( '1', 'avatar', basename( $files['img']['name']), $unique = false );
                        // add_user_meta( '1', 'filepath', $target_dir['path'], $unique = false );
                } else {
                    return "Sorry, there was an error uploading your file.";
                }
            }


		}

    }
