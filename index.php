<?php

    /*
        * Plugin Name   DataVice Backend
        * @package      bytescrafter-datavice-backend
        * @author       Bytes Crafter

        * @copyright    2020 Bytes Crafter
        * @version      0.1.0

        * @wordpress-plugin
        * WC requires at least: 2.5.0
        * WC tested up to: 5.3.2

        Plugin Name: DataVice Backend
        Plugin URI: http://www.bytes-crafter.com/projects/datavice
        Description: DataVice backend using the WordPress framework.
        Version: 0.1.0
        Author: Bytes Crafter
        Author URI:   https://www.bytes-crafter.com/about-us
        Text Domain:  bytescrafter-datavice-backend
    */

    #region WP Recommendation - Prevent direct initilization of the plugin.
    if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly
    if ( ! function_exists( 'is_plugin_active' ) ) 
    {
        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }
    #endregion

    include_once ( plugin_dir_path( __FILE__ ) . '/config.php' );

    include_once ( plugin_dir_path( __FILE__ ) . '/includes/api/index.php' );

?>