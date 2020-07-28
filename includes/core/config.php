<?php
	// Exit if accessed directly
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	/**
	 * @package datavice-wp-plugin
     * @version 0.1.0
     * This is where you provide all the constant config.
	*/
?>
<?php

	//Defining Global Variables
	define('DV_PREFIX', 'dv_');
	define('DV_SERVER', 'localhost');
	define('DV_USER', 'root');
	define('DV_PASS', '');
	define('DV_NAME', 'wordpress');
	define('PLUGIN_PATH', plugin_dir_path( __FILE__ ));

	//Initializing table names
	define('DV_ADDRESS_TABLE', DV_PREFIX.'address');
	define('DV_ROLES_TABLE', DV_PREFIX.'roles');
	define('DV_ROLES_META_TABLE', DV_PREFIX.'roles_meta');
	define('DV_ROLES_ACCESS_TABLE', DV_PREFIX.'roles_access');
	define('DV_COUNTRY_TABLE', DV_PREFIX.'countries');
	define('DV_PRV_TABLE', DV_PREFIX.'provinces');
	define('DV_CTY_TABLE', DV_PREFIX.'cities');
	define('DV_BRGY_TABLE', DV_PREFIX.'brgys');


	

	//Initializing table fields
	define('DV_COUNTRY_FIELDS', '*');
	define('DV_PRV_FIELDS', 'id, prov_name as prov');
	define('DV_CTY_FIELDS', '*');
	define('DV_BRGY_FIELDS', '*');

	//Initializing table where clause
	define('DV_PRV_WHERE', 'status = 1');
	define('DV_CTY_WHERE', 'status = 1');
	define('DV_BRGY_WHERE', 'status = 1');













?>