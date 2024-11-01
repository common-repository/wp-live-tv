<?php

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit();

if ( ! class_exists( 'WPTV_Elementor_Widget' ) ) {
	class WPTV_Elementor_Widget extends \Elementor\Widget_Base {

		public function get_name() {
			return 'wptv_player';
		}

		public function get_title() {
			return __( 'TV Player', 'wp-live-tv' );
		}

		public function get_icon() {
			return 'eicon-play-o';
		}

		public function get_categories() {
			return [ 'basic' ];
		}

		public function get_keywords() {
			return [ 'audio', 'video', 'wptv', 'channel' ];
		}

		public function _register_controls() {

			$this->start_controls_section( '_section_tv_channel',
			                               [
				                               'label' => __( 'Alignment', 'wp-live-tv' ),
				                               'tab'   => Controls_Manager::TAB_CONTENT,
			                               ] );

			//switch style
			$this->add_control( '_channel_heading',
			                    [
				                    'label' => __( 'TV Channel', 'wp-live-tv' ),
				                    'type'  => Controls_Manager::HEADING,
			                    ] );

			$this->add_control( 'channel_id',
			                    [
				                    'label'       => __( 'Channel ID', 'wp-live-tv' ),
				                    'type'        => Controls_Manager::TEXT,
				                    'label_block' => false,
			                    ] );

			$this->end_controls_section();
		}

		public function render() {
			$settings = $this->get_settings_for_display();
			extract( $settings );

			if(empty($channel_id)){
			    echo "Enter the channel ID";
				return;
			}

			echo do_shortcode( "[wptv_player id={$channel_id}]" );

			?>
            <script>
                new MediaElementPlayer('wptv_media_player', {
                    videoWidth: '100%',
                    videoHeight: '100%',
                });
            </script>
			<?php
		}

	}
}