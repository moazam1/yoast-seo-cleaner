<?php
/**
 * Author: Moazam
 * Company: ISolution Technologies
 * URL: http://www.isolution.org
 * Date: 21/02/2018
 * Time: 11:25 PM
 */

class Yoast_Seo_Optimizer {

	public function ysc_cleanup_admin_action() {
		$this->do_cleanup();
		wp_redirect( $_SERVER['HTTP_REFERER'] . '&updated=1');
		exit();
	}

	function ysc_schedule_daily_cleanup() {

		if ( ! wp_next_scheduled( 'ysc_schedule_daily_cleanup' ) && get_option('activate_yoast_seo_cron_job') ) {
			wp_schedule_event( time(), 'daily', array($this, 'do_cleanup' ) );
		}
	}

	public function do_cleanup () {
		global $wpdb;
		$query = "DELETE FROM $wpdb->options WHERE option_name LIKE %s";
		$wpdb->query( $wpdb->prepare( $query, '%_cache_validator' ) );
	}
}