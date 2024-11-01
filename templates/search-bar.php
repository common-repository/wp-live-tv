<?php

$keyword     = ! empty( $_REQUEST['keyword'] ) ? sanitize_text_field( $_REQUEST['keyword'] ) : '';
$country_id  = ! empty( $_REQUEST['country'] ) ? intval( $_REQUEST['country'] ) : '';

if ( is_tax( 'tv_country' ) ) {
	$country_id = get_queried_object()->term_id;
}

if ( ! empty( $country_id ) ) {
	$country_name = get_term_field( 'name', $country_id, 'tv_country' );
}

?>

<div class="wptv-search">
    <h4 class="wptv-search-title"><?php esc_html_e( 'Search channel by name, country', 'wp-live-tv' ) ?></h4>

    <!--    Search Toggle -->
    <div class="search_toggle">
        <button type="button" class="button-primary">
            <i class="dashicons dashicons-menu"></i> <?php esc_html_e( 'Search Channel', 'wp-live-tv' ); ?>
        </button>
    </div>

    <form action="<?php echo get_permalink( wptv_get_settings( 'channel_page' ) ); ?>" method="get" id="tv_channel_search">

        <!-- keyword -->
        <select name="keyword" id="keyword" data-placeholder="<?php echo ! empty( $keyword ) ? esc_attr( $keyword ) : esc_attr__( 'Channel Name',
		                                                                                                                          'wp-live-tv' ); ?>">
            <option value=""></option>
        </select>

        <!-- country -->
        <select name="country" id="country" data-placeholder="<?php echo ! empty( $country_name ) ? esc_attr( $country_name ) : esc_attr__( 'Select Country',
		                                                                                                                                    'wp-live-tv' ) ?>">
            <?php
            $countries = get_terms( [ 'taxonomy' => 'tv_country' ] );

			if ( ! empty( $countries ) ) {
				$countries = wp_list_pluck( $countries, 'name', 'term_id' );
				foreach ( $countries as $id => $name ) {
					printf( '<option value="%s" %s >%s</option>', $id, selected( $id, $country_id, false ), $name );
				}
			}

			?>
        </select>

        <button type="submit"><?php esc_html_e( 'Search', 'wp-live-tv' ); ?></button>

    </form>
</div>