<?php

/**

 *
 * @link              http://isolution.org
 * @since             1.0.1
 * @package           Yoast_Seo_Cleaner
 *
 * @wordpress-plugin
 * Plugin Name:       Yoast SEO Cleaner
 * Plugin URI:        https://github.com/moazam1/yoast-seo-cleaner
 * Description:       Cleans up your WP options table by removing Yoast SEO "_cache_validator" entries.
 * Version:           1.0.1
 * Author:            Moazam Nabi
 * Author URI:        http://isolution.org
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       yoast-seo-cleaner
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'YOAST_SEO_CLEANER_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-yoast-seo-cleaner-activator.php
 */
function activate_yoast_seo_cleaner() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-yoast-seo-cleaner-activator.php';
	Yoast_Seo_Cleaner_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-yoast-seo-cleaner-deactivator.php
 */
function deactivate_yoast_seo_cleaner() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-yoast-seo-cleaner-deactivator.php';
	Yoast_Seo_Cleaner_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_yoast_seo_cleaner' );
register_deactivation_hook( __FILE__, 'deactivate_yoast_seo_cleaner' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-yoast-seo-cleaner.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_yoast_seo_cleaner() {

	$plugin = new Yoast_Seo_Cleaner();
	$plugin->run();

}
run_yoast_seo_cleaner();
