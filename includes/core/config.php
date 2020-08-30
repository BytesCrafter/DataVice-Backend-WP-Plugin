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
	

	//Defining Global Variables
	define('DV_PREFIX', 'dv_');
	define('DV_MASTER', 'masterkey');
	
	// Maximum file size for uploading image
	define('DV_UPLOAD_SIZE', 5000000);


	define('WP_PREFIX', 'wp_');
	
	// Wordpress CONSTANT
	define('WP_USERS_META', WP_PREFIX.'usermeta');
	define('WP_USERS', WP_PREFIX.'users');


	
	// Primary Table Constant
	define('DV_CONTACTS_TABLE', DV_PREFIX.'contacts');
	define('DV_ADDRESS_TABLE', DV_PREFIX.'address');
	define('DV_REVS_TABLE', DV_PREFIX.'revisions');

	//Insert Fields
	define('DV_INSERT_REV_FIELDS', '`revs_type`, `child_key`, `child_val`, `created_by`, `date_created`');
	define('DV_INSERT_ADDRESS_FIELDS', '`status`, `wpid`, `types`, `street`, `brgy`, `city`, `province`, `country`, `date_created`');
	

	// Config CONSTANT
	define('DV_CONFIG_TABLE', DV_PREFIX.'configs');
	define("DV_CONFIG_DATA", $dv_config_list);
	define("DV_CONFIG_VALUE", $dv_config_val);
	define("DV_CONFIG_FIELD", "(title, info, config_key, config_val)");

	// Country CONSTANT
	define('DV_COUNTRY_FIELDS', 'country_code as code, country_name as name');
	define('DV_COUNTRY_WHERE', 'WHERE status = 1');
	define('DV_COUNTRY_TABLE', DV_PREFIX.'geo_countries');
    define("DV_COUNTRY_DATA", $dv_country_list);
	define("DV_COUNTRY_FIELD", "(country_code, country_name)");

	// Province CONSTANT
	define('DV_PROVINCE_FIELDS', 'prov_code as code, prov_name as name');
	define('DV_PROVINCE_WHERE', 'WHERE status = 1 AND country_code = ');
	define('DV_PROVINCE_TABLE', DV_PREFIX.'geo_provinces');
	define("DV_PROVINCE_DATA", $dv_province_list);
	define("DV_PROVINCE_FIELD", "(country_code, prov_code, prov_name)");

	// City CONSTANT
	define('DV_CITY_FIELDS', 'city_code as code, city_name as name');
	define('DV_CITY_WHERE', 'WHERE status = 1 AND prov_code = ');
	define('DV_CITY_TABLE', DV_PREFIX.'geo_cities');
    define("DV_CITY_DATA", $dv_city_list);
	define("DV_CITY_FIELD", "(prov_code, city_code, city_name)");

	// Barangay CONSTANT
	define('DV_BRGY_FIELDS', 'id as code, brgy_name as name');
	define('DV_BRGY_WHERE', 'WHERE status = 1 AND city_code =');
	define('DV_BRGY_TABLE', DV_PREFIX.'geo_brgys');
	define("DV_BRGY_DATA", $dv_brgy_list);
	define("DV_BRGY_FIELD", "(brgy_name, city_code)");

	// Timezone CONSTANTS
	define('DV_TZ_TABLE', DV_PREFIX.'geo_timezone');
	define("DV_TZ_FIELD", "(country_code, tzone_name, utc_offset, utc_dst_offset)");
	define("DV_TZ_DATA", $dv_tz_list);

	// Events CONSTANTS
	define('DV_EVENTS_TABLE', DV_PREFIX.'events');
	define("DV_EVENTS_FIELDS", "(`wpid`, `keys`, `info`)");

