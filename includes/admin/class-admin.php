<?php

/** prevent direct access */
defined( 'ABSPATH' ) || exit;
/** check if not class `WPTV_Admin` exists yet */
if ( !class_exists( 'WPTV_Admin' ) ) {
    class WPTV_Admin
    {
        private static  $instance = null ;
        /**
         * Admin constructor.
         */
        public function __construct()
        {
            add_action( 'pre_get_posts', [ $this, 'pre_get_posts' ] );
            add_action( 'restrict_manage_posts', [ $this, 'add_posts_filter_field' ] );
            add_filter(
                'display_post_states',
                [ $this, 'channel_page_status' ],
                10,
                2
            );
            add_action( 'admin_menu', [ $this, 'admin_menu' ] );
        }
        
        public function admin_menu()
        {
            add_submenu_page(
                'edit.php?post_type=wptv',
                __( 'Import Channels', 'wp-live-tv' ),
                __( 'Import Channels', 'wp-live-tv' ),
                'manage_options',
                'import-channels',
                [ $this, 'import_menu_page' ]
            );
            add_submenu_page(
                'edit.php?post_type=wptv',
                __( 'Get Started', 'wp-live-tv' ),
                __( 'Get Started', 'wp-live-tv' ),
                'manage_options',
                'wptv-get-started',
                [ $this, 'get_started_page' ]
            );
        }
        
        public function get_started_page()
        {
            include WPTV_INCLUDES . '/admin/views/get-started/index.php';
        }
        
        public function import_menu_page()
        {
            include_once WPTV_INCLUDES . '/admin/views/html-import-channels.php';
            wptv()->get_template( 'admin/promo', [
                'is_hidden' => true,
            ] );
        }
        
        /**
         * Add post filter field
         *
         * @return void
         */
        function add_posts_filter_field()
        {
            $type = ( !empty($_GET['post_type']) ? sanitize_key( $_GET['post_type'] ) : '' );
            
            if ( 'wptv' == $type ) {
                ?>
                <select name="country">
                    <option value=""><?php 
                _e( 'All Countries', 'wp-live-tv' );
                ?></option>
					<?php 
                $countries = get_terms( [
                    'taxonomy' => 'tv_country',
                ] );
                if ( !empty($countries) ) {
                    foreach ( $countries as $country ) {
                        printf(
                            '<option value="%1$s" %2$s >%3$s</option>',
                            $country->slug,
                            selected( $country->slug, ( !empty($_GET['country']) ? sanitize_key( $_GET['country'] ) : '' ) ),
                            $country->name
                        );
                    }
                }
                ?>
                </select>
				<?php 
            }
        
        }
        
        public function pre_get_posts( $query )
        {
            if ( !is_admin() ) {
                return;
            }
            global  $pagenow ;
            $is_edit = 'edit.php' == $pagenow;
            $is_type = !empty($query->query_vars['post_type']) && 'wptv' == $query->query_vars['post_type'];
            $is_main_query = $query->is_main_query();
            
            if ( $is_edit && $is_type && $is_main_query ) {
                
                if ( empty($_GET['orderby']) ) {
                    $query->set( 'orderby', 'title' );
                    $query->set( 'order', 'ASC' );
                }
                
                if ( !empty($_GET['country']) ) {
                    $query->query_vars['tax_query'] = [
                        'relation' => 'AND',
                        [
                        'taxonomy' => 'tv_country',
                        'field'    => 'slug',
                        'terms'    => sanitize_key( $_GET['country'] ),
                    ],
                    ];
                }
            }
        
        }
        
        /**
         * Add status for radios base page
         *
         * @param $states
         * @param $post
         *
         * @return array
         */
        function channel_page_status( $states, $post )
        {
            if ( wptv_get_settings( 'channel_page' ) == $post->ID ) {
                $states[] = __( 'TV Archive', 'wp-live-tv' );
            }
            return $states;
        }
        
        public static function instance()
        {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self();
            }
            return self::$instance;
        }
    
    }
}
WPTV_Admin::instance();