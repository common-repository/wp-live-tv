<div class="wptv-listings full-width">
    <div class="wptv-listings-main">
		<?php

		if ( empty( $id ) || ! $channel || 'wptv' != get_post_type( $id ) ) {

			echo '<div class="wptv-listing">';
			wptv()->get_template( 'no-channel' );
			echo '</div>';
		} else {
			$stream_link = get_post_meta( $id, '_video_link', true );
			$stream_link = ! empty( $stream_link ) ? esc_url( $stream_link ) : '';

			wptv()->get_template( 'player', [ 'stream_link' => $stream_link, 'post_id' => $id ] );
		}
		?>
    </div>
</div>