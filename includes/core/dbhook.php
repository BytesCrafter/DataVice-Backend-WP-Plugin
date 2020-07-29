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
		$sql_files = array('\dv_geo_brgys.sql', '\dv_geo_cities.sql', '\dv_geo_countries.sql', '\dv_geo_provinces.sql' );

		//Loop through the array and pass the sql filename to the importing function
		for ($i=0; $i < count($sql_files); $i++) { 
			file_importing($sql_files[$i]);
		}

		global $wpdb;

		//Passing from global defined variable to local variable
		$tbl_address = DV_ADDRESS_TABLE;
		$tbl_roles = DV_ROLES_TABLE;
		$tbl_roles_meta = DV_ROLES_META_TABLE;
		$tbl_roles_access = DV_ROLES_ACCESS_TABLE;
		$tbl_configs = DV_CONFIG_TABLE;
		$tbl_contacts = DV_CONTACTS_TABLE;


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

		
		
	}


	//Function for importing .sql files
	function file_importing($sql_table){

		$server  =  DV_SERVER; 
		$username   = DV_USER; 
		$password   = DV_PASS;  
		$database = DV_NAME;

		/* PDO connection start */
		$conn = new PDO("mysql:host=$server; dbname=$database", $username, $password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);         
		$conn->exec("SET CHARACTER SET utf8");     
		/* PDO connection end */

		// your config
		$filename = untrailingslashit(DV_PLUGIN_PATH) . '\sql-files' . $sql_table;

		$maxRuntime = 8; // less then your max script execution limit


		$deadline = time()+$maxRuntime; 
		$progressFilename = $filename.'_filepointer'; // tmp file for progress
		$errorFilename = $filename.'_error'; // tmp file for erro



		($fp = fopen($filename, 'r')) OR die('failed to open file:'.$filename);

		// check for previous error
		if( file_exists($errorFilename) ){
			die('<pre> previous error: '.file_get_contents($errorFilename));
		}

		// activate automatic reload in browser
		echo '<html><head> <meta http-equiv="refresh" content="'.($maxRuntime+2).'"><pre>';

		// go to previous file position
		$filePosition = 0;
		if( file_exists($progressFilename) ){
			$filePosition = file_get_contents($progressFilename);
			fseek($fp, $filePosition);
		}

		$queryCount = 0;
		$query = '';
		while( $deadline>time() AND ($line=fgets($fp, 1024000)) ){
			if(substr($line,0,2)=='--' OR trim($line)=='' ){
				continue;
			}

			$query .= $line;
			if( substr(trim($query),-1)==';' ){

				$igweze_prep= $conn->prepare($query);

				if(!($igweze_prep->execute())){ 
					$error = 'Error performing query \'<strong>' . $query . '\': ' . print_r($conn->errorInfo());
					file_put_contents($errorFilename, $error."\n");
					exit;
				}
				$query = '';
				// file_put_contents($progressFilename, ftell($fp)); // save the current file position for 
				$queryCount++;
			}
		}

		if( feof($fp) ){
			echo 'Files successfully imported!';
		}else{
			echo ftell($fp).'/'.filesize($filename).' '.(round(ftell($fp)/filesize($filename), 2)*100).'%'."\n";
			echo $queryCount.' queries processed! please reload or wait for automatic browser refresh!';
		}
	
	
	} // end of function

    add_action( 'activated_plugin', 'dv_dbhook_activate' );



?>