<?php

defined( 'ABSPATH' ) || exit;


/**
 * Register all the block assets so that they can be enqueued through the block editor
 * in the corresponding context
 */
add_action( 'init', 'wptv_register_block' );
function wptv_register_block() {
	// If block editor is not active, bail.
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}

	/**
	 * Frontend Scripts
	 */
	wp_register_style( 'wptv-editor', WPTV_ASSETS . '/css/frontend.css', [ 'dashicons' ] );

	wp_register_script( 'wptv-editor-frontend',
		WPTV_ASSETS . '/assets/js/frontend.min.js',
		[
			'jquery',
			'jquery-ui-slider',
			'wp-util',
			'wp-mediaelement',
		],
		WPTV_VERSION,
		true );

	// Register the block editor scripts
	wp_register_script( 'wptv-editor-script',
		plugins_url( 'build/index.js', __FILE__ ),
		[
			'wp-blocks',
			'wp-i18n',
			'wp-element',
			'wp-editor',
			//'wptv-editor-frontend',
		],
		filemtime( plugin_dir_path( __FILE__ ) . 'build/index.js' ) );

	// Register the block editor styles
	wp_register_style( 'wptv-editor-style',
		plugins_url( 'build/editor.css', __FILE__ ),
		[ 'wptv-editor' ],
		filemtime( plugin_dir_path( __FILE__ ) . 'build/editor.css' ) );

	// Register the front-end styles
	wp_register_style( 'wptv-frontend-styles',
		plugins_url( 'build/style.css', __FILE__ ),
		[],
		filemtime( plugin_dir_path( __FILE__ ) . 'build/style.css' ) );


	register_block_type( 'wptv/channel',
		[
			'editor_script' => 'wptv-editor-script',
			'editor_style'  => 'wptv-editor-style',
			'style'         => 'wptv-frontend-styles',
		] );

	if ( function_exists( 'wp_set_script_translations' ) ) {
		/**
		 * Adds internalization support
		 */
		wp_set_script_translations( 'wptv-editor-script', 'wptv' );
	}

}