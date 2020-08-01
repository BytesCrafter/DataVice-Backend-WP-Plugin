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
  	class DV_Globals {
         
        public static function create($table_name, $data){
            global $wpdb;
        
            return $wpdb->insert($table_name, $data);
                       
        }
        
        //Working
        public static function retrieve($table_name, $fields, $where, $sort_field, $sort){
            global $wpdb;

            return $wpdb->get_results("SELECT $fields FROM $table_name $where $sort_field $sort ");
        }

        /**
         * Not working 
         
            *public static function retrieveById($table_name, $fields, $id){
            *    global $wpdb;
            *    $data = implode( ', ', $fields );
            *    return $data;
            *    // return $wpdb->get_results("SELECT $data FROM $table_name WHERE id = $id ");
            *}
         */

        public static function delete($table_name , $id){
            global $wpdb;
        
            return $wpdb->delete( $table_name, array( 'id' => $id ) );

        }

        public static function update($table_name, $id, $fields){
            global $wpdb;
            
            return $wpdb->update( $table_name , $fields, array('id' => $id) );
        }

        public static function check_by_field($table_name, $key, $value){
            
            global $wpdb;
            
            return $wpdb->get_row("SELECT ID 
                FROM $table_name 
                WHERE $key LIKE '%$value%'");

        }
        // NEW
        public static function insert_usermeta($user_id,  $firstName, $lastName){
            global $wpdb;
            return wp_update_user([
                'ID' => $userId, 
                'first_name' => $firstName,
                'last_name' => $lastName,
            ]);
           
        }


        //User validation
        // public static function validate_user(){
            
        //     //User verification
        //     $verified = DV_Verification::initialize();

        //     //Convert object to array
        //     $array =  (array) $verified;

        //     //Return data
        //     return $array['data'];
            
        // }

        public static function validate_user(){
            $verified = DV_Verification::initialize();
            //Convert object to array
            $array =  (array) $verified;
            // Pass request status in a variable
            $response =  $array['data']['status'];
            if ($response != 'success') {
                    return $verified;
            } else {
                    return true;
            }
        }


          // date stamp 
        public static function date_stamp(){
            // Timezone
            date_default_timezone_set('Asia/Manila');
            // Return Curent DateTime
            return date("Y-m-d h:i:s");

        }
        
       

        
    }
?>