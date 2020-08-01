<?php
	// Exit if accessed directly
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	/**
	 * @package datavice-wp-plugin
     * @version 0.1.0
     * This is where you include CSS and JS files using WP enqueue script functions.
    */
    
    //Define constant for this path
    define('DV_CONFIGS_LIB_PATH', plugin_dir_path( __FILE__ ));

    class DV_Library_Config {

        public static function dv_get_config($table, $key, $default){
            
            global $wpdb; 
            
            $result = $wpdb->get_row("SELECT config_value FROM $table WHERE config_key = '$key'");

            if (!$result) {
                return $default;
            } else {
                return intval($result->config_value);
            }

        }

        public static function dv_set_config($table, $desc, $key, $value){
            
            global $wpdb; 
            
            $result = $wpdb->query("INSERT INTO $table (`config_desc`, `config_key`, `config_value`) VALUES ('$desc', '$key', '$value');");

            if (!$result) {
                return false;
            } else {
                return true;
            }

        }


    }
