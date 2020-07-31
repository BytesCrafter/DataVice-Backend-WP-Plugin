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
	define('DV_PLUGIN_PATH', plugin_dir_path( __FILE__ ));

	//Initializing table names
	define('DV_ADDRESS_TABLE', DV_PREFIX.'address');
	define('DV_ROLES_TABLE', DV_PREFIX.'roles');
	define('DV_ROLES_META_TABLE', DV_PREFIX.'roles_meta');
	define('DV_ROLES_ACCESS_TABLE', DV_PREFIX.'roles_access');
	define('DV_COUNTRY_TABLE', DV_PREFIX.'geo_countries');
	define('DV_PRV_TABLE', DV_PREFIX.'geo_provinces');
	define('DV_CTY_TABLE', DV_PREFIX.'geo_cities');
	define('DV_BRGY_TABLE', DV_PREFIX.'geo_brgys');
	define('DV_CONFIG_TABLE', DV_PREFIX.'configs');
	define('DV_CONTACTS_TABLE', DV_PREFIX.'contacts');
	define('DV_REVS_TABLE', DV_PREFIX.'revisions');




	//Initializing table fields
	define('DV_COUNTRY_FIELDS', 'country_code as code, country_name as name');
	define('DV_PRV_FIELDS', 'prov_code as code, prov_name as name');
	define('DV_CTY_FIELDS', 'city_code as code, citymun_name as name');
	define('DV_BRGY_FIELDS', 'id as code, brgy_name as name');

	//Initializing table where clause
	define('DV_CTRY_WHERE', 'WHERE status = 1');
	define('DV_PRV_WHERE', 'WHERE status = 1 AND country_code = ');
	define('DV_CTY_WHERE', 'WHERE status = 1 AND prov_code = ');
	define('DV_BRGY_WHERE', 'WHERE status = 1 AND city_code =');















?>