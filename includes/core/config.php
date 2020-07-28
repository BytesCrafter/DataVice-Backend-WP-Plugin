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
	define('ADDRESS_TABLE', DV_PREFIX.'address');
	define('ROLES_TABLE', DV_PREFIX.'roles');
	define('ROLES_META_TABLE', DV_PREFIX.'roles_meta');
	define('ROLES_ACCESS_TABLE', DV_PREFIX.'roles_access');
	define('COUNTRY_TABLE', DV_PREFIX.'countries');
	define('PRV_TABLE', DV_PREFIX.'provinces');
	define('CTY_TABLE', DV_PREFIX.'cities');
	define('BRGY_TABLE', DV_PREFIX.'brgys');


	

	//Initializing table fields
	define('COUNTRY_FIELDS', '*');
	define('PRV_FIELDS', 'id, prov_name as prov');
	define('CTY_FIELDS', '*');
	define('BRGY_FIELDS', '*');











?>