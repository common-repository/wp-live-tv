<?php

/** Block direct access */
defined( 'ABSPATH' ) || exit();

/** check if class `WPTV_Metabox` not exists yet */
if(!class_exists('WPTV_Metabox')) {
	/**
	 * Class WPTV_Metabox
	 *
	 * Handle metaboxes
	 *
	 * @package Prince\WP_Radio
	 *
	 * @since 1.0.0
	 */
	class WPTV_Metabox {

		/**
		 * @var null
		 */
		private static $instance = null;

		/**
		 * WPTV_Metabox constructor.
		 * Initialize the custom Meta Boxes for prince-options api.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			add_action( 'add_meta_boxes', array( $this, 'register_meta_boxes' ) );
			add_action( 'do_meta_boxes', [ $this, 'remove_meta_box' ] );
		}

		public function remove_meta_box() {
			remove_meta_box( 'postimagediv', 'wptv', 'side' );
		}

		/**
		 * register metaboxes
		 */
		public function register_meta_boxes() {
			add_meta_box( 'channel_info',
			              __( 'Channel Information', 'wp-live-tv' ),
			              array( $this, 'render_channel_info_meta_box' ),
			              array( 'wptv' ),
			              'normal',
			              'high' );
		}

		/**
		 * render station info metabox content
		 *
		 * @since 1.0.0
		 */
		public function render_channel_info_meta_box() {
			include_once WPTV_INCLUDES. '/admin/views/metabox.php';
		}


		/**
		 * @return WPTV_Metabox|null
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

	}
}

WPTV_Metabox::instance();
