<?php

defined( 'ABSPATH' ) || exit;

if ( !function_exists( 'wptv_fs' ) ) {
    // Create a helper function for easy SDK access.
    function wptv_fs()
    {
        global  $wptv_fs ;
        
        if ( !isset( $wptv_fs ) ) {
            // Include Freemius SDK.
            require_once WPTV_PATH . '/freemius/start.php';
            $wptv_fs = fs_dynamic_init( array(
                'id'             => '6834',
                'slug'           => 'wp-live-tv',
                'type'           => 'plugin',
                'public_key'     => 'pk_e051fae7029139c3e2c27943a7710',
                'is_premium'     => false,
                'premium_suffix' => 'Pro',
                'has_addons'     => false,
                'has_paid_plans' => true,
                'menu'           => array(
                'slug'    => 'edit.php?post_type=wptv',
                'support' => false,
                'contact' => false,
            ),
                'is_live'        => true,
            ) );
        }
        
        return $wptv_fs;
    }
    
    // Init Freemius.
    wptv_fs();
    // Signal that SDK was initiated.
    do_action( 'wptv_fs_loaded' );
}
