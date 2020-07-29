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
    class DV_Contact{
        public static function add_contact(){
            global $wpdb;

            $wpid = $_POST['wpid'];
            $snky = $_POST['snky'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];
            $contact_type = $_POST['type'];
            $revs_type = 'contacts';
            $date_stamp = DV_Globals::date_stamp();
            // child keys
            $ck_phone = 'phone';
            $ck_email = 'email';
            
            if(!isset($_POST['ec_name']) ){

                if ($_POST['type'] != "personal") {
                    return rest_ensure_response( 
                        array(
                                "status" => "unknown",
                                "message" => "Please contact your Administrator. Parameters Unknown.",
                        )
                    );
                }
                
                $wpdb->query("START TRANSACTION ");

                    $query_contact = $wpdb->query("INSERT INTO dv_contacts (`type`, created_by, date_created  ) VALUES ('$contact_type', $wpid, '$date_stamp')");
                        $parent_id = $wpdb->insert_id;
                    $query_phone = $wpdb->query("INSERT INTO dv_revisions (revs_type, parent_id, child_key, child_val, created_by, date_created) 
                                                            VALUES ( '$revs_type', $parent_id, '$ck_phone', '$phone', $wpid, '$date_stamp'  )");
                        $phone_id = $wpdb->insert_id;
                    $query_email = $wpdb->query("INSERT INTO dv_revisions (revs_type, parent_id, child_key, child_val, created_by, date_created) 
                                                            VALUES ( '$revs_type', $parent_id, '$ck_email', '$email', $wpid, '$date_stamp'  )");
                        $email_id = $wpdb->insert_id;
                  
                    $query_update = $wpdb->query("UPDATE dv_contacts SET phone = $phone_id , email = $email_id WHERE ID = $parent_id   ");

                $wpdb->query("COMMIT");

                if($query_contact < 0 || $query_phone < 0 || $query_email < 0 ||  $query_update < 0 ){
                    return rest_ensure_response( 
                        array(
                                "status" => "failed",
                                "message" => "Please contact your Administrator. Submition failed",
                        )
                    );
                }

                return rest_ensure_response( 
                    array(
                            "status" => "Success",
                            "message" => "Contact Created Successfully!.",
                    )
                );

            }else{

                if ($_POST['type'] != "emergency") {
                    return rest_ensure_response( 
                        array(
                                "status" => "unknown",
                                "message" => "Please contact your Administrator. Parameters Unknown.",
                        )
                    );
                }
                
                $ec_name = $_POST['ec_name'];
                $ck_emergency_name = 'name';

                
                $wpdb->query("START TRANSACTION ");

                    $query_contact = $wpdb->query("INSERT INTO dv_contacts (`type`, created_by, date_created  ) VALUES ('$contact_type', $wpid, '$date_stamp')");
                        $parent_id = $wpdb->insert_id;
                    $query_phone = $wpdb->query("INSERT INTO dv_revisions (revs_type, parent_id, child_key, child_val, created_by, date_created) 
                                                            VALUES ( '$revs_type', $parent_id, '$ck_phone', '$phone', $wpid, '$date_stamp'  )");
                        $phone_id = $wpdb->insert_id;
                    $query_email = $wpdb->query("INSERT INTO dv_revisions (revs_type, parent_id, child_key, child_val, created_by, date_created) 
                                                            VALUES ( '$revs_type', $parent_id, '$ck_email', '$email', $wpid, '$date_stamp'  )");
                        $email_id = $wpdb->insert_id;
                    $query_ec_name = $wpdb->query("INSERT INTO dv_revisions (revs_type, parent_id, child_key, child_val, created_by, date_created) 
                                                            VALUES ( '$revs_type', $parent_id, '$ck_emergency_name', '$ec_name', $wpid, '$date_stamp'  )");
                    $query_update = $wpdb->query("UPDATE dv_contacts SET phone = $phone_id , email = $email_id WHERE ID = $parent_id   ");

                $wpdb->query("COMMIT");

                if($query_contact < 0 || $query_phone < 0 || $query_email < 0 || $query_ec_name < 0 || $query_update < 0 ){
                    return rest_ensure_response( 
                        array(
                                "status" => "failed",
                                "message" => "Please contact your Administrator. Submition failed",
                        )
                    );
                }
                return rest_ensure_response( 
                    array(
                            "status" => "Success",
                            "message" => "Contact Created Successfully!.",
                    )
                );
                
            }





            
        }
    }