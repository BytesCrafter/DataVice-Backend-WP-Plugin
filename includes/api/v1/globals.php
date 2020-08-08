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

        //Declare a private variable for mysql table name
        private $table;
         
        /** Global function for retrieving from database 
		* @param1 = {table name in database};
        * @param2 = {fields to be selected}
        * @param3 = {optional. WHERE clause}
		* @param4 = {optional. ORDER BY field}
		* @param5 = {optional. ASC or DESC}
	    */
        public static function retrieve($table_name, $fields, $where = NULL, $sort_field = NULL , $sort = NULL){
            global $wpdb;

            $table = $table_name;

            return $wpdb->get_results("SELECT $fields FROM $table $where $sort_field $sort ");
        }

        // Return Current DateTime
        public static function date_stamp(){
            return date("Y-m-d h:i:s");
        }

        // TODO: Convert the date
        public static function convert_user_datetime($tzone_id, $date_time) {
            //STEP 1 - Get timezone name from tzone ID.
            //step 2 - USe that tzone to convert date_time to current timezone.
        }

     
        /** Checking if $id has row. Returns boolean
	    * Returns true if  row found, false if not found, "unavail" if status is not active
		* @param1 = {table name in database};
        * @param2 = {id key}
		* @param3 = {optional. additional where clause}
	    */
        public static function check_availability($table_name, $where){
            global $wpdb;

            $table = $table_name;

            $result = $wpdb->get_row("SELECT id, status FROM $table $where");
            
            if (!$result) {
                return false;
            } else if ($result->status == 0){
                return "unavail";
            } else {
                return true;
            }
        }

        public static function check_roles($role){
            
            $wp_user = get_userdata($_POST['wpid']);
            
            if ( in_array($role , $wp_user->roles, true) ) {
                return true;
            }

            return false;
        }


        


    } // end of class
?>