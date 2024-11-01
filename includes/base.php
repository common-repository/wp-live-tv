<?php

/** don't call the file directly */
defined( 'ABSPATH' ) || wp_die( __( 'You can\'t access this page', 'wp-live-tv' ) );
/** if class `WPTV` doesn't exists yet. */
if ( !class_exists( 'WPTV' ) ) {
    /**
     * Sets up and initializes the plugin.
     * Main initiation class
     *
     * @since 1.0.0
     */
    final class WPTV
    {
        /**
         * A reference to an instance of this class.
         *
         * @since  1.0.0
         * @access private
         * @var    object
         */
        private static  $instance = null ;
        /**
         * Minimum PHP version required
         *
         * @var string
         */
        private static  $min_php = '5.6.0' ;
        /**
         * Sets up needed actions/filters for the plugin to initialize.
         *
         * @return void
         * @since  1.0.0
         * @access public
         */
        public function __construct()
        {
            
            if ( $this->check_environment() ) {
                $this->load_files();
                add_action( 'init', [ $this, 'lang' ] );
                //add_action( 'admin_init', [ $this, 'activation_redirect' ] );
                add_action( 'admin_notices', [ $this, 'print_notices' ], 15 );
                add_filter( 'plugin_action_links_' . plugin_basename( WPTV_FILE ), array( $this, 'plugin_action_links' ) );
                add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widget' ] );
            }
        
        }
        
        /**
         * redirect to settings page after activation the plugin
         */
        public function activation_redirect()
        {
            
            if ( get_option( 'wptv_do_activation_redirect', false ) ) {
                delete_option( 'wptv_do_activation_redirect' );
                wp_redirect( admin_url( 'edit.php?post_type=wptv&page=wptv-settings#wptv_get_started' ) );
                exit;
            }
        
        }
        
        /**
         * Ensure theme and server variable compatibility
         *
         * @return boolean
         * @since  1.0.0
         * @access private
         */
        private function check_environment()
        {
            $return = true;
            /** Check the PHP version compatibility */
            
            if ( version_compare( PHP_VERSION, self::$min_php, '<=' ) ) {
                $return = false;
                $notice = sprintf( esc_html__( 'Unsupported PHP version Min required PHP Version: "%s"', 'wp-live-tv' ), self::$min_php );
            }
            
            /** Add notice and deactivate the plugin if the environment is not compatible */
            
            if ( !$return ) {
                add_action( 'admin_notices', function () use( $notice ) {
                    ?>
                        <div class="notice is-dismissible notice-error">
                            <p><?php 
                    echo  $notice ;
                    ?></p>
                        </div>
					<?php 
                } );
                return $return;
            } else {
                return $return;
            }
        
        }
        
        /**
         * register darkmode switch elementor widget
         *
         * @throws Exception
         */
        public function register_widget()
        {
            require WPTV_INCLUDES . '/class-elementor-widget.php';
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new WPTV_Elementor_Widget() );
        }
        
        /**
         * Include required core files used in admin and on the frontend.
         *
         * @return void
         * @since 1.0.0
         */
        public function load_files()
        {
            //core includes
            require WPTV_INCLUDES . 'functions.php';
            require WPTV_INCLUDES . 'freemius.php';
            require WPTV_INCLUDES . 'class-hooks.php';
            require WPTV_INCLUDES . 'class-shortcode.php';
            require WPTV_INCLUDES . 'class-form-handler.php';
            require WPTV_INCLUDES . 'class-cpt.php';
            require WPTV_INCLUDES . 'class-enqueue.php';
            require WPTV_INCLUDES . 'template-functions.php';
            /** include the gutenberg block */
            require WPTV_PATH . '/block/block.php';
            //admin includes
            
            if ( is_admin() ) {
                include WPTV_INCLUDES . 'admin/class-importer.php';
                require WPTV_INCLUDES . 'admin/class-metabox.php';
                require WPTV_INCLUDES . 'admin/class-admin.php';
                require WPTV_INCLUDES . 'admin/class-settings-api.php';
                require WPTV_INCLUDES . 'admin/class-settings.php';
            }
        
        }
        
        /**
         * Initialize plugin for localization
         *
         * @return void
         * @since 1.0.0
         *
         */
        public function lang()
        {
            load_plugin_textdomain( 'wptv', false, dirname( plugin_basename( WPTV_FILE ) ) . '/languages/' );
        }
        
        /**
         * Plugin action links
         *
         * @param array $links
         *
         * @return array
         */
        public function plugin_action_links( $links )
        {
            $links[] = sprintf( '<a href="%1$s">%2$s</a>', admin_url( 'edit.php?post_type=wptv&page=wptv-settings#wptv_get_started' ), __( 'Settings', 'wp-live-tv' ) );
            return $links;
        }
        
        /**
         * Get the template path.
         *
         * @return string
         * @since 1.0.0
         */
        public function template_path()
        {
            return apply_filters( 'wp_tv_template_path', 'wp-tv/' );
        }
        
        /**
         * Returns path to template file.
         *
         * @param null $name
         * @param boolean|array $args
         *
         * @return bool|string
         * @since 1.0.0
         */
        public function get_template( $name = null, $args = false )
        {
            if ( !empty($args) && is_array( $args ) ) {
                extract( $args );
            }
            $template = locate_template( $this->template_path() . $name . '.php' );
            if ( !$template ) {
                $template = WPTV_TEMPLATES . "/{$name}.php";
            }
            
            if ( file_exists( $template ) ) {
                include $template;
            } else {
                return false;
            }
        
        }
        
        /**
         * add admin notices
         *
         * @param           $class
         * @param           $message
         * @param string $only_admin
         *
         * @return void
         */
        public function add_notice( $class, $message, $only_admin = '' )
        {
            $notices = get_option( sanitize_key( 'wp_radio_notification' ), [] );
            
            if ( is_string( $message ) && is_string( $class ) && !wp_list_filter( $notices, array(
                'message' => $message,
            ) ) ) {
                $notices[] = array(
                    'message'    => $message,
                    'class'      => $class,
                    'only_admin' => $only_admin,
                );
                update_option( sanitize_key( 'wp_radio_notification' ), $notices );
            }
        
        }
        
        /**
         * Print the admin notices
         *
         * @return void
         * @since 1.0.0
         */
        public function print_notices()
        {
            $notices = get_option( sanitize_key( 'wptv_notices' ), [] );
            foreach ( $notices as $notice ) {
                if ( !empty($notice['only_admin']) && !is_admin() ) {
                    continue;
                }
                ?>
                <div class="notice notice-<?php 
                echo  $notice['class'] ;
                ?>">
                    <p><?php 
                echo  $notice['message'] ;
                ?></p>
                </div>
				<?php 
                update_option( sanitize_key( 'wptv_notices' ), [] );
            }
        }
        
        /**
         * Main WPTV Instance.
         *
         * Ensures only one instance of WPTV is loaded or can be loaded.
         *
         * @return WPTV - Main instance.
         * @since 1.0.0
         * @static
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
/** if function `wp_tv` doesn't exists yet. */
if ( !function_exists( 'wptv' ) ) {
    function wptv()
    {
        return WPTV::instance();
    }

}
/** fire off the plugin */
wptv();