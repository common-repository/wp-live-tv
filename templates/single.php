<?php

defined( 'ABSPATH' ) || exit();

$post_id = get_the_ID();

$stream_link = get_post_meta( $post_id, '_video_link', true );
$stream_link = ! empty( $stream_link ) ? esc_url( $stream_link ) : '';

$website = get_post_meta( $post_id, '_website', true );
$youtube = get_post_meta( $post_id, '_youtube', true );

$country = wp_get_post_terms( $post_id, 'tv_country' );
$country = is_array( $country ) && ! empty( $country[0] ) ? $country[0] : '';

$thumb = get_post_meta($post_id, '_logo_url', true);
$thumb = ! empty( $thumb ) ? esc_url( $thumb ) : esc_url( WPTV_ASSETS . '/images/placeholder.jpg' );

?>

<!--single content wrapper-->
<div class="wptv-single">

    <!--content header-->
    <div class="wptv-header">

        <!--station poster-->
        <div class="wptv-thumbnail">
            <img src="<?php echo $thumb; ?>" alt="<?php echo esc_attr( get_the_title( $post_id ) ); ?>">
        </div>

        <div class="wptv-details">

            <div class="tv-country">
                <span><?php esc_html_e( 'Country: ', 'wp-live-tv' ); ?></span>

                <?php if($country){ ?>
                <a href="<?php echo esc_url( get_term_link( $country->term_id ) ); ?>" class="tv-country-link">
                    <?php echo $country->name; ?>
                </a>
                <?php } ?>
            </div>

			<?php
			/** station categories html */
			echo wptv_get_channel_categories( $post_id );
			?>

        </div>
    </div>

    <!--content body-->
    <div class="wptv-body">

	    <?php wptv()->get_template( 'player', [ 'stream_link' => $stream_link , 'post_id' => $post_id] ); ?>

        <!--station description-->
        <div class="description"><?php echo get_the_content(); ?></div>

    </div>

    <!--content footer-->
	<?php if ( ! empty( $website ) || ! empty( $youtube ) ) { ?>
        <div class="wptv-footer">
            <div class="wptv-info">

                <span class="station-name"><?php echo get_the_title( $post_id ); ?>:</span>

				<?php

				if ( ! empty( $website ) ) {
					printf( '<a href="%1$s" target="_blank"><i class="dashicons dashicons-admin-links"></i> %2$s </a>',
					        esc_url( $website ),
					        esc_html__( 'Website', 'wp-live-tv' ) );
				}

				if ( ! empty( $youtube ) ) {
					printf( '<a href="%1$s" target="_blank"><i class="dashicons dashicons-video-alt3"></i> %2$s </a>',
					        esc_url( $youtube ),
					        esc_html__( 'Video Channel', 'wp-live-tv' ) );
				}

				?>
            </div>
        </div>
	<?php } ?>


	<?php

	$categories = wp_get_post_terms( $post_id, 'tv_category' );
	$categories = wp_list_pluck( $categories, 'name', 'term_id' );

	if ( 'on' == wptv_get_settings( 'you_may_like', 'on', 'wptv_display_settings' ) ) {
		wptv()->get_template( 'related',
		                      [
			                      'post_id'    => $post_id,
			                      'country'    => $country,
			                      'categories' => $categories
		                      ] );
	}

	?>

</div>
<?php

if ( 'on' == wptv_get_settings( 'single_next_prev', 'off', 'wptv_display_settings' ) ) {
	echo '<div class="wptv-post-pagination">';
	// Previous/next post navigation.
	ob_start();
	the_post_navigation( array(
		                     'in_same_term'       => true,
		                     'taxonomy'           => 'tv_country',
		                     'prev_text'          => '<span class="post-title"><i class="dashicons dashicons-arrow-left-alt"></i> %title </span>',
		                     'next_text'          => '<span class="post-title">%title <i class="dashicons dashicons-arrow-right-alt"></i></span>',
		                     'screen_reader_text' => false,
	) );
	$html = ob_get_clean();
	echo str_replace( 'post-navigation', '', $html );
	echo '</div>';
}

?>
