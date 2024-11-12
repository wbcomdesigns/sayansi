<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://https://wbcomdesigns.com/
 * @since      1.0.0
 *
 * @package    Sayansi_Core
 * @subpackage Sayansi_Core/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Sayansi_Core
 * @subpackage Sayansi_Core/includes
 * @author     WBCOM Designs <admin@wbcomdesigns.com>
 */
class Sayansi_Core_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'sayansi-core',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
