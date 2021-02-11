
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

	class DV_Upload_Avatar {

		public static function listen() {

            //User validation
            if (DV_Verification::is_verified() == false) {
				return array(
                    "status" => "unknown",
                    "message" => "Please contact your administrator. Request Unknown!",
                );
            }

            return rest_ensure_response( 
                DV_Upload_Avatar::upload($_POST['wpid'])
            ); 
        }

        public static function upload($wpid) {

            ///Check if all field is set.
            if ( !isset($_POST['img']) ) {
                return array(
                    "status" => "unknowm",
                    "message" => "Please contact your administrator. Request Incomplete."
                );
            }

            //Check if one required field is empty.
            if ( empty($_POST['img']) ) {
                return array(
                    "status" => "unknowm",
                    "message" => "Please contact your administrator. Request Empty."
                );
            }

            $preview = CM_PLUGIN_URL . "assets/images/default.jpg"; 
            $imageUrl = DV_Globals::base64_upload( $_POST['img'] );
            if( $imageUrl == false ) {
                return array(
                    "status" => "failed",
                    "message" => "Please contact your administrator. Upload Failed."
                );
            }

            update_user_meta($wpid, "avatar", $imageUrl);

            return array(
                "status" => "success",
                "message" => "Avatar is now updated!"
            );

        }
    }