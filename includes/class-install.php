<?php

/** block direct access */
defined( 'ABSPATH' ) || exit;

/** check if class `WPTV_Install` not exists yet */
if ( ! class_exists( 'WPTV_Install' ) ) {
	/**
	 * Class Install
	 */
	class WPTV_Install {

		/**
		 * @var null
		 */
		private static $instance = null;

		/**
		 * Do the activation stuff
		 *
		 * @return void
		 * @since 1.0.0
		 */
		public function __construct() {
			self::create_pages();
			self::create_default_data();
		}

		/**
		 * Create pages
		 *
		 * @since 2.1.0
		 */
		private static function create_pages() {

			if ( get_page_by_title( 'Live TV' ) ) {
				return;
			}

			$id = wp_insert_post( array(
				                      'post_type'    => 'page',
				                      'post_title'   => 'Live TV',
				                      'post_content' => '[wptv_listing]',
				                      'post_status'  => 'publish',
			                      ) );
			if ( ! is_wp_error( $id ) ) {
				$settings = get_option('wptv_general_settings');

				$settings['channel_page'] = $id;

				update_option( 'wptv_general_settings', $settings );
			}

		}


		/**
		 * create default data
		 *
		 * @since 2.0.8
		 */
		private static function create_default_data() {

			update_option( 'wptv_version', WPTV_VERSION );
			update_option( 'wptv_flush_rewrite_rules', true );
			update_option( 'wptv_do_activation_redirect', true );


			$install_date = get_option( 'wptv_install_time' );

			if ( empty( $install_date ) ) {
				update_option( 'wptv_install_time', time() );
			}

		}

		/**
		 * @return WPTV_Install|null
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

	}
}

WPTV_Install::instance();