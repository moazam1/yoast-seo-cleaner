<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://isolution.org
 * @since      1.0.0
 *
 * @package    Yoast_Seo_Cleaner
 * @subpackage Yoast_Seo_Cleaner/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Yoast_Seo_Cleaner
 * @subpackage Yoast_Seo_Cleaner/admin
 * @author     Moazam Nabi <moazam@isolution.org>
 */
class Yoast_Seo_Cleaner_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->load_dependencies();

	}

	private function load_dependencies() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) .  'admin/class-yoast-seo-cleaner-admin-settings.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) .  'admin/class-yoast-seo-optimizer.php';
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/yoast-seo-cleaner-admin.css', array(), $this->version, 'all' );

	}

}
