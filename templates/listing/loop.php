<?php

/* Block direct access */
defined( 'ABSPATH' ) || exit();

$post_id = $channel->ID;

$country = wp_get_post_terms( $post_id, 'radio_country' );

if ( is_array( $country ) && ! empty( $country[0] ) ) {
	$country = $country[0];
}

$listing_class = 'wptv-listing';

if ( 'on' != wptv_get_settings(  'show_description', '', 'wptv_display_settings' ) ) {
	$listing_class .= ' hide_desc';
}

$is_grid = 'grid' == wptv_get_settings(  'layout', 'list', 'wptv_display_settings' );

if ( $is_grid ) {
	$col           = isset( $col ) ? $col : 3;
	$listing_class .= " listing-col-$col";
}

$country = wp_get_post_terms( $post_id, 'tv_country' );
$country = is_array( $country ) && ! empty( $country[0] ) ? $country[0] : '';

$thumb = get_post_meta($post_id, '_logo_url', true);
$thumb = ! empty( $thumb ) ? esc_url( $thumb ) : esc_url( WPTV_ASSETS.'/images/placeholder.jpg'  );

?>

<div class="<?php echo $listing_class; ?>">

    <div class="listing-thumbnail">
        <a href="<?php echo get_the_permalink( $post_id ); ?>">
            <img src="<?php echo $thumb; ?>" alt="<?php echo get_the_title( $post_id ); ?>">
        </a>
    </div>

    <!-- channel details -->
    <div class="listing-details">

        <div class="listing-heading">

            <a href="<?php echo get_the_permalink( $post_id ) ?>" class="station-name">
                <span><?php echo get_the_title( $post_id ); ?></span>
            </a>

        </div>

		<?php

		/** categories */
		echo wptv_get_channel_categories( $post_id );

		if ( 'on' == wptv_get_settings(  'show_description', 'on', 'wptv_display_settings' ) ) {
			printf( '<p class="listing-desc">%s</p>', wp_trim_words( get_post_field( 'post_content', $post_id ), 10 ) );
		}

		?>
    </div>

    <a href="<?php echo get_the_permalink($post_id); ?>" class="listing-play"> <i class="dashicons dashicons-controls-play"></i> Watch Live</a>

</div>