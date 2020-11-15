<?php
	// Exit if accessed directly
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	/**
	 * @package datavice-wp-plugin
     * @version 0.1.0
     * Config related class and function
    */

    class DV_Library_Config {

        public static function dv_get_config($key, $default){
            
            global $wpdb; 
            $tbl_config = DV_CONFIG_TABLE;
            
            $result = $wpdb->get_row("SELECT config_val FROM {$tbl_config} WHERE config_key = '$key' ORDER BY ID DESC LIMIT 0, 1");

            if (!$result) {
                return $default;
            } else {
                return $result->config_val;
            }
        }

        // public static function dv_get_config($key, $default){
            
        //     global $wpdb; 
        //     $tbl_config = DV_CONFIG_TABLE;
        //     $tbl_revision  = DV_REVS_TABLE;
            
        //     $result = $wpdb->get_row("SELECT child_val FROM {$tbl_config} INNER JOIN {$tbl_revision} rev ON rev.ID = dv_configs.config_val 
        //         WHERE config_key = '$key' AND revs_type = 'configs' AND child_key = '$key'");

        //     if (!$result) {
        //         return $default;
        //     } else {
        //         return $result->child_val;
        //     }
        // }

        public static function dv_set_config($title, $info, $key, $value){
            
            global $wpdb; 
            $tbl_config = DV_CONFIG_TABLE;
            $rev_table = DV_REVS_TABLE;
            $rev_fields = DV_INSERT_REV_FIELDS;
            
            $date = date("Y-m-d h:i:s");
            
            $result_config_val = $wpdb->query("INSERT INTO {$rev_table} ($rev_fields,  `parent_id`) VALUES ( 'configs', '$key', '$value', '1', '$date', '0' )");
            $result_config_val_id = $wpdb->insert_id;

            $result_config = $wpdb->query("INSERT INTO {$tbl_config} (`title`, `info`, `config_key`, `config_val`) VALUES ('$title', '$info', '$key', '$result_config_val_id');");
            $result_config_id = $wpdb->insert_id;

            $result_config_val_update = $wpdb->query("UPDATE {$rev_table} SET `parent_id` = '$result_config_id' WHERE ID = $result_config_val_id  ");

            if (!$result_config_val_id || !$result_config || !$result_config_val_update) {
                return false;
            } else {
                return true;
            }
        }

        public static function dv_update_config($key, $value){
            
            global $wpdb; 
            $tbl_config = DV_CONFIG_TABLE;
            
            $result = $wpdb->query("UPDATE {$tbl_config} SET `config_key`='$key', `config_val`='$value';");

            if (!$result) {
                return false;
            } else {
                return true;
            }
        }

    }
