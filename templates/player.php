<!--tv station player-->
<div class="wptv-player-wrap">
	<?php

	$is_external = wptv_get_meta( $post_id, 'is_external' );

	if ( 'yes' == $is_external ) { ?>
        <a href="<?php echo $stream_link; ?>" class="player-external" target="_blank">
            <i class="dashicons dashicons-external"></i>
            <div>
                <span>OPEN LIVE STREAM</span>
                <span>LIVE STREAM WILL OPEN ON THE OFFICIAL WEBSITE</span>
            </div>
        </a>
	<?php } else {

		$is_autoplay = 'on' == wptv_get_settings( 'autoplay', 'off', 'wptv_player_settings' );

		if ( ! strpos( $stream_link, 'iframe' ) ) {

			$url = parse_url( $stream_link );

			if ( isset( $url['host'] ) && ( $url['host'] == 'vimeo.com' || $url['host'] == 'player.vimeo.com' ) ) {
				$type = 'video/vimeo';
			} elseif ( strpos( $stream_link, 'youtube' ) > 0 ) {
				$type = 'video/youtube';
			} elseif ( isset( $url['host'] ) && $url['host'] == 'soundcloud.com' ) {
				$type = 'video/soundcloud';
			} elseif ( preg_match( '/http:\/\/www\.dailymotion\.com\/video\/+/', $stream_link ) ) {
				$type = 'video/dailymotion';
			} else {
				$type = wptv_get_file_type_by_url( $stream_link );
			}

			?>

            <video id="wptv_media_player" class="wptv-media-player" <?php echo $is_autoplay ? 'autoplay' : ''; ?>>
                <source src="<?php echo esc_url( $stream_link ); ?>" type="<?php echo $type; ?>">
            </video>

		<?php } else {
			printf( '<iframe class="wptv-iframe-player" src="%s" frameborder="0"></iframe>', $stream_link );
		}
	}
	?>
</div>