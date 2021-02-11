<?php

    /*
        Plugin Name: DataVice WP Plugin
        Plugin URI: http://www.bytescrafter.net/projects/datavice
        Description: DataVice WP Plugin is a user management for your application or game users.
        Version: 0.1.0
        Author: Bytes Crafter
        Author URI:   https://www.bytescrafter.net/about-us
        Text Domain:  datavice-wp-plugin

        * @package      datavice-wp-plugin
        * @author       Bytes Crafter

        * @copyright    2020 Bytes Crafter
        * @version      0.1.0

        * @wordpress-plugin
        * WC requires at least: 2.5.0
        * WC tested up to: 5.4.2
    */

    #region WP Recommendation - Prevent direct initilization of the plugin.
    if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly
    if ( ! function_exists( 'is_plugin_active' ) ) 
    {
        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }
    #endregion

    // Root plugin path
    define('DV_PLUGIN_PATH', plugin_dir_path( __FILE__ ));
    define("DV_PLUGIN_URL", plugin_dir_url( __FILE__ ) );

    //Includes helper class firts.
    include_once ( DV_PLUGIN_PATH . '/includes/helpers/includes.php' );

    //Important config files and plugin updates.
    include_once ( DV_PLUGIN_PATH . '/includes/core/source.php' );
    include_once ( DV_PLUGIN_PATH . '/includes/core/config.php' );
    include_once ( DV_PLUGIN_PATH . '/includes/core/library.php' );
    include_once ( DV_PLUGIN_PATH . '/includes/core/update.php' );

     //Make sure to create required mysql tables.
    include_once ( DV_PLUGIN_PATH . '/includes/core/dbhook.php' );

    //Includes assets if page is defined.
    include_once ( DV_PLUGIN_PATH . '/includes/core/assets.php' );

    //Include the REST API of USocketNet to be accessible.
    include_once ( DV_PLUGIN_PATH . '/includes/api/routes.php' );

    //Display menu on WP Backend.
    include_once ( DV_PLUGIN_PATH . '/includes/view/primary.php' );

    //Any wordpress custom implementation.
    include_once ( DV_PLUGIN_PATH . '/includes/core/wpress.php' );

        function my_login_logo_one() { 
    ?> 
            <style type="text/css"> 
                body.login div#login h1 a {
                    background-image: url(<?php echo get_site_icon_url(); ?>); 
                    padding-bottom: 30px; 
                    background-size: 100px;
                    width: 100px;
                } 
            </style>
    <?php 
        } 
        add_action( 'login_enqueue_scripts', 'my_login_logo_one' );

