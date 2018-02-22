<?php
/**
 * Author: Moazam
 * Company: ISolution Technologies
 * URL: http://www.isolution.org
 * Date: 21/02/2018
 * Time: 05:25 PM
 */

class Yoast_Seo_Cleaner_Admin_Settings {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;
	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of this plugin.
	 * @param      string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	public function setup_plugin_options_menu() {
		//Add the menu to the Plugins set of menu items
		add_options_page(
			'Yoast SEO Cleaner Settings',
			'Yoast SEO Cleaner Settings',
			'manage_options',
			'yoast-seo-cleaner-settings',
			array(
				$this,
				'render_settings_page_content'
			)
		);
	}

	/**
	 * Renders a simple page to display for the theme menu defined above.
	 */
	public function render_settings_page_content() {
		global $wpdb;
		?>
        <!-- Create a header in the default WordPress 'wrap' container -->
        <div class="wrap">

            <h2><?php _e( 'Yoast SEO Cleaner Settings', 'yoast-seo-cleaner' ); ?></h2>
            <form method="post" action="options.php">
	            <?php if (isset($_GET['updated'])) : ?>
                    <div class="notice notice-success is-dismissible"><p><?php _e('All done!'); ?>.</p></div>
	            <?php endif; ?>
				<?php
				settings_fields( 'yoast_seo_cleaner_options' );
				do_settings_sections( 'yoast_seo_cleaner_settings_page' );
				submit_button();
				?>
            </form>

			<?php
			$query = "SELECT COUNT(*) FROM $wpdb->options WHERE option_name LIKE %s";
			$rows  = $wpdb->get_var( $wpdb->prepare( $query, '%_cache_validator' ) );
			?>
            <div class="section">
                <span>
                    <b><?php _e( 'Entries needs to be removed:' ) ?> </b>
                    <b class="ysc-badge ysc-<?php echo $rows ? 'error' : 'success' ?>"><?php echo $rows ? $rows : _e( 'None' ) ?></b>
                </span>
				<?php if ( $rows ) : ?>
                    <form method="POST" action="<?php echo admin_url( 'admin.php' ); ?>">
                        <h2><?php _e( 'Cleanup Now!', 'yoast-seo-cleaner' ); ?></h2>
                        <input type="hidden" name="action" value="ysc_cleanup"/>
                        <input type="submit" value="<?php _e( 'Cleanup Now!', 'yoast-seo-cleaner' ) ?>"
                               class="button button-primary"/>
                    </form>
				<?php endif; ?>
            </div>

        </div><!-- /.wrap -->
		<?php
	}

	/**
	 * This function provides a simple description for the General Options page.
	 *
	 * It's called from the 'wppb-demo_initialize_theme_options' function by being passed as a parameter
	 * in the add_settings_section function.
	 */
	public function general_options_callback() {

	} // end general_options_callback

	/**
	 * Initializes the theme's display options page by registering the Sections,
	 * Fields, and Settings.
	 *
	 * This function is registered with the 'admin_init' hook.
	 */
	public function initialize_display_options() {
		add_settings_section(
			'general_settings_section',                        // ID used to identify this section and with which to register options
			'',                // Title to be displayed on the administration page
			array( $this, 'general_options_callback' ),        // Callback used to render the description of the section
			'yoast_seo_cleaner_settings_page'                        // Page on which to add this section of options
		);
		// Next, we'll introduce the fields for toggling the visibility of content elements.
		add_settings_field(
			'activate_yoast_seo_cron_job',                                // ID used to identify the field throughout the theme
			__( 'Auto clean?', 'yoast-seo-cleaner' ),                    // The label to the left of the option interface element
			array(
				$this,
				'activate_yoast_seo_cron_checkbox_callback'
			),    // The name of the function responsible for rendering the option interface
			'yoast_seo_cleaner_settings_page',                // The page on which this option will be displayed
			'general_settings_section',                    // The name of the section to which this field belongs
			array(                                        // The array of arguments to pass to the callback. In this case, just a description.
				__( 'Cleanup your options table on a daily basis.', 'yoast-seo-cleaner' ),
			)
		);
		// Finally, we register the fields with WordPress
		register_setting(
			'yoast_seo_cleaner_options',
			'activate_yoast_seo_cron_job'
		);
	}

	/**
	 * This function renders the interface elements for toggling the visibility of the header element.
	 *
	 * It accepts an array or arguments and expects the first element in the array to be the description
	 * to be displayed next to the checkbox.
	 */
	public function activate_yoast_seo_cron_checkbox_callback( $args ) {

		// First, we read the options collection
		$options = get_option( 'activate_yoast_seo_cron_job' );

		// Next, we update the name attribute to access this element's ID in the context of the display options array
		// We also access the show_header element of the options collection in the call to the checked() helper function
		$html = '<input type="checkbox" id="activate_yoast_seo_cron_job" name="activate_yoast_seo_cron_job" value="1" ' . checked( 1, $options, false ) . '/>';

		// Here, we'll take the first argument of the array and add it to a label next to the checkbox
		$html .= '<label for="activate_yoast_seo_cron_job">&nbsp;' . $args[0] . '</label>';

		echo $html;

	}

	protected function format_number( $num, $display_decimals = false ) {
		$x               = round( $num );
		$x_number_format = number_format( $x );
		$x_array         = explode( ',', $x_number_format );
		$x_parts         = array( 'K', 'M', 'B', 'T' );
		$x_count_parts   = count( $x_array ) - 1;
		if ( $display_decimals ) {
			$x_display = $x_array[0] . ( (int) isset( $x_array[1][0] ) && $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '' );
		} else {
			$x_display = $x_array[0];
		}
		$x_display .= isset( $x_parts[ $x_count_parts - 1 ] ) ? $x_parts[ $x_count_parts - 1 ] : '';

		return $x_display;
	}

}