<?php

/** Block direct access */
defined( 'ABSPATH' ) || exit;
/** check if class `WPTV_Hooks` not exists yet */
if ( !class_exists( 'WPTV_Hooks' ) ) {
    class WPTV_Hooks
    {
        /**
         * @var null
         */
        private static  $instance = null ;
        /**
         * WPTV_Hooks constructor.
         */
        public function __construct()
        {
            add_filter( 'excerpt_more', [ $this, 'excerpt_more' ], 99 );
            add_action( 'rest_api_init', [ $this, 'rest_api' ] );
            add_action( 'wpmilitary_settings/after_content', [ $this, 'proPromo' ] );
            add_action( 'wp_footer', [ $this, 'custom_color' ] );
        }
        
        public function custom_color()
        {
            $listing_bg = wptv_get_settings( 'listing_bg', '#F7F7F7', 'wptv_color_settings' );
            $listing_hover_bg = wptv_get_settings( 'listing_hover_bg', '', 'wptv_color_settings' );
            $listing_play_color = wptv_get_settings( 'listing_play_color', '', 'wptv_color_settings' );
            $listing_play_bg_color = wptv_get_settings( 'listing_play_bg_color', '#f56624', 'wptv_color_settings' );
            $player_bg_color = wptv_get_settings( 'player_bg_color', '', 'wptv_color_settings' );
            ?>
            <style>
                .wptv-listings .wptv-listing {
                    border-color: <?php 
            echo  $listing_play_bg_color ;
            ?>;
                    background-color: <?php 
            echo  $listing_bg ;
            ?>;
                }

                .wptv-listings .wptv-listing:hover {
                    background-color: <?php 
            echo  $listing_hover_bg ;
            ?>;
                }

                .wptv-listings .wptv-listing .listing-play {
                    color: <?php 
            echo  $listing_play_color ;
            ?>;
                    background: <?php 
            echo  $listing_play_bg_color ;
            ?>;
                }

                .wptv-media-player.mejs-container,
                .wptv-media-player.mejs-container .mejs-controls,
                .wptv-media-player.mejs-embed,
                .wptv-media-player.mejs-embed body {
                    background: <?php 
            echo  $player_bg_color ;
            ?>;
                }


            </style>
			<?php 
        }
        
        public function proPromo()
        {
            wptv()->get_template( 'admin/promo', [
                'is_hidden' => true,
            ] );
        }
        
        public function rest_api()
        {
            register_rest_route( 'wptv/v1', '/player/(?P<id>\\d+)', [ [
                'methods'             => 'GET',
                'callback'            => [ $this, 'get_rest_api_player' ],
                'permission_callback' => '__return_true',
            ] ] );
        }
        
        public function get_rest_api_player( $data )
        {
            $id = ( isset( $data['id'] ) ? $data['id'] : '' );
            wp_send_json_success( do_shortcode( "[wptv_player id={$id}]" ) );
        }
        
        /**
         * @param $more
         *
         * @return string
         */
        public function excerpt_more( $more )
        {
            if ( 'wptv' == get_post_type() ) {
                $more = sprintf( '... <a href="%1$s" class="read-more">[ %2$s ]</a>', get_the_permalink(), __( 'View Channel', 'wp-live-tv' ) );
            }
            return $more;
        }
        
        /**
         * @return WPTV_Hooks|null
         */
        public static function instance()
        {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self();
            }
            return self::$instance;
        }
    
    }
}
WPTV_Hooks::instance();