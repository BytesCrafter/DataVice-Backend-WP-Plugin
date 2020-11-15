<?php
	// Exit if accessed directly
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	/**
	 * @package datavice-wp-plugin
     * @version 0.1.0
     * @author bytescrafter
     * Quality Controlled since 15/11/2020
    */

	$dv_config_list = "
	('Account Lock Time', 'Time it takes before account is release from freeze.', 'lock_expiry_span', '1800', sha2(1, 256) ),
	('Master Key', 'Use this key if function does not required snky and wpid.', 'master_key', 'datavice', sha2(2, 256)),
	('Password Expiry Span', 'Time it takes to expire the given password reset key.', 'pword_expiry_span', '1800', sha2(3, 256)),
	('Password Reset Key length', 'Length of hashed password reset key.', 'pword_resetkey_length', '7', sha2(4, 256)),
	('Activation Key length', 'Length of activation key.', 'activation_key_length', '5', sha2(5, 256)),
	('Maximum image file size', 'Length of Maximum file size to be upload to server.', 'max_img_size', '5000000', sha2(6, 256)),
	('Limit of output in search user', 'This config is the length output of search query.', 'limit_search', '10', sha2(7, 256)),
	('Lock authentication', 'This config lock authentication of datavice plugin.', 'lock_authentication', 'inactive', sha2(8, 256));";
