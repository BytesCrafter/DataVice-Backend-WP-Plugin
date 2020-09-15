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

	function dv_dbhook_activate(){

		// Initialized WordPress core.
		global $wpdb;

		//Passing from global defined variable to local variable
		$tbl_configs = DV_CONFIG_TABLE;
		$tbl_contacts = DV_CONTACTS_TABLE;
		$tbl_address = DV_ADDRESS_TABLE;
		$tbl_revs = DV_REVS_TABLE;
		$tbl_events = DV_EVENTS_TABLE;
		$tbl_configs = DV_CONFIG_TABLE;
		$tbl_docu = DV_DOCUMENTS;
		$tbl_link_acc = DV_LINK_ACCOUNT;
		$tbl_error_log = DV_ERROR_LOG;

		$wpdb->query("START TRANSACTION ");

		$get_last_pocket = $wpdb->get_row(" SHOW VARIABLES LIKE 'max_allowed_packet'; ");

		$wpdb->query("SET GLOBAL max_allowed_packet=12582912;");

		if($wpdb->get_var( "SHOW TABLES LIKE '$tbl_configs'" ) != $tbl_configs) {
			$sql = "CREATE TABLE `".$tbl_configs."` (";
				$sql .= "`ID` bigint(20) NOT NULL AUTO_INCREMENT, ";
				$sql .= "`hash_id` varchar(255) NOT NULL , ";
				$sql .= "`title` varchar(255) NOT NULL, ";
				$sql .= "`info` varchar(255) NOT NULL, ";
				$sql .= "`config_key` varchar(50) NOT NULL,";
				$sql .= "`config_val` bigint(20) NOT NULL DEFAULT 0, ";
				$sql .= "PRIMARY KEY (`ID`) ";
				$sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; ";
			$result = $wpdb->get_results($sql);

			//Pass the globally defined constant to a variable
			$conf_list = DV_CONFIG_DATA;
			$conf_fields = DV_CONFIG_FIELD;

			//Dumping data into tables(title, info, config_key, config_val,  hash_id) ($conf_fields, hash_id)
			$wpdb->query("INSERT INTO `".$tbl_configs."` (title, info, config_key, config_val,  hash_id) VALUES $conf_list");
		}

		// Database table creation for dv_address - QA: 01/08/2020
		if($wpdb->get_var( "SHOW TABLES LIKE '$tbl_address'" ) != $tbl_address) {
			$sql = "CREATE TABLE `".$tbl_address."` (";
				$sql .= "`ID` bigint(20) NOT NULL AUTO_INCREMENT, ";
				$sql .= "`hash_id` varchar(255) NOT NULL , ";
				$sql .= "`status` bigint(20) NOT NULL DEFAULT 0 COMMENT 'Live/Hiden', ";
				$sql .= "`wpid` bigint(20) NOT NULL DEFAULT 0 COMMENT 'User ID, 0 if Null', ";
				$sql .= "`stid` bigint(20) NOT NULL DEFAULT 0 COMMENT 'Store ID, 0 if Null', ";
				$sql .= "`types` enum('none','home','office','business') NOT NULL DEFAULT 'none' COMMENT 'Group', ";
				$sql .= "`street` bigint(20) NOT NULL DEFAULT 0 COMMENT 'Street address from Revs, 0 if Null', ";
				$sql .= "`brgy` bigint(20) NOT NULL DEFAULT 0 COMMENT 'Barangay code from Revs, 0 if Null', ";
				$sql .= "`city` bigint(20) NOT NULL DEFAULT 0 COMMENT 'CityMun code from Revs, 0 if Null', ";
				$sql .= "`province` bigint(20) NOT NULL DEFAULT 0 COMMENT 'Province code from Revs, 0 if Null', ";
				$sql .= "`country` bigint(20) NOT NULL DEFAULT 0 COMMENT 'Country code from Revs, 0 if Null', ";
				$sql .= "`latitude` bigint(20) NOT NULL DEFAULT 0 COMMENT 'Latitude id from Revs, 0 if Null', ";
				$sql .= "`longitude` bigint(20) NOT NULL DEFAULT 0 COMMENT 'Longitude id from Revs, 0 if Null', ";
				$sql .= "`img_url` bigint(20) NOT NULL DEFAULT 0 COMMENT 'Url id from Revs, 0 if Null', ";
				$sql .= "`date_created` datetime DEFAULT NULL COMMENT 'Date created', ";
				$sql .= "PRIMARY KEY (`ID`) ";
				$sql .= ") ENGINE = InnoDB; ";
			$result = $wpdb->get_results($sql);
		}

		//Database table creation for plugin_config
		if($wpdb->get_var( "SHOW TABLES LIKE '$tbl_docu'" ) != $tbl_docu) {
			$sql = "CREATE TABLE `".$tbl_docu."` (";
				$sql .= "`ID` bigint(20) NOT NULL AUTO_INCREMENT, ";
				$sql .= "`hash_id` varchar(255) NOT NULL COMMENT 'Hash of id.', ";
				$sql .= "`wpid` bigint(20) NOT NULL DEFAULT 0 COMMENT 'Store ID of Merchant', ";
				$sql .= "`parent_id` bigint(20) NOT NULL COMMENT 'Image url of document', ";
				$sql .= " `date_created` datetime DEFAULT current_timestamp() COMMENT 'Date document was created', ";
				$sql .= "PRIMARY KEY (`ID`) ";
				$sql .= ") ENGINE = InnoDB; ";
			$result = $wpdb->get_results($sql);
		}

		// Database table creation for dv_contacts - QA: 01/08/2020
		if($wpdb->get_var( "SHOW TABLES LIKE '$tbl_contacts'" ) != $tbl_contacts) {
			$sql = "CREATE TABLE `".$tbl_contacts."` (";
				$sql .= "`ID` bigint(20) NOT NULL AUTO_INCREMENT, ";
				$sql .= "`hash_id` varchar(255) NOT NULL , ";
				$sql .= "`status` bigint(20) NOT NULL DEFAULT 0 COMMENT 'Live/Hiden', ";
				$sql .= "`wpid` bigint(20) NOT NULL DEFAULT 0 COMMENT 'User ID, 0 if Null', ";
				$sql .= "`stid` bigint(20) NOT NULL DEFAULT 0 COMMENT 'Store ID, 0 if Null', ";
				$sql .= "`types` enum('none','phone','email','emergency') NOT NULL DEFAULT 'none' COMMENT 'Group', ";
				$sql .= "`revs` bigint(20) NOT NULL DEFAULT 0 COMMENT 'Revision ID', ";
				$sql .= "`created_by` bigint(20) NOT NULL, ";
				$sql .= "`date_created` datetime NOT NULL, ";
				$sql .= "PRIMARY KEY (`ID`) ";
				$sql .= ") ENGINE = InnoDB; ";
			$result = $wpdb->get_results($sql);
		}

		//Database table creation for dv_revisions - QA: 01/08/2020
		if($wpdb->get_var( "SHOW TABLES LIKE '$tbl_revs'" ) != $tbl_revs) {
			$sql = "CREATE TABLE `".$tbl_revs."` (";
				$sql .= "`ID` bigint(20) NOT NULL AUTO_INCREMENT, ";
				$sql .= "`hash_id` varchar(255) NOT NULL , ";
				$sql .= "`revs_type` enum('none', 'documents', 'configs','address','contacts') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Plugin Tables', ";
				$sql .= "`parent_id` bigint(20) NOT NULL DEFAULT 0 COMMENT 'Row ID',";
				$sql .= "`child_key` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Column Name', ";
				$sql .= "`child_val` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Precious Value', ";
				$sql .= "`created_by` bigint(20) NOT NULL DEFAULT 0 COMMENT 'User ID of the author', ";
				$sql .= "`date_created` datetime(0) NULL DEFAULT NULL COMMENT 'Date created', ";
				$sql .= "PRIMARY KEY (`ID`) ";
				$sql .= ") ENGINE = InnoDB; ";
			$result = $wpdb->get_results($sql);
			$conf_list_val = DV_CONFIG_VALUE;
			$rev_table = DV_REVS_TABLE;
			$rev_fields = DV_INSERT_REV_FIELDS;

			//Dumping data into tables
			$wpdb->query("INSERT INTO `".$rev_table."` ($rev_fields,  `parent_id`, `hash_id`) VALUES $conf_list_val");
		}

		//Database table creation for dv_geo_countries - QA: 01/08/2020
		$tbl_countries = DV_COUNTRY_TABLE;

		if($wpdb->get_var( "SHOW TABLES LIKE '$tbl_countries'" ) != $tbl_countries) {
			$sql = "CREATE TABLE `".$tbl_countries."` (";
				$sql .= "`ID` bigint(20) NOT NULL AUTO_INCREMENT, ";
				$sql .= "`status` bigint(20) NOT NULL DEFAULT 0, ";
				$sql .= "`country_code` varchar(2) NOT NULL DEFAULT '', ";
				$sql .= "`country_name` varchar(100) NOT NULL DEFAULT '', ";
				$sql .= "PRIMARY KEY (`ID`) ";
				$sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; ";
			$result = $wpdb->get_results($sql);

			//Pass the globally defined constant to a variable
			$ctry_data = DV_COUNTRY_DATA;
			$ctry_fields = DV_COUNTRY_FIELD;

			//Dumping data into tables
			$wpdb->query("INSERT INTO `".$tbl_countries."` $ctry_fields VALUES $ctry_data");
		}

		//Database table creation for dv_geo_provinces - QA: 01/08/2020
		$tbl_province = DV_PROVINCE_TABLE;

		if($wpdb->get_var( "SHOW TABLES LIKE '$tbl_province'" ) != $tbl_province) {
			$sql = "CREATE TABLE `".$tbl_province."` (";
				$sql .= "`ID` bigint(20) NOT NULL AUTO_INCREMENT, ";
				$sql .= "`status` bigint(20) NOT NULL DEFAULT 0, ";
				$sql .= "`country_code` varchar(2) DEFAULT NULL, ";
				$sql .= "`prov_code` varchar(10) DEFAULT NULL, ";
				$sql .= "`prov_name` varchar(100) DEFAULT NULL, ";
				$sql .= "PRIMARY KEY (`ID`) ";
				$sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; ";
			$result = $wpdb->get_results($sql);

			//Pass the globally defined constant to a variable
			$prov_list = DV_PROVINCE_DATA;
			$prov_fields = DV_PROVINCE_FIELD;

			//Dumping data into tables
			$wpdb->query("INSERT INTO `".$tbl_province."` $prov_fields VALUES $prov_list");
		}

		//Database table creation for dv_geo_cities - QA: 01/08/2020
		$tbl_city = DV_CITY_TABLE;

		if($wpdb->get_var( "SHOW TABLES LIKE '$tbl_city'" ) != $tbl_city) {
			$sql = "CREATE TABLE `".$tbl_city."` (";
				$sql .= "`ID` bigint(20) NOT NULL AUTO_INCREMENT, ";
				$sql .= "`status` bigint(20) NOT NULL DEFAULT 0, ";
				$sql .= "`prov_code` varchar(10) DEFAULT NULL,";
				$sql .= "`city_code` varchar(10) DEFAULT NULL, ";
				$sql .= "`city_name` varchar(100) DEFAULT NULL, ";
				$sql .= "PRIMARY KEY (`ID`) ";
				$sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; ";
			$result = $wpdb->get_results($sql);

			//Pass the globally defined constant to a variable
			$cty_list = DV_CITY_DATA;
			$cty_fields = DV_CITY_FIELD;

			//Dumping data into tables
			$wpdb->query("INSERT INTO `".$tbl_city."` $cty_fields VALUES $cty_list");
		}

		//Database table creation for dv_geo_brgys - QA: 01/08/2020
		$tbl_brgy = DV_BRGY_TABLE;

		if($wpdb->get_var( "SHOW TABLES LIKE '$tbl_brgy'" ) != $tbl_brgy) {
			$sql = "CREATE TABLE `".$tbl_brgy."` (";
				$sql .= "`ID` bigint(20) NOT NULL AUTO_INCREMENT, ";
				$sql .= "`status` bigint(20) NOT NULL DEFAULT 1, ";
				$sql .= "`city_code` varchar(10) DEFAULT NULL, ";
				$sql .= "`brgy_name` varchar(100) DEFAULT NULL, ";
				$sql .= "PRIMARY KEY (`ID`) ";
				$sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; ";
			$result = $wpdb->get_results($sql);

			//Pass the globally defined constant to a variable
			$brgy_data = DV_BRGY_DATA;
			$brgy_field = DV_BRGY_FIELD;

			//Dumping data into tables
			$wpdb->query("INSERT INTO `".$tbl_brgy."` $brgy_field VALUES $brgy_data");
		}

		//Database table creation for dv_geo_timezone
		$tbl_timezone = DV_TZ_TABLE;

		if($wpdb->get_var( "SHOW TABLES LIKE '$tbl_timezone'" ) != $tbl_timezone) {
			$sql = "CREATE TABLE `".$tbl_timezone."` (";
				$sql .= "`ID` bigint(20) NOT NULL AUTO_INCREMENT, ";
				$sql .= "`country_code` varchar(2) NOT NULL, ";
				$sql .= "`tzone_name` varchar(50) NOT NULL, ";
				$sql .= "`utc_offset` varchar(10) NULL, ";
				$sql .= "`utc_dst_offset` varchar(10) NULL, ";
				$sql .= "PRIMARY KEY (`ID`) ";
				$sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; ";
			$result = $wpdb->get_results($sql);

			//Pass the globally defined constant to a variable
			$tz_data = DV_TZ_DATA;
			$tz_field = DV_TZ_FIELD;

			//Dumping data into tables
			$wpdb->query("INSERT INTO `".$tbl_timezone."` $tz_field VALUES $tz_data");
		}

		//Database table creation for dv_events
		if($wpdb->get_var( "SHOW TABLES LIKE '$tbl_events'" ) != $tbl_events) {
			$sql = "CREATE TABLE `".$tbl_events."` (";
				$sql .= "`ID` bigint(20) NOT NULL AUTO_INCREMENT, ";
				$sql .= "`wpid` bigint(20) NOT NULL DEFAULT 0 COMMENT 'User id of the owner of this event',";
				$sql .= "`keys` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Key of the event', ";
				$sql .= "`info` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Additional Information on the event', ";
				$sql .= "`date_created` datetime DEFAULT current_timestamp() COMMENT 'The date this event is created.', ";
				$sql .= "PRIMARY KEY (`ID`) ";
				$sql .= ") ENGINE = InnoDB; ";
			$result = $wpdb->get_results($sql);
		}

		//Database table creation for link ACCOUNT
		if($wpdb->get_var( "SHOW TABLES LIKE '$tbl_link_acc'" ) != $tbl_link_acc) {
			$sql = "CREATE TABLE `".$tbl_link_acc."` (";
				$sql .= " `ID` bigint(20) NOT NULL AUTO_INCREMENT, ";
				$sql .= " `hash_id` bigint(20) NOT NULL DEFAULT 0 COMMENT 'User id of the owner of this event',";
				$sql .= " `wpid` bigint(20) NOT NULL,";
				$sql .= " `platform` enum('facebook','google') NOT NULL, ";
				$sql .= " `token` varchar(255) NOT NULL, ";
				$sql .= " `status` enum('1','0') DEFAULT '1' NOT NULL, ";
				$sql .= " `date_created` datetime NOT NULL DEFAULT current_timestamp(),";
				$sql .= "PRIMARY KEY (`ID`) ";
				$sql .= ") ENGINE = InnoDB; ";
			$result = $wpdb->get_results($sql);
		}

		//Database table creation for error log
		if($wpdb->get_var( "SHOW TABLES LIKE '$tbl_error_log'" ) != $tbl_error_log) {
			$sql = "CREATE TABLE `".$tbl_error_log."` (";
				$sql .= "  `ID` bigint(20) NOT NULL AUTO_INCREMENT, ";
				$sql .= "  `hash_id` varchar(255) NOT NULL DEFAULT 0 COMMENT 'User id of the owner of this event',";
				$sql .= "  `platform` varchar(50) NOT NULL, ";
				$sql .= "  `device_ip` varchar(50) NOT NULL, ";
				$sql .= "  `public_ip` varchar(50) NOT NULL, ";
				$sql .= "  `error_key` varchar(255) DEFAULT NULL, ";
				$sql .= "  `error_code` varchar(255) DEFAULT NULL, ";
				$sql .= "  `status` tinyint(2) NOT NULL, ";
				$sql .= "  `date_created` datetime NOT NULL DEFAULT current_timestamp(),";
				$sql .= "PRIMARY KEY (`ID`) ";
				$sql .= ") ENGINE = InnoDB; ";
			$result = $wpdb->get_results($sql);
		}



		$wpdb->query("SET GLOBAL max_allowed_packet=$get_last_pocket->value;");

		$wpdb->query("COMMIT");
	}
    add_action( 'activated_plugin', 'dv_dbhook_activate' );