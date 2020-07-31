<?php
	// Exit if accessed directly
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	/**
	 * @package datavice-wp-plugin
     * @version 0.1.0
     * Here is where you add hook to WP to create our custom database if not found.
	*/
?>
<?php

	function dv_dbhook_activate(){
		
		//Array for sql files
		//If adding new files, pls follow the format provided
		// $sql_files = array('\dv_geo_brgys.sql', '\dv_geo_cities.sql', '\dv_geo_countries.sql', '\dv_geo_provinces.sql' );

		// //Loop through the array and pass the sql filename to the importing function
		// for ($i=0; $i < count($sql_files); $i++) { 
		// 	file_importing($sql_files[$i]);
		// }

		global $wpdb;

		//Passing from global defined variable to local variable
		$tbl_address = DV_ADDRESS_TABLE;
		$tbl_roles = DV_ROLES_TABLE;
		$tbl_roles_meta = DV_ROLES_META_TABLE;
		$tbl_roles_access = DV_ROLES_ACCESS_TABLE;
		$tbl_configs = DV_CONFIG_TABLE;
		$tbl_contacts = DV_CONTACTS_TABLE;
		$tbl_revs = DV_REVS_TABLE;
		$tbl_countries = DV_COUNTRY_TABLE;
		$tbl_prv = DV_PRV_TABLE;
		$tbl_cty = DV_CTY_TABLE;

		$tbl_brgy = DV_BRGY_TABLE;

		//Database table creation for dv_revisions
		if($wpdb->get_var( "SHOW TABLES LIKE '$tbl_revs'" ) != $tbl_revs) {
			$sql = "CREATE TABLE `".$tbl_revs."` (";
				$sql .= "`ID` bigint(20) NOT NULL AUTO_INCREMENT, ";
				$sql .= "`revs_type` enum('none','configs','address','contacts') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Target table', ";
				$sql .= "`parent_id` bigint(20) NOT NULL DEFAULT 0 COMMENT 'Parent ID of this Revision',";
				$sql .= "`child_key` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Column name on the table', ";
				$sql .= "`child_val` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Text Value of the row Key.', ";
				$sql .= "`created_by` bigint(20) NOT NULL DEFAULT 0 COMMENT 'User ID created this Revision.', ";
				$sql .= "`date_created` datetime(0) NULL DEFAULT NULL COMMENT 'The date this Revision is created.', ";
				$sql .= "PRIMARY KEY (`ID`) ";
				$sql .= ") ENGINE = InnoDB; ";
			$result = $wpdb->get_results($sql);
		}

		//Database table creation for dv_contacts
		if($wpdb->get_var( "SHOW TABLES LIKE '$tbl_contacts'" ) != $tbl_contacts) {
			$sql = "CREATE TABLE `".$tbl_contacts."` (";
				$sql .= "`ID` bigint(20) NOT NULL AUTO_INCREMENT, ";
				$sql .= "`status` ENUM('active','inactive') NOT NULL, ";
				$sql .= "`phone` VARCHAR(15) NOT NULL, ";
				$sql .= "`email` VARCHAR(50) NOT NULL, ";
				$sql .= "`created_by` int(11) NOT NULL, ";
				$sql .= "`date_created` datetime NOT NULL, ";
				$sql .= "PRIMARY KEY (`ID`) ";
				$sql .= ") ENGINE = InnoDB; ";
			$result = $wpdb->get_results($sql);
		}

		//Database table creation for dv_configs
		if($wpdb->get_var( "SHOW TABLES LIKE '$tbl_configs'" ) != $tbl_configs) {
			$sql = "CREATE TABLE `".$tbl_configs."` (";
				$sql .= "`ID` bigint(20) NOT NULL AUTO_INCREMENT, ";
				$sql .= "`config_desc` varchar(255) NOT NULL COMMENT 'Config Description', ";
				$sql .= "`config_key` varchar(50) NOT NULL COMMENT 'Config KEY',";
				$sql .= "`config_value` bigint(20) NOT NULL DEFAULT 0 COMMENT 'Config VALUES', ";
				$sql .= "PRIMARY KEY (`ID`) ";
				$sql .= ") ENGINE = InnoDB; ";
			$result = $wpdb->get_results($sql);
		}


		if($wpdb->get_var( "SHOW TABLES LIKE '$tbl_address'" ) != $tbl_address) {
			$sql = "CREATE TABLE `".$tbl_address."` (";
				$sql .= "`ID` bigint(20) NOT NULL AUTO_INCREMENT, ";
				$sql .= "`wpid` bigint(20) NOT NULL DEFAULT 0 COMMENT 'User ID, 0 if Null', ";
				$sql .= "`stid` bigint(20) NOT NULL DEFAULT 0 COMMENT 'Store ID, 0 if Null', ";
				$sql .= "`types` enum('none','home','office','business') NOT NULL COMMENT 'Set this instance what type of address.', ";
				$sql .= "`status` bigint(12) NOT NULL DEFAULT 0 COMMENT 'Value of active or inactive, 0 being inactive', ";
				$sql .= "`street` bigint(20) NOT NULL DEFAULT 0 COMMENT 'Street address active revision ID, 0 if Null', ";
				$sql .= "`brgy` bigint(20) NOT NULL DEFAULT 0 COMMENT 'Barangay active revision ID, 0 if Null', ";
				$sql .= "`city` bigint(20) NOT NULL DEFAULT 0 COMMENT 'City active revision ID, 0 if Null', ";
				$sql .= "`province` bigint(20) NOT NULL DEFAULT 0 COMMENT 'Province active revision ID, 0 if Null', ";
				$sql .= "`country` bigint(20) NOT NULL DEFAULT 0 COMMENT 'Country active revision ID, 0 if Null', ";
				$sql .= "`date_created` datetime DEFAULT NULL COMMENT 'The date this address is created.', ";
				$sql .= "PRIMARY KEY (`ID`) ";
				$sql .= ") ENGINE = InnoDB; ";
			$result = $wpdb->get_results($sql);
		}

		//Database table creation for dv_geo_countries
		if($wpdb->get_var( "SHOW TABLES LIKE '$tbl_countries'" ) != $tbl_countries) {
			$sql = "CREATE TABLE `".$tbl_countries."` (";
				$sql .= "`ID` bigint(20) NOT NULL AUTO_INCREMENT, ";
				$sql .= "`country_code` varchar(2) NOT NULL DEFAULT '', ";
				$sql .= "`country_name` varchar(100) NOT NULL DEFAULT '', ";
				$sql .= "`status` tinyint(4) NOT NULL DEFAULT 0, ";
				$sql .= "PRIMARY KEY (`ID`) ";
				$sql .= ") ENGINE = InnoDB; ";
			$result = $wpdb->get_results($sql);
			
			//Pass the globally defined constant to a variable
			$ctry_list = CTRY_LIST;
			$ctry_fields = CTRY_DATA_FIELDS;

			//Dumping data into tables
			$wpdb->query("INSERT INTO `".$tbl_countries."` $ctry_fields VALUES $ctry_list");

		}

		//Database table creation for dv_geo_provinces
		if($wpdb->get_var( "SHOW TABLES LIKE '$tbl_prv'" ) != $tbl_prv) {
			$sql = "CREATE TABLE `".$tbl_prv."` (";
				$sql .= "`ID` bigint(20) NOT NULL AUTO_INCREMENT, ";
				$sql .= "`prov_name` text DEFAULT NULL, ";
				$sql .= "`prov_code` varchar(255) DEFAULT NULL, ";
				$sql .= "`country_code` varchar(2) DEFAULT NULL, ";
				$sql .= "`status` tinyint(4) NOT NULL DEFAULT 0, ";
				$sql .= "PRIMARY KEY (`ID`) ";
				$sql .= ") ENGINE = InnoDB; ";
			$result = $wpdb->get_results($sql);
			
			//Pass the globally defined constant to a variable
			$prov_list = PROV_LIST;
			$prov_fields = PROV_DATA_FIELDS;

			//Dumping data into tables
			$wpdb->query("INSERT INTO `".$tbl_prv."` $prov_fields VALUES $prov_list");



		}

		//Database table creation for dv_geo_cities
		if($wpdb->get_var( "SHOW TABLES LIKE '$tbl_cty'" ) != $tbl_cty) {
			$sql = "CREATE TABLE `".$tbl_cty."` (";
				$sql .= "`ID` bigint(20) NOT NULL AUTO_INCREMENT, ";
				$sql .= "`citymun_name` text DEFAULT NULL, ";
				$sql .= "`prov_code` varchar(255) DEFAULT NULL,";
				$sql .= "`city_code` varchar(255) DEFAULT NULL, ";
				$sql .= "`status` tinyint(4) NOT NULL DEFAULT 0, ";
				$sql .= "PRIMARY KEY (`ID`) ";
				$sql .= ") ENGINE = InnoDB; ";
			$result = $wpdb->get_results($sql);
			
			//Pass the globally defined constant to a variable
			$cty_list = CTY_LIST;
			$cty_fields = CTY_DATA_FIELDS;

			//Dumping data into tables
			$wpdb->query("INSERT INTO `".$tbl_cty."` $cty_fields VALUES $cty_list");

		}

		//Database table creation for dv_geo_brgys
		if($wpdb->get_var( "SHOW TABLES LIKE '$tbl_brgy'" ) != $tbl_brgy) {
			$sql = "CREATE TABLE `".$tbl_brgy."` (";
				$sql .= "`ID` bigint(20) NOT NULL AUTO_INCREMENT, ";
				$sql .= "`brgy_name` text DEFAULT NULL, ";
				$sql .= "`prov_code` varchar(255) DEFAULT NULL,";
				$sql .= "`city_code` varchar(255) DEFAULT NULL, ";
				$sql .= "`status` tinyint(4) NOT NULL DEFAULT 0, ";
				$sql .= "PRIMARY KEY (`ID`) ";
				$sql .= ") ENGINE = InnoDB; ";
			$result = $wpdb->get_results($sql);
			
			//Pass the globally defined constant to a variable
			$brgy_list = BRGY_LIST;
			$brgy_fields = BRGY_DATA_FIELDS;
			
			//Dumping data into tables
			$wpdb->query("INSERT INTO `".$tbl_brgy."` $brgy_fields VALUES $brgy_list");

		}

	}


    add_action( 'activated_plugin', 'dv_dbhook_activate' );



?>