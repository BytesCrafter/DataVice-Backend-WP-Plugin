<?php
	// Exit if accessed directly
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	/**
	 * @package bytescrafter-datavice
	*/
?>

<?php

	// Global prefix for this plugins table name prefix.
	DEFINE('DVC_TABLE_PREFIX', 'bc_datavice');
	
	// Global as Plugin URL for WordPress.
	DEFINE('DVC_PLUGIN_URL', plugin_dir_url( __FILE__ ));

?>