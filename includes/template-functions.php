<?php

/** prevent direct access */
defined( 'ABSPATH' ) || exit();


/**
 * render station listings
 */
if ( ! function_exists( 'wptv_listing_page_content' ) ) {
	function wptv_listing_page_content() {

		$queried_object = get_queried_object();

		global $wp_query;

		ob_start();
		wptv()->get_template( 'listing/page-content' );
		$html = ob_get_clean();

		$post_title = '';

		if ( ! empty( $queried_object->name ) ) {

			if ( in_array( $queried_object->taxonomy, [ 'tv_country', 'tv_category' ] ) ) {
				$post_title = $queried_object->name;
			}

		}


		$post_name = ! empty( $queried_object->slug ) ? $queried_object->slug : '';

		$dummy_post_properties = array(
			'ID'                    => 0,
			'post_status'           => 'publish',
			'post_author'           => '',
			'post_parent'           => 0,
			'post_type'             => 'page',
			'post_date'             => '',
			'post_date_gmt'         => '',
			'post_modified'         => '',
			'post_modified_gmt'     => '',
			'post_content'          => $html,
			'post_title'            => apply_filters( 'the_title', $post_title ),
			'post_excerpt'          => '',
			'post_content_filtered' => '',
			'post_mime_type'        => '',
			'post_password'         => '',
			'post_name'             => $post_name,
			'guid'                  => '',
			'menu_order'            => 0,
			'pinged'                => '',
			'to_ping'               => '',
			'ping_status'           => '',
			'comment_status'        => 'closed',
			'comment_count'         => 0,
			'filter'                => 'raw',
		);

		// Set the $post global.
		$post = new WP_Post( (object) $dummy_post_properties );

		// Copy the new post global into the main $wp_query.
		$wp_query->post  = $post;
		$wp_query->posts = array( $post );

		// Prevent comments form from appearing.
		$wp_query->post_count = 1;
		$wp_query->is_page    = true;
		$wp_query->is_single  = true;
		if ( is_tax() ) {
			$wp_query->is_tax = true;
		}
		$wp_query->max_num_pages = 0;

		// Prepare everything for rendering.
		setup_postdata( $post );
		remove_all_filters( 'the_content' );
		remove_all_filters( 'the_excerpt' );
	}
}

if ( ! function_exists( 'wptv_template_redirect' ) ) {
	function wptv_template_redirect() {
		if ( is_tax( 'tv_country' ) || is_tax( 'tv_category' ) ) {
			if ( is_tax( 'tv_category' ) ) {
				add_filter( 'edit_post_link', '__return_false' );
			}

			wptv_listing_page_content();
		}
	}
}
add_action( 'template_redirect', 'wptv_template_redirect' );

if ( ! function_exists( 'wptv_filter_content' ) ) {
	/**
	 * Single Station page content
	 *
	 * @param $content
	 *
	 * @return false|string
	 */
	function wptv_filter_content( $content ) {
		if ( is_singular( 'wptv' ) ) {
			ob_start();
			wptv()->get_template( 'single' );
			$content = ob_get_clean();
		}

		return $content;
	}
}

add_filter( 'the_content', 'wptv_filter_content' );