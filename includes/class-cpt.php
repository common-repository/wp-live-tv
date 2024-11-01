<?php

/* Block direct access */
defined( 'ABSPATH' ) || exit();

/** check if class `WPTV_Post_Types` not exists yet */
if ( ! class_exists( 'WPTV_Post_Types' ) ) {
	/**
	 * Class Post_Types
	 *
	 * Register Custom post types and taxonomies
	 *
	 * @package Prince\WP_Radio
	 *
	 * @since 1.0.0
	 */
	class WPTV_Post_Types {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * post type slug
		 * @var string
		 */
		public static $post_type = 'wptv';

		/**
		 * Post_Types constructor.
		 */
		function __construct() {
			add_action( 'init', array( $this, 'register_post_types' ) );
			add_action( 'init', array( $this, 'register_taxonomies' ) );
			add_action( 'init', array( $this, 'flush_rewrite_rules' ), 99 );
		}

		/**
		 * register custom post types
		 *
		 * @return void
		 * @since 1.0.0
		 */
		function register_post_types() {

			$labels = array(
				'labels'              => $this->get_posts_labels( __( 'TV Channels', 'wp-live-tv' ),
				                                                  __( 'Channel', 'wp-live-tv' ),
				                                                  __( 'Channels', 'wp-live-tv' ) ),
				'hierarchical'        => false, //Hierarchical causes memory issues - WP Loads all records
				'supports'            => apply_filters( 'wptv_post_supports', array( 'title', 'editor', 'thumbnail' ) ),
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'menu_position'       => 5,
				'menu_icon'           => 'dashicons-format-video',
				'publicly_queryable'  => true,
				'exclude_from_search' => false,
				'has_archive'         => false,
				'query_var'           => true,
				'can_export'          => true,
				'rewrite'             => array( 'slug' => apply_filters( 'wptv_post_type_slug', 'channel' ) ),
				'capability_type'     => 'post',
			);

			register_post_type( self::$post_type, $labels );
		}

		/**
		 * Register custom taxonomies
		 *
		 * @return void
		 * @since 1.0.0
		 */
		public function register_taxonomies() {

			/** register tv category taxonomy */
			register_taxonomy( 'tv_category',
			                   array( 'wptv' ),
			                   array(
				                   'hierarchical'      => true,
				                   'labels'            => self::get_taxonomy_label( __( 'Categories', 'wp-live-tv' ),
				                                                                    __( 'Category', 'wp-live-tv' ),
				                                                                    __( 'Categories', 'wp-live-tv' ) ),
				                   'show_ui'           => true,
				                   'show_admin_column' => true,
				                   'rewrite'           => apply_filters( 'wptv_category_slug',
				                                                         [ 'slug' => 'tv-category' ] ),
				                   'query_var'         => true,
			                   ) );

			/** register tv country taxonomy */
			register_taxonomy( 'tv_country',
			                   array( 'wptv' ),
			                   array(
				                   'hierarchical'      => true,
				                   'labels'            => self::get_taxonomy_label( __( 'Countries', 'wp-live-tv' ),
				                                                                    __( 'Country', 'wp-live-tv' ),
				                                                                    __( 'Countries', 'wp-live-tv' ) ),
				                   'show_ui'           => true,
				                   'show_admin_column' => true,
				                   'query_var'         => true,
				                   'rewrite'           => apply_filters( 'wptv_country_slug',
				                                                         [ 'slug' => 'tv-country' ] ),
			                   ) );

		}

		/**
		 * Get all labels from post types
		 *
		 * @param $menu_name
		 * @param $singular
		 * @param $plural
		 *
		 * @return array
		 * @since 1.0.0
		 */
		protected static function get_posts_labels( $menu_name, $singular, $plural, $type = 'plural' ) {
			$labels = array(
				'name'               => 'plural' == $type ? $plural : $singular,
				'all_items'          => sprintf( __( "All %s", 'wp-live-tv' ), $plural ),
				'singular_name'      => $singular,
				'add_new'            => sprintf( __( 'Add New %s', 'wp-live-tv' ), $singular ),
				'add_new_item'       => sprintf( __( 'Add New %s', 'wp-live-tv' ), $singular ),
				'edit_item'          => sprintf( __( 'Edit %s', 'wp-live-tv' ), $singular ),
				'new_item'           => sprintf( __( 'New %s', 'wp-live-tv' ), $singular ),
				'view_item'          => sprintf( __( 'View %s', 'wp-live-tv' ), $singular ),
				'search_items'       => sprintf( __( 'Search %s', 'wp-live-tv' ), $plural ),
				'not_found'          => sprintf( __( 'No %s found', 'wp-live-tv' ), $plural ),
				'not_found_in_trash' => sprintf( __( 'No %s found in Trash', 'wp-live-tv' ), $plural ),
				'parent_item_colon'  => sprintf( __( 'Parent %s:', 'wp-live-tv' ), $singular ),
				'menu_name'          => $menu_name,
			);

			return $labels;
		}


		/**
		 * Get all labels from taxonomies
		 *
		 * @param $menu_name
		 * @param $singular
		 * @param $plural
		 *
		 * @return array
		 * @since 1.0.0
		 */
		protected static function get_taxonomy_label( $menu_name, $singular, $plural ) {
			$labels = array(
				'name'              => sprintf( _x( '%s', 'taxonomy general name', 'wp-live-tv' ), $plural ),
				'singular_name'     => sprintf( _x( '%s', 'taxonomy singular name', 'wp-live-tv' ), $singular ),
				'search_items'      => sprintf( __( 'Search %s', 'wp-live-tv' ), $plural ),
				'all_items'         => sprintf( __( 'All %s', 'wp-live-tv' ), $plural ),
				'parent_item'       => sprintf( __( 'Parent %s', 'wp-live-tv' ), $singular ),
				'parent_item_colon' => sprintf( __( 'Parent %s:', 'wp-live-tv' ), $singular ),
				'edit_item'         => sprintf( __( 'Edit %s', 'wp-live-tv' ), $singular ),
				'update_item'       => sprintf( __( 'Update %s', 'wp-live-tv' ), $singular ),
				'add_new_item'      => sprintf( __( 'Add New %s', 'wp-live-tv' ), $singular ),
				'new_item_name'     => sprintf( __( 'New %s Name', 'wp-live-tv' ), $singular ),
				'menu_name'         => __( $menu_name, 'wp-live-tv' ),
			);

			return $labels;
		}

		/**
		 * Flash The Rewrite Rules
		 *
		 * @return void
		 * @since 1.0.0
		 *
		 */
		public function flush_rewrite_rules() {
			if ( get_option( 'wptv_flush_rewrite_rules' ) ) {
				flush_rewrite_rules();
				delete_option( 'wptv_flush_rewrite_rules' );
			}
		}

		/**
		 * Returns the instance.
		 *
		 * @return object|WPTV_Post_Types
		 * @since  1.0.0
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

	}
}

WPTV_Post_Types::instance();