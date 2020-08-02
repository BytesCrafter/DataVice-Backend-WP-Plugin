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
    
    //Include source page for data sources.
    include_once ( DV_PLUGIN_PATH . '/includes/source/geo-countries.php' );
    include_once ( DV_PLUGIN_PATH. '/includes/source/geo-cities.php' );
    include_once ( DV_PLUGIN_PATH . '/includes/source/geo-provinces.php' );
    include_once ( DV_PLUGIN_PATH . '/includes/source/geo-barangays.php' );