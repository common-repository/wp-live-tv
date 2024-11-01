<?php

/** block direct access */
defined( 'ABSPATH' ) || exit();

/** check if class `WPTV_Enqueue` not exists yet */
if(!class_exists('WPTV_Enqueue')) {
	class WPTV_Enqueue {

		/**
		 * @var null
		 */
		private static $instance = null;

		/**
		 * WPTV_Enqueue constructor.
		 */
		public function __construct() {
			add_action( 'wp_enqueue_scripts', [ $this, 'frontend_scripts' ] );
			add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );
		}

		/**
		 * Frontend Scripts
		 *
		 * @param $hook
		 */
		public function frontend_scripts( $hook ) {

			/** enqueue frontend styles */
			wp_enqueue_style( 'select2', WPTV_ASSETS . '/vendor/select2/select2.min.css',
			                  false,
			                  '4.0.11' );

			/** wptv frontend */
			wp_enqueue_style( 'wptv-frontend', WPTV_ASSETS . '/css/frontend.css', [ 'dashicons', 'wp-mediaelement' ], WPTV_VERSION );


			/** enqueue frontend script */
			wp_enqueue_script( 'select2', WPTV_ASSETS . '/vendor/select2/select2.min.js',
			                   [ 'jquery' ],
			                   '4.0.11',
			                   true );

			/**-------- lazy js ---------*/
			wp_enqueue_script( 'lazy-js', WPTV_ASSETS . '/vendor/jquery.lazy.min.js',
			                   [ 'jquery' ],
			                   '1.7.10',
			                   true );

			/**-------- HLS js ---------*/
			wp_enqueue_script( 'hls', WPTV_ASSETS . '/vendor/hls.js', [ 'jquery' ], false, true );

			/**-------- wptv frontned --------*/
			wp_enqueue_script( 'wptv-frontend', WPTV_ASSETS . '/js/frontend.min.js',
			                   [
				                   'jquery',
				                   'jquery-ui-slider',
				                   'wp-util',
				                   'wp-mediaelement',
			                   ], WPTV_VERSION,
			                   true );


			/* localized script attached to 'wptv-frontend' */
			wp_localize_script( 'wptv-frontend',
			                    'wptv',
			                    [
				                    'ajax_url' => admin_url( 'admin-ajax.php' ),
				                    'volume'   => wptv_get_settings( 'player_volume', 70, 'wptv_player_settings' ),
			                    ] );
		}

		/**
		 * Admin scripts
		 *
		 * @param $hook
		 */
		public function admin_scripts( $hook ) {

			/** select2 css */
			wp_enqueue_style( 'select2', WPTV_ASSETS . '/vendor/select2/select2.min.css',
			                  false,
			                  '4.0.11' );

			/** wptv admin css */
			wp_enqueue_style( 'wptv-admin', WPTV_ASSETS . '/css/admin.css', false, WPTV_VERSION );

			/** multi-select css */
			wp_enqueue_style( 'jquery.multi-select', WPTV_ASSETS . '/vendor/lou-multi-select/css/multi-select.dist.css',
			                  false,
			                  '0.9.12' );

			/** multi-select js */
			wp_enqueue_script( 'jquery.multi-select', WPTV_ASSETS . '/vendor/lou-multi-select/js/jquery.multi-select.js',
			                   [ 'jquery' ],
			                   '0.9.12',
			                   true );

			/** syotimer */
			wp_enqueue_script( 'jquery.syotimer',
			                   WPTV_ASSETS . 'vendor/jquery.syotimer.min.js',
			                   [ 'jquery' ] );

			/** hideseek */
			wp_enqueue_script( 'jquery.hideseek', WPTV_ASSETS . '/vendor/jquery.hideseek.min.js',
			                   [ 'jquery' ] );

			//Alpha color picker
			wp_enqueue_script( 'wp-color-picker-alpha', WPTV_ASSETS . '/vendor/wp-color-picker-alpha.js', [ 'jquery', 'wp-color-picker' ], '', true );


			/** select2 js */
			wp_enqueue_script( 'select2', WPTV_ASSETS . '/vendor/select2/select2.min.js',
			                   [ 'jquery' ],
			                   '4.0.11',
			                   true );

			/** wptv admin js */
			wp_enqueue_script( 'wptv-admin', WPTV_ASSETS . '/js/admin.min.js',
			                   [
				                   'jquery',
				                   'jquery.syotimer',
				                   'wp-util'
			                   ], WPTV_VERSION,
			                   true );

			$localize_array = array(
				'adminUrl'           => admin_url(),
				'pluginUrl'          => WPTV_URL,
				'ajaxUrl'            => admin_url( 'admin-ajax.php' ),
				'nonce'              => wp_create_nonce( 'wptv' ),
				'isPremium'          => wptv_fs()->can_use_premium_code__premium_only(),
				'imported_countries' => get_option( 'wptv_imported_countries' ),
				'i18n'               => array(
					'alert_no_country'   => __( 'You need to select countries, before run the import', 'wp-live-tv' ),
					'running'            => __( 'Please wait, Import is running...', 'wp-live-tv' ),
					'no_country_found'   => __( 'No country found.', 'wp-live-tv' ),
					'update'             => __( 'Update', 'wp-live-tv' ),
					'updating'           => __( 'Updating', 'wp-live-tv' ),
					'imported'           => __( 'Imported: ', 'wp-live-tv' ),
					'count_title'        => __( 'Total channel of the country', 'wp-live-tv' ),
					'premium'            => __( 'Premium', 'wp-live-tv' ),
					'select_add_country' => __( 'Search country to add', 'wp-live-tv' ),
					'get_premium'        => __( 'Upgrade to Premium', 'wp-live-tv' ),
					'premium_promo'      => __( 'to import all the channels of 40+ countries.', 'wp-live-tv' ),
					'selected_countries' => __( 'Selected Countries', 'wp-live-tv' ),
					'selected'           => __( 'Selected:', 'wp-live-tv' ),
					'remaining'          => __( 'Remaining:', 'wp-live-tv' ),
					'total_channel'      => __( 'Total Channel:', 'wp-live-tv' ),
					'import_more'        => __( 'Import More', 'wp-live-tv' ),
					'run_import'         => __( 'Run Importer', 'wp-live-tv' ),
				),
			);
			
			wp_localize_script( 'wptv-admin', 'wptv', $localize_array );

		}

		/**
		 * @return WPTV_Enqueue|null
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

	}
}

WPTV_Enqueue::instance();





