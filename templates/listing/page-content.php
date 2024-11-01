<?php

/** block direct access */
defined( 'ABSPATH' ) || exit();

$queried_object = get_queried_object();

$col     = ! empty( $shortcode_args['col'] ) ? intval( $shortcode_args['col'] ) : 4;
$is_grid = 'grid' == wptv_get_settings(  'layout', 'list', 'wptv_display_settings' );

?>
<div class="wptv-listings">
    <div class="wptv-listings-main">
		<?php

		global $post, $wp_query;
		$queried_object = get_queried_object();

		/** Search bar */
		if ( wptv_fs()->can_use_premium_code__premium_only()
		     && 'on' == wptv_get_settings( 'show_search', 'on', 'wptv_display_settings' ) ) {
			wptv()->get_template( 'search-bar' );
		}

		/**
		 * Check if the shortcode has filter attributes
		 *
		 * @var  $shortcode_filter
		 */
		$shortcode_filter = ! empty( $shortcode_args['country'] ) || ! empty( $shortcode_args['category'] );
		$is_search        = ! empty( $_GET['country'] ) || ! empty( $_GET['category'] ) || ! empty( $_GET['keyword'] );
		$is_tax           = is_tax( 'tv_country' ) || is_tax( 'tv_category' );


		$args     = [];
		$country  = [];
		$category = [];

		//Add search string to the query
			if ( ! empty( $_REQUEST['keyword'] ) ) {
				$args['s'] = sanitize_text_field( $_REQUEST['keyword'] );
			}

		//Country search
			if ( ! empty( $_REQUEST['country'] ) ) {
				$country[] = intval( $_REQUEST['country'] );
			}

		//Country archive taxonomy
		if ( is_tax( 'tv_country' ) ) {
				$country[] = intval( $queried_object->term_id );
			}

		//Category search
		if ( ! empty( $_REQUEST['category'] ) ) {
			$category[] = intval( $_REQUEST['category'] );
			}

		// Category taxonomy archive
		if ( is_tax( 'tv_category' ) ) {
			$category[] = intval( $queried_object->term_id );
			}

			$countries = ! empty( $shortcode_args['country'] ) ? array_filter( explode( ',', $shortcode_args['country'] ) ) : '';

			if ( ! empty( $countries ) ) {
				foreach ( $countries as $c ) {
					$country_term = get_term_by( 'slug', $c, 'tv_country' );
					if ( $country_term ) {
						$country[] = $country_term->term_id;
					}
				}
			}

		$categories = ! empty( $shortcode_args['category'] ) ? array_filter( explode( ',',
		                                                                              $shortcode_args['category'] ) ) : '';

		if ( ! empty( $categories ) ) {
			foreach ( $categories as $g ) {
				$category_term = get_term_by( 'slug', $g, 'tv_category' );
				if ( $category_term ) {
					$category[] = $category_term->term_id;
					}
				}
			}

			if ( ! empty( $country ) ) {
				$args['tax_query'][] = array(
					'relation' => 'AND',
					array(
						'taxonomy' => 'tv_country',
						'field'    => 'term_id',
						'terms'    => $country,
					)
				);
			}

		if ( ! empty( $category ) ) {
				$args['tax_query'][] = array(
					'relation' => 'AND',
					'taxonomy' => 'tv_category',
					'field'    => 'term_id',
					'terms'    => $category,
				);
			}

		$channels = wptv_get_channels( $args );

		if ( ! empty( $channels ) ) { ?>
            <div class="wptv-listing-wrapper <?php echo $is_grid ? 'wptv-listing-grid' : ''; ?>">
				<?php
				foreach ( $channels as $channel ) {
					wptv()->get_template( 'listing/loop', [ 'channel' => $channel, 'col' => $col ] );
				} ?>
            </div>
			<?php
		} else {
			wptv()->get_template( 'no-channel' );
		}

		?>

        <!-- listing pagination -->
        <div class="wptv-pagination">
            <nav id="post-navigation" class="navigation pagination" role="navigation" aria-label="<?php esc_attr_e( 'Post Navigation',
			                                                                                                        'wp-live-tv' ); ?>">
                <div class="nav-links">
					<?php

					global $wptv_args;

					if ( $shortcode_filter || $is_search || $is_tax ) {
						$wptv_args = $args;
					}

					$wptv_query = wptv_get_channels( $wptv_args, true );
					$paged      = ! empty( $_REQUEST['paginate'] ) ? intval( $_REQUEST['paginate'] ) : '';

					/** Supply translatable string */
					$translated = __( 'Page', 'wp-live-tv' );

					echo paginate_links( array(
						                     'format'             => '?paginate=%#%',
						                     'current'            => max( 1, $paged ),
						                     'total'              => $wptv_query->max_num_pages,
						                     'before_page_number' => '<span class="screen-reader-text">' . $translated . ' </span>',
						                     'mid_size'           => 1,
						                     'prev_text'          => sprintf( '<i class="dashicons dashicons-arrow-left-alt"></i> %s',
						                                                      esc_html__( 'Previous', 'wp-live-tv' ) ),
						                     'next_text'          => sprintf( '%s <i class="dashicons dashicons-arrow-right-alt"></i>',
						                                                      esc_html__( 'Next', 'wp-live-tv' ) ),
					) );

					?>
                </div>
            </nav>
        </div>
    </div>
</div>