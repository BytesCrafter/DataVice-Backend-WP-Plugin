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

	// $dv_config_list = "('Account Lock Time', 'Time it takes before account is release from freeze.', 'lock_expiry_span', '1800'),
	// ('Master Key', 'Use this key if function does not required snky and wpid.', 'master_key', 'datavice'),
	// ('Password Expiry Span', 'Time it takes to expire the given password reset key.', 'pword_expiry_span', '30'),
	// ('Password Reset Key length', 'Length of hashed password reset key.', 'pword_resetkey_length', '12');
	// ;";

	$dv_config_list = "('Account Lock Time', 'Time it takes before account is release from freeze.', 'lock_expiry_span', '1'),
	('Master Key', 'Use this key if function does not required snky and wpid.', 'master_key', '2'),
	('Password Expiry Span', 'Time it takes to expire the given password reset key.', 'pword_expiry_span', '3'),
	('Password Reset Key length', 'Length of hashed password reset key.', 'pword_resetkey_length', '4');
	;";

	$date = date("Y-m-d h:i:s");
	
	$dv_config_val = "
	('configs', 'lock_expiry_span', '1800', '1', '$date', '1' ),
	('configs', 'master_key', 'datavice', '1', '$date', '2' ),
	('configs', 'pword_expiry_span', '30', '1', '$date', '3' ),
	('configs', 'pword_resetkey_length', '12', '1', '$date', '4' )
	";
