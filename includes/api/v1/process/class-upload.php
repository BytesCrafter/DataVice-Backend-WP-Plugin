
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

	class DV_Upload {

		public static function listen(WP_REST_Request $request) {
			return rest_ensure_response(

                DV_Upload::listen_open($request)
            );
		}

		public static function listen_open($request) {
            global $wpdb;
            $date = DV_Globals:: date_stamp();

            if ( DV_Verification::is_verified() == false ) {
                return array(
                        "status" => "unknown",
                        "message" => "Please contact your administrator. Verification Issue!",
                );
            }

            $files = $request->get_file_params();

            if ( !isset($files['img'])) {
				return  array(
                    "status" => "unknown",
                    "message" => "Please contact your administrator. Request Unknown!",
                );
            }

            isset($_POST['wpid']) ? $user_id = $_POST['wpid'] : $user_id = NULL;
            isset($_POST['stid']) ? $stid = $_POST['stid'] : $stid = NULL;
            isset($_POST['pdid']) ? $pdid = $_POST['pdid'] : $pdid = NULL;
            isset($_POST['type']) ? $typ = $_POST['type'] : $typ = NULL;
            isset($_POST['mkey']) ? $mkey = $_POST['mkey'] : $mkey = NULL;

            $wpid = $user_id  == '0' || $user_id == NULL ? NULL: $wpid = $user_id;
            $store_id = $stid  == '0' || $stid == NULL ? NULL: $store_id = $stid;
            $product_id = $pdid  == '0' || $pdid == NULL ? NULL: $product_id = $pdid;
            $type = $typ  == '0' || $typ == NULL ? NULL: $type = $typ;

            $master_key = $mkey  == '0' || $mkey == NULL ? NULL: $master_key = $mkey;

            if (isset($_POST['wpid']) && isset($_POST['mkey'])  ) {

                if ($wpid !== NULL || $master_key  !== NULL ) {

                    $master_key = DV_Library_Config::dv_get_config('master_key', '123');

                    //Check if master key matches
                    if ($master_key !== $_POST['mkey']) {
                        return  array(
                            "status" => "error",
                            "message" => "Master key does not match.",
                        );
                    }

                    // Call upload image function
                    $result = DV_Globals::upload_image( $request, $files);

                    if ($result['status'] != 'success') {
                        return array(
                            "status" => $result['status'],
                            "message" => $result['message']
                        );
                    }

                    if ($result['status'] == 'success') {
                        return array(
                            "status" => $result['status'],
                            "data" => $result['data']

                        );
                    }
                }
            }

            // for inserting user avatar
            else if (isset($_POST['wpid']) && !isset($_POST['stid']) && isset($_POST['type']) ) {

                if ($store_id === NULL && $product_id == NULL && $wpid !== NULL && $type !== NULL) {

                    if ($_POST['type'] !== 'avatar' && $_POST['type'] !== 'banner') {
                        return array(
                            "status" => "failed",
                            "message" => "Invalid value of type."
                        );
                    }

                    if ( !is_numeric($wpid)  ) {
                        return array(
                            "status" => "unknwon",
                            "message" => "Please contact your administrator. Request field is not in valid format.",

                        );
                    }

                    $result = DV_Globals::upload_image( $request, $files);

                    if ($result['status'] != 'success') {
                        return array(
                            "status" => $result['status'],
                            "message" => $result['message']
                        );

                    }

                    $user_avatar = update_user_meta( $wpid,  $type,  $result['data'] );

                    if ($user_avatar == false) {
                        return array(
                            "status" => "failed",
                            "message" => "An error occured while submitting data to server.",

                        );
                    }

                    if ($result['status'] == 'success') {
                        return array(
                            "status" => $result['status'],
                            "data" => $result['data']

                        );

                    }

                }
            }

            else if (isset($_POST['stid']) && isset($_POST['type']) && !isset($_POST["pdid"]) ) {

                if ($_POST['type'] !== 'logo' && $_POST['type'] !== 'banner') {
                    return array(
                        "status" => "failed",
                        "message" => "Invalid value of type."
                    );
                }


                if ($store_id !== NULL && $product_id == NULL && $type !== NULL) {
                    $check_store = $wpdb->get_row("SELECT str.ID, child_val as `status` FROM  tp_stores str INNER JOIN tp_revisions rev ON rev.ID = str.`status` WHERE str.ID = '$store_id'");

                    if ($check_store->status == '0') {
                        return array(
                            "status" => "failed",
                            "message" => "This store is currently inactive."
                        );
                    }
                    if (!$check_store) {
                        return array(
                            "status" => "failed",
                            "message" => "This store does not exists."
                        );
                    }

                    if (empty($_POST['stid']) || empty($_POST['type']) ) {
                        return array(
                            "status" => "unknown",
                            "message" => "Please contact your administrator. Request unknown."
                        );
                    }

                    if ($type == 'logo') {

                        $result = DV_Globals::upload_image( $request, $files);

                        if ($result['status'] != 'success' || $result['status'] == "unknown") {
                            return array(
                                "status" => $result['status'],
                                "message" => $result['message']
                            );

                        }

                        if(empty($result['data'])){
                            return array(
                                "status" => "failed",
                                "status" => "Please contact your administrator. Uploading image does not indicates success. Error Code 404"
                            );
                        }

                        $link = $result['data'];

                        $wpdb->query("START TRANSACTION");
                            $store_img = $wpdb->query("INSERT INTO tp_revisions ( `revs_type`, `parent_id`, `child_key`, `child_val`, `created_by`, `date_created` )
                                            VALUES ( 'stores', '$store_id', 'logo', '$link', '$wpid', '$date') ");
                            $store_img_ID = $wpdb->insert_id;

                            $update_store = $wpdb->query(" UPDATE tp_stores SET `logo` = '$store_img_ID' WHERE ID = '$store_id' ");

                        if ($store_img < 1 || $store_img_ID < 1 || $update_store < 1 ) {
                            $wpdb->query("ROLLBACK");
                            return array(
                                "status" => "failed",
                                "message" => "An error occured while submmiting data to server."
                            );

                        }else{

                            if ($result['status'] == 'success') {
                                $wpdb->query("COMMIT");

                                return array(
                                    "status" => $result['status'],
                                    "data" => $result['data'],
                                    "message" => ucfirst($type) . ' has been added successfully'
                                );
                            }
                        }

                    }else{

                        $result = DV_Globals::upload_image( $request, $files);

                        if ($result['status'] != 'success') {
                            return array(
                                "status" => $result['status'],
                                "message" => $result['message']
                            );

                        }

                        $link = $result['data'];

                        $wpdb->query("START TRANSACTION");
                            $store_img = $wpdb->query("INSERT INTO tp_revisions ( `revs_type`, `parent_id`, `child_key`, `child_val`, `created_by`, `date_created` )
                                            VALUES ( 'stores', '$store_id', 'banner', '$link', '$wpid', '$date') ");
                            $store_img_ID = $wpdb->insert_id;

                            $update_store = $wpdb->query(" UPDATE tp_stores SET `banner` = '$store_img_ID' WHERE ID = '$store_id' ");

                        if ($store_img < 1 || $store_img_ID < 1 || $update_store < 1 ) {
                            $wpdb->query("ROLLBACK");
                            return array(
                                "status" => "failed",
                                "message" => "An erro occured while submmiting data to server."
                            );

                        }else{

                            if ($result['status'] == 'success') {
                                $wpdb->query("COMMIT");

                                return array(
                                    "status" => $result['status'],
                                    "data" => $result['data'],
                                    "message" => ucfirst($type) . ' has been added successfully'
                                );
                            }
                        }
                    }
                }
            }

            else if (isset($_POST['pdid']) && isset($_POST['type']) ) {

                if ($_POST['type'] !== 'logo' && $_POST['type'] !== 'banner') {
                    return array(
                        "status" => "failed",
                        "message" => "Invalid value of type."
                    );
                }


                if ($store_id !== NULL && $product_id !== NULL && $type !== NULL) {

                    $check_product = $wpdb->get_row("SELECT prod.ID, rev.child_val as `status`  FROM tp_products prod INNER JOIN tp_revisions rev ON rev.ID = prod.`status` WHERE prod.ID = '$product_id' AND prod.stid = '$store_id' ");

                    if (!$check_product) {
                        return array(
                            "status" => "failed",
                            "message" => "This product does not exists."
                        );
                    }

                    if ($check_product->status == '0') {
                        return array(
                            "status" => "failed",
                            "message" => "This product is currently inactive."
                        );
                    }

                    $result = DV_Globals::upload_image( $request, $files);

                    if ($result['status'] != 'success' || $result['status'] == "unknown") {
                        return array(
                            "status" => $result['status'],
                            "message" => $result['message'],

                        );

                    }

                    $link = $result['data'];


                    if ($type == 'logo') {

                        $wpdb->query("START TRANSACTION");
                            $product_img = $wpdb->query("INSERT INTO tp_revisions ( `revs_type`, `parent_id`, `child_key`, `child_val`, `created_by`, `date_created` )
                                            VALUES ( 'products', '$product_id', 'preview', '$link', '$wpid', '$date') ");
                            $product_img_id = $wpdb->insert_id;

                            $update_product_data = $wpdb->query("UPDATE tp_products SET `preview` = $product_img_id  WHERE ID = '$product_id'  ");


                        if ($product_img < 1 || $update_product_data < 1 ) {
                            $wpdb->query("ROLLBACK");
                            return array(
                                "status" => "failed",
                                "message" => "An erro occured while submmiting data to server."
                            );

                        }else{

                            if ($result['status'] == 'success') {
                                $wpdb->query("COMMIT");

                                return array(
                                    "status" => $result['status'],
                                    "data" => $result['data'],
                                    "message" => ucfirst($type) . ' has been added successfully'
                                );
                            }
                        }

                    }
                }
            }

            else {
                return array(
                    "status" => "unknown",
                    "message" => "Please contact your administrator. Unknown Request.",
                );
            }
        }

    }