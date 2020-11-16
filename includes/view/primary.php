
<?php
	// Exit if accessed directly
	if ( ! defined( 'ABSPATH' ) ) 
	{
		exit;
	}

    /**
	 * @package datavice-wp-plugin
     * @version 0.1.0
    */

    #region Shortcodes

    function datavice_activate_shortcodes() { 
        include_once( DV_PLUGIN_PATH . '/includes/view/blocks/activate.php' );
    } 
    add_shortcode('datavice_activations', 'datavice_activate_shortcodes'); 

    #endregion
