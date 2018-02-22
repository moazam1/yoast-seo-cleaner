<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://isolution.org
 * @since      1.0.0
 *
 * @package    Yoast_Seo_Cleaner
 * @subpackage Yoast_Seo_Cleaner/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Yoast_Seo_Cleaner
 * @subpackage Yoast_Seo_Cleaner/includes
 * @author     Moazam Nabi <moazam@isolution.org>
 */
class Yoast_Seo_Cleaner {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Yoast_Seo_Cleaner_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'PLUGIN_NAME_VERSION' ) ) {
			$this->version = PLUGIN_NAME_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'yoast-seo-cleaner';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Yoast_Seo_Cleaner_Loader. Orchestrates the hooks of the plugin.
	 * - Yoast_Seo_Cleaner_i18n. Defines internationalization functionality.
	 * - Yoast_Seo_Cleaner_Admin. Defines all hooks for the admin area.
	 * - Yoast_Seo_Cleaner_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-yoast-seo-cleaner-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-yoast-seo-cleaner-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-yoast-seo-cleaner-admin.php';


		$this->loader = new Yoast_Seo_Cleaner_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Yoast_Seo_Cleaner_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Yoast_Seo_Cleaner_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Yoast_Seo_Cleaner_Admin( $this->get_plugin_name(), $this->get_version() );
		$plugin_settings = new Yoast_Seo_Cleaner_Admin_Settings( $this->get_plugin_name(), $this->get_version() );
		$yoast_optimizer = new Yoast_Seo_Optimizer();

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );

		$this->loader->add_action( 'admin_menu', $plugin_settings, 'setup_plugin_options_menu' );
		$this->loader->add_action( 'admin_init', $plugin_settings, 'initialize_display_options' );

		$this->loader->add_action( 'admin_init', $plugin_settings, 'initialize_display_options' );
		$this->loader->add_action( 'admin_action_ysc_cleanup', $yoast_optimizer, 'ysc_cleanup_admin_action' );
		//daily cleanup cron!
		$this->loader->add_action( 'wp', $yoast_optimizer, 'do_cleanup' );

		$plugin = 'yoast-seo-cleaner/yoast-seo-cleaner.php';
		$this->loader->add_filter("plugin_action_links_$plugin", $this, 'plugin_add_settings_link');
	}

	function plugin_add_settings_link( $links ) {
		$settings_link = '<a href="options-general.php?page=yoast-seo-cleaner-settings">' . __( 'Settings' ) . '</a>';
		array_push( $links, $settings_link );
		return $links;
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Yoast_Seo_Cleaner_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
