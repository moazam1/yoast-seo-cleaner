<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://isolution.org
 * @since      1.0.0
 *
 * @package    Yoast_Seo_Cleaner
 * @subpackage Yoast_Seo_Cleaner/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Yoast_Seo_Cleaner
 * @subpackage Yoast_Seo_Cleaner/includes
 * @author     Moazam Nabi <moazam@isolution.org>
 */
class Yoast_Seo_Cleaner_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'yoast-seo-cleaner',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
