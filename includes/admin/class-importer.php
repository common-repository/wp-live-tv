<?php

defined( 'ABSPATH' ) || exit();


use League\Csv\Reader;
use League\Csv\Statement;

include WPTV_PATH . '/vendor/autoload.php';


class WPTV_Importer {

	private static $instance = null;

	private $countries = [];

	/**
	 * Importer constructor.
	 *
	 * @param   array   $countries
	 * @param   string  $file_path
	 */

	function __construct( $countries ) {
		$this->countries = $countries;
	}

	public function handle_import() {

		@ini_set( 'max_execution_time', - 1 );
		@ini_set( 'memory_limit', - 1 );

		$response = [];

		$file_path = WPTV_INCLUDES . "/admin/data/data.csv";

		$countries = array_diff( (array) $this->countries, (array) get_option( 'wptv_imported_countries' ) );

		//offset
		$last_countries = get_option( 'wptv_last_selected_countries' );
		$offset         = $countries == $last_countries ? get_option( 'wptv_import_offset' ) : 0;

		update_option( 'wptv_last_selected_countries', $countries );

		//stations per page to be inserted
		$length = 10;

		//get stations from offset to offset + length
		$csv = Reader::createFromPath( $file_path, 'r' );
		$csv->setHeaderOffset( 0 ); //set the CSV header offset

		$stmt = Statement::create()->offset( $offset )->limit( $length )->where( [ $this, 'where' ] );

		$channels = $stmt->process( $csv );

		if ( $channels->count() ) {

			foreach ( $channels as $channel ) {
				$this->import_station( $channel );
			}

			$count = count( $channels );

			if ( $count < $length ) {
				$length = $count;
			}

			$offset = $offset + $length;
			update_option( 'wptv_import_offset', $offset );

		} else {
			$response['done'] = true;
			update_option( 'wptv_imported_countries', array_merge( $countries, (array) get_option( 'wptv_imported_countries' ) ) );
			update_option( 'wptv_import_offset', 0 );
		}

		$response['imported'] = $offset;

		return $response;

	}

	/**
	 * Import Station
	 *
	 * @param $channel
	 */
	function import_station( $channel ) {

		$defaults = array(
			'id'           => '',
			'name'         => '',
			'video_link'   => '',
			'external'     => 0,
			'description'  => '',
			'logo'         => '',
			'website'      => '',
			'channel'      => '',
			'country'      => '',
			'country_code' => 'ww',
		);


		$channel = array_merge( $defaults, $channel );

		$title = sanitize_text_field( $channel['name'] );

		if ( $this->channel_exists( $title, $channel['country_code'] ) ) {
			return;
		}

		$content = sanitize_textarea_field( $channel['description'] );

		$meta_input = array(
			'_video_link' => $channel['video_link'],
			'is_external' => '1' == $channel['external'] ? 'yes' : '',
			'_logo_url'   => $channel['logo'],
			'_website'    => $channel['website'],
			'_youtube'    => $channel['channel'],
		);


		//insert post
		$post_id = wp_insert_post( array(
			'post_type'    => 'wptv',
			'post_title'   => $title,
			'post_content' => $content,
			'post_status'  => 'publish',
		) );

		if ( is_wp_error( $post_id ) ) {
			return;
		}

		//update meta
		foreach ( $meta_input as $key => $value ) {
			update_post_meta( $post_id, $key, $value );
		}

		//update country terms
		$country_taxonomy = 'tv_country';

		$country_code = trim( $channel['country_code'] );
		$country      = trim( $channel['country'] );
		$country_id   = 0;


		//country
		if ( $country_exists = get_term_by( 'slug', $country_code, $country_taxonomy, ARRAY_A ) ) {
			$country_id = $country_exists['term_id'];
		} else {
			$country_inserted = wp_insert_term( $country, $country_taxonomy, [ 'slug' => $country_code ] );

			if ( ! is_wp_error( $country_inserted ) ) {
				$country_id = $country_inserted['term_id'];
			}
		}

		wp_set_post_terms( $post_id, [ $country_id ], $country_taxonomy );

	}

	function where( $channel ) {
		$countries = array_diff( (array) $this->countries, (array) get_option( 'wptv_imported_countries' ) );

		return in_array( $channel['country_code'], $countries );
	}

	/**
	 * Check if stations exists or not
	 *
	 * @param $channel_name
	 * @param $country_code
	 *
	 * @return bool
	 */
	public function channel_exists( $channel_name, $country_code ) {
		$channel = get_page_by_title( $channel_name, OBJECT, 'wptv' );

		if ( $channel ) {
			$countries = wp_get_post_terms( $channel->ID, 'tv_country' );

			if ( $countries and is_array( $countries ) ) {

				foreach ( $countries as $country ) {
					if ( $country->parent == 0 ) {
						$parent = $country;
					}
				}

				if ( isset( $parent ) && $country_code == $parent->slug ) {
					return true;
				}

			} else {
				return true;
			}
		}

		return false;
	}

	public static function instance( $countries = [] ) {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self( $countries );
		}

		return self::$instance;
	}

}