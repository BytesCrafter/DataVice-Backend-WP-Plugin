<?php
	// Exit if accessed directly
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	/**
	 * @package datavice-wp-plugin
     * @version 0.1.0
     * Data for DataVice config.
    */

	global $wpdb;

	$dv_config_list = "
	('Account Lock Time', 'Time it takes before account is release from freeze.', 'lock_expiry_span', '1',sha2(1, 256) ),
	('Master Key', 'Use this key if function does not required snky and wpid.', 'master_key', '2', sha2(2, 256)),
	('Password Expiry Span', 'Time it takes to expire the given password reset key.', 'pword_expiry_span', '3', sha2(3, 256)),
	('Password Reset Key length', 'Length of hashed password reset key.', 'pword_resetkey_length', '4', sha2(4, 256)),
	('Maximum image file size', 'Length of Maximum file size to be upload to server.', 'max_img_size', '5', sha2(5, 256));";

	$date = date("Y-m-d h:i:s");

	$dv_config_val = "
	('configs', 'lock_expiry_span', '1800', '1', '$date', '1', sha2(1, 256) ),
	('configs', 'master_key', 'datavice', '1', '$date', '2', sha2(2, 256) ),
	('configs', 'pword_expiry_span', '1800', '1', '$date', '3', sha2(3, 256) ),
	('configs', 'pword_resetkey_length', '5', '1', '$date', '4', sha2(4, 256) ),
	('configs', 'max_img_size', '5000000', '1', '$date', '5', sha2(5, 256) )
	;";
