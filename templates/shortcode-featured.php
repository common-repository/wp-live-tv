<?php
/* Block direct access */
defined( 'ABSPATH' ) || exit();

$col     = ! empty( $shortcode_args['col'] ) ? intval( $shortcode_args['col'] ) : 4;
$is_grid = 'grid' == wptv_get_settings(  'layout', 'list', 'wptv_display_settings' );


$args = array(
	'post_type'   => 'wptv',
	'numberposts' => $count,
	'meta_key'    => '_featured_channel',
	'meta_value'  => 'yes',
);

if ( ! empty( $country ) ) {
	$args['tax_query'] = array(
		'relation' => 'AND',
		array(
			'taxonomy' => 'tv_country',
			'field'    => 'name',
			'terms'    => $country,
		)
	);
}

$channels = get_posts( $args );

if ( ! empty( $channels ) ) { ?>
    <div class="wptv-listings">
        <div class="wptv-listings-main">
            <div class="wptv-listing-wrapper <?php echo $is_grid ? 'wptv-listing-grid' : ''; ?>">
				<?php
				foreach ( $channels as $channel ) {
					wptv()->get_template( 'listing/loop', [ 'channel' => $channel, 'col' => $col ] );
				} ?>
            </div>
        </div>
    </div>
	<?php
} else {
	wptv()->get_template( 'no-channel' );
}