<?php

/** prevent direct access */


defined( 'ABSPATH' ) || exit();


/** check if class `WPTV_Form_Handler` not exists yet */
if(!class_exists('WPTV_Form_Handler')) {
	/**
	 * Class Ajax
	 *
	 * Handle all Ajax requests
	 *
	 * @since 1.0.0
	 *
	 * @package Prince\WP_Radio
	 */
	class WPTV_Form_Handler {

		private static $instance = null;

		/**
		 * WPTV_Form_Handler constructor.
		 */
		public function __construct() {
			add_action( 'wp_ajax_tv_channel_search', array( $this, 'tv_channel_search' ) );
			add_action( 'wp_ajax_nopriv_tv_channel_search', array( $this, 'tv_channel_search' ) );
			add_action( 'save_post_wptv', array( $this, 'save_post_meta' ) );

			add_action( 'wp_ajax_wptv_import_channels', array( $this, 'handle_import' ) );
			add_action( 'admin_action_wptv_remove_country', [ $this, 'remove_country' ] );
		}

		public function remove_country() {

			$countries = (array) get_option( 'wptv_imported_countries' );

			$country_name = sanitize_text_field( $_REQUEST['country'] );
			if ( ( $key = array_search( $country_name, $countries ) ) !== false ) {
				unset( $countries[ $key ] );
			}

			if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'wptv' ) || ! current_user_can( 'delete_posts' ) ) {

				update_option( 'wptv_imported_countries', (array) $countries );

				wp_redirect( admin_url( 'edit.php?post_type=wptv&page=import-channels' ) );
				exit();
			}


			$country = get_term_by( 'slug', $country_name, 'tv_country' );

			if (empty($country) || is_wp_error( $country ) ) {
				update_option( 'wptv_imported_countries', (array) $countries );

				wp_redirect( admin_url( 'edit.php?post_type=wptv&page=import-channels' ) );
				exit();
			}

			$country_slug = $country->slug;


			global $wpdb;
			$post_ids = $wpdb->get_col( $wpdb->prepare( "SELECT tr.object_id FROM {$wpdb->terms} t LEFT JOIN {$wpdb->term_relationships} tr ON tr.term_taxonomy_id = t.term_id WHERE t.slug = %s;",
			                                            $country_slug ) );

			if ( ! empty( $post_ids ) ) {
				$placeholders = '';
				foreach ( $post_ids as $post_id ) {
					$placeholders .= '%s,';
				}

				$prepared_placeholders = trim( $placeholders, ',' );
				$prepared_values       = array_merge( array( 'wptv' ), $post_ids );


				// Delete posts + data.
				$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->posts} WHERE post_type = %s AND ID IN ($prepared_placeholders);", $prepared_values ) );
				$wpdb->query( "DELETE meta FROM {$wpdb->postmeta} meta LEFT JOIN {$wpdb->posts} posts ON posts.ID = meta.post_id WHERE posts.ID IS NULL;" );
			}

			// Delete term taxonomies.
			foreach ( array( 'tv_country', 'tv_category' ) as $taxonomy ) {
				$wpdb->delete( $wpdb->term_taxonomy,
				               array(
					               'taxonomy' => $taxonomy,
				               ) );
			}

			// Delete orphan relationships.
			$wpdb->query( "DELETE tr FROM {$wpdb->term_relationships} tr LEFT JOIN {$wpdb->posts} posts ON posts.ID = tr.object_id WHERE posts.ID IS NULL;" );

			// Delete orphan terms.
			$wpdb->query( "DELETE t FROM {$wpdb->terms} t LEFT JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id WHERE tt.term_id IS NULL;" );

			// Delete orphan term meta.
			if ( ! empty( $wpdb->termmeta ) ) {
				$wpdb->query( "DELETE tm FROM {$wpdb->termmeta} tm LEFT JOIN {$wpdb->term_taxonomy} tt ON tm.term_id = tt.term_id WHERE tt.term_id IS NULL;" );
			}

			// Clear any cached data that has been removed.
			wp_cache_flush();

			update_option( 'wptv_imported_countries', (array) $countries );

			wp_redirect( admin_url( 'edit.php?post_type=wptv&page=import-channels' ) );
			exit();
		}

		public function handle_import() {

			$countries = ! empty( $_REQUEST['countries'] ) ? array_map('sanitize_text_field', $_REQUEST['countries']) : '';

			$importer = new WPTV_Importer( $countries );
			$response = $importer->handle_import();

			wp_send_json_success( $response );
		}

		/**
		 * get station by search
		 */
		public function tv_channel_search() {

			$term = ! empty( $_REQUEST['term']['term'] ) ? sanitize_text_field( $_REQUEST['term']['term'] ) : '';

			if ( empty( $term ) ) {
				wp_send_json_error( __( 'No Station Found', 'wp-live-tv' ) );
			}

			$stations = wp_radio_get_stations( [ 's' => $term ] );

			$data = [];
			if ( ! empty( $stations ) ) {
				foreach ( $stations as $station ) {
					$data[] = [ 'id' => $station->post_title, 'text' => $station->post_title ];
				}
			}

			echo json_encode( $data );
			exit();

		}

		/**
		 * Save post meta data
		 *
		 * @param $post_id
		 */
		public function save_post_meta( $post_id ) {

			$stream_link      = ! empty( $_POST['_video_link'] ) ? esc_url( $_POST['_video_link'] ) : '';
			$featured_station = ! empty( $_POST['_featured_channel'] ) ? sanitize_key( $_POST['_featured_channel'] ) : '';
			$is_external      = ! empty( $_POST['is_external'] ) ? sanitize_key( $_POST['is_external'] ) : '';
			$website          = ! empty( $_POST['_website'] ) ? esc_url( $_POST['_website'] ) : '';
			$youtube          = ! empty( $_POST['_youtube'] ) ? esc_url( $_POST['_youtube'] ) : '';
			$logo_url         = ! empty( $_POST['_logo_url'] ) ? esc_url( $_POST['_logo_url'] ) : '';

			update_post_meta( $post_id, '_video_link', $stream_link );
			update_post_meta( $post_id, 'is_external', $is_external );
			update_post_meta( $post_id, '_featured_channel', $featured_station );
			update_post_meta( $post_id, '_website', $website );
			update_post_meta( $post_id, '_youtube', $youtube );
			update_post_meta( $post_id, '_logo_url', $logo_url );
		}

		/**
		 * @return WPTV_Form_Handler|null
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

	}
}

WPTV_Form_Handler::instance();
