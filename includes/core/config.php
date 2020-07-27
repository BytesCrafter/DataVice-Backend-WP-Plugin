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

?>