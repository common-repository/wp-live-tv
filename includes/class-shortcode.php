<?php

defined( 'ABSPATH' ) || exit;
if ( !class_exists( 'WPTV_ShortCode' ) ) {
    class WPTV_ShortCode
    {
        /**
         * @var null
         */
        private static  $instance = null ;
        /* constructor */
        public function __construct()
        {
            add_shortcode( 'wptv_listing', array( $this, 'listing' ) );
        }
        
        public function player( $atts )
        {
            $atts = shortcode_atts( array(
                'id' => '',
            ), $atts );
            $id = $atts['id'];
            $channel = get_post( $id );
            ob_start();
            wptv()->get_template( 'shortcode/player', [
                'id'      => $id,
                'channel' => $channel,
            ] );
            return ob_get_clean();
        }
        
        /**
         * render station listing
         *
         * @param $atts
         *
         * @return false|string
         */
        public function listing( $atts )
        {
            $atts = shortcode_atts( array(
                'country'  => '',
                'category' => '',
                'col'      => 4,
            ), $atts );
            ob_start();
            wptv()->get_template( 'listing/page-content', [
                'shortcode_args' => $atts,
            ] );
            return ob_get_clean();
        }
        
        /**
         * @param $atts
         *
         * @return false|string
         */
        public function country_list()
        {
            ob_start();
            wptv()->get_template( 'sidebar' );
            return ob_get_clean();
        }
        
        /**
         * @param $atts
         *
         * @return false|string
         */
        public function featured( $atts )
        {
            $atts = shortcode_atts( array(
                'count'   => 10,
                'country' => '',
                'col'     => 4,
            ), $atts );
            ob_start();
            wptv()->get_template( 'shortcode-featured', $atts );
            return ob_get_clean();
        }
        
        /**
         * @return WPTV_ShortCode|null
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
WPTV_ShortCode::instance();