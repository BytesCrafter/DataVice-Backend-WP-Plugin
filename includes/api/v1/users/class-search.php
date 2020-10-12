
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

	class DV_Search_User {

		public static function listen() {
			return rest_ensure_response(
				self::listen_open()
			);
        }

        public static function listen_open(){
            global $wpdb;

            if ( DV_Verification::is_verified() == false ) {
                return array(
                        "status" => "unknown",
                        "message" => "Please contact your administrator. Verification Issue!",
                );
            }

            if (!isset($_POST['search'])) {
                return array(
                    "status" => "unknown",
                    "message" => "Please contact your administrator. Request unknown!"
                );
            }

            $limit = 5;

            $search = $_POST['search'];
            $avatar = DV_PLUGIN_URL . "assets/default-avatar.png";

            $sql = "SELECT
                ID,
                IF((SELECT meta_value FROM wp_usermeta WHERE `user_id` = w.ID AND meta_key = 'avatar') is null, '$avatar', (SELECT meta_value FROM wp_usermeta WHERE `user_id` = w.ID AND meta_key = 'avatar') ) as avatar,
                w.display_name as `name`
            FROM `pasabuy`.`wp_users` w
                WHERE
                    w.`display_name` LIKE '%$search%' OR w.user_login LIKE '%$search%'  OR w.user_email LIKE '%$search%'
                LIMIT $limit";

			if( isset($_POST['lid']) ){
				// Step 4: Validate parameter
                if (!empty($_POST['lid']) ) {

                    if ( !is_numeric($_POST["lid"])) {
                        return array(
                            "status" => "failed",
                            "message" => "Parameters not in valid format.",
                        );
                    }

                    $lastid = $_POST['lid'];
                    $sql .= " WHERE ID < $lastid ";
                    $limit = 5;

                }
            }

            $data = $wpdb->get_results($sql);

            return array(
                "status" => "success",
                "data" => $data
            );
        }
    }