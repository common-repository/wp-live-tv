<?php

defined( 'ABSPATH' ) || exit();

$is_grid = false;

/** station query arguments */
$args = [
	'posts_per_page' => ! $is_grid ? 3 : 4,
	'orderby'        => 'rand',
	'post__not_in'   => [ $post_id ],
	'tax_query'      => [
		'relation' => 'AND',
	],
];

/** include the country argument */
if ( ! empty( $country ) ) {
	$args['tax_query'][] = [
		'taxonomy' => 'tv_country',
		'field'    => 'term_id',
		'terms'    => $country,
	];
}

/** include the categories argument */
if ( ! empty( $categories ) ) {
	$args['tax_query'][] = [
		'taxonomy' => 'tv_category',
		'field'    => 'term_id',
		'terms'    => array_keys( $categories ),
	];
}


$related_channels = wptv_get_channels( $args );

if ( ! empty( $related_channels ) ) { ?>
    <div class="wptv-related wptv-listings <?php echo $is_grid ? 'wptv-listing-grid' : ''; ?>">
        <h3><?php esc_html_e( 'You Also May Like', 'wp-live-tv' ); ?></h3>
		<?php
		foreach ( $related_channels as $station ) {
			wptv()->get_template( 'listing/loop', [ 'channel' => $station ] );
		}
		?>
    </div>
<?php } ?>