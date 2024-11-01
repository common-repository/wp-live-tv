<?php
/**
 * Plugin Name: Live TV Player
 * Plugin URI:  https://wpmilitary.com/live-tv
 * Description: Worldwide TV Channels Directory Player.
 * Version:     1.0.5
 * Author:      WP Military
 * Author URI:  http://wpmilitary.com
 * Text Domain: wptv
 * Domain Path: /languages/
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

/** don't call the file directly */
defined( 'ABSPATH' ) || wp_die( __( 'You can\'t access this page', 'wp-live-tv' ) );

if ( function_exists( 'wptv_fs' ) ) {
	wptv_fs()->set_basename( false, __FILE__ );
} else {
	define( 'WPTV_VERSION', '1.0.5' );
	define( 'WPTV_FILE', __FILE__ );
	define( 'WPTV_PATH', dirname( WPTV_FILE ) );
	define( 'WPTV_INCLUDES', WPTV_PATH . '/includes/' );
	define( 'WPTV_URL', plugins_url( '', WPTV_FILE ) );
	define( 'WPTV_ASSETS', WPTV_URL . '/assets/' );
	define( 'WPTV_TEMPLATES', WPTV_PATH . '/templates/' );

	define( 'WPTV_PRICING', admin_url( 'edit.php?post_type=wptv&page=wp-live-tv-pricing' ) );

	/** do the activation stuffs */
	register_activation_hook( __FILE__, function () {
			include WPTV_INCLUDES . 'class-install.php';
	} );


	include WPTV_INCLUDES . '/base.php';
}