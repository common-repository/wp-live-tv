<?php
/* Block direct access */
defined( 'ABSPATH' ) || exit;

?>

<div class="sidebar-toggle">
    <span class="dashicons dashicons-menu-alt"></span> <?php _e( 'Countries', 'wp-live-tv' ); ?>
</div>

<div class="wptv-sidebar <?php echo isset( $shortcode ) ? 'shortcode' : ''; ?>">

    <div class="sidebar-header <?php echo ! empty( $_REQUEST['keyword'] ) ? 'search' : ''; ?>">
        <div class="title"><?php _e( 'Countries', 'wp-live-tv' ); ?></div>
    </div>

    <ul class="sidebar-listing">
		<?php

		$countries = get_terms( [ 'taxonomy' => 'tv_country' ] );


		if ( ! empty( $countries ) ) {

			$i = 0;
			foreach ( $countries as $country ) {

				if ( $i < 10 ) {
					$image = wptv_get_country_flag( $country->slug, 16 );
				} else {
					$image = wptv_get_country_flag( $country->slug, 16, true );
				}

				printf( '<li %s ><a href="%s">%s %s</a></li>', ! empty( $active ) && $country->slug == $active ? 'class="active"' : '',
					get_term_link( $country->term_id ), $image, $country->name );
				$i ++;
			}

		} else {
			_e( 'No Country added yet!', 'wp-live-tv' );
		}

		?>
    </ul>

</div>