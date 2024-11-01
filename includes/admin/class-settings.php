<?php

/** if class `WPTV_Settings` not exists yet */
if ( ! class_exists( 'WPTV_Settings' ) ) {

	class WPTV_Settings {
		private static $instance = null;
		private static $settings_api = null;

		public function __construct() {
			add_action( 'admin_init', array( $this, 'settings_fields' ) );
			add_action( 'admin_menu', array( $this, 'settings_menu' ) );
		}

		/**
		 * Registers settings section and fields
		 */
		public function settings_fields() {

			$sections = array(
				array(
					'id'    => 'wptv_general_settings',
					'title' => sprintf( __( '%s General Settings', 'wp-live-tv' ), '<i class="dashicons dashicons-admin-generic"></i>' )
				),
				array(
					'id'    => 'wptv_display_settings',
					'title' => sprintf( __( '%s Display Settings', 'wp-live-tv' ), '<i class="dashicons dashicons-welcome-view-site"></i>' )
				),
				array(
					'id'    => 'wptv_player_settings',
					'title' => sprintf( __( '%s Player Settings', 'wp-live-tv' ), '<i class="dashicons dashicons-controls-play"></i>' ),
				),
				array(
					'id'    => 'wptv_color_settings',
					'title' => sprintf( __( '%s Color Settings', 'wp-live-tv' ), '<i class="dashicons dashicons-admin-customizer"></i>' ),
				),
			);

			$fields = array(

				'wptv_general_settings' => [
					[
						'name'    => 'channel_page',
						'label'   => __( 'Select Archive Page', 'wp-live-tv' ),
						'desc'    => __( 'Select the channel archive page  which contains <code>[wptv_listing]</code> shortcode.',
						                 'wp-live-tv' ),
						'type'    => 'pages',
						'default' => get_option( 'wptv_channels_page' ),
					],

				],

				'wptv_display_settings' => array(
					[
						'name'    => 'posts_per_page',
						'label'   => __( 'Channels Per Page :', 'wp-live-tv' ),
						'desc'    => __( 'Enter the number how many channels will be displayed on the listing page.', 'wp-live-tv' ),
						'type'    => 'number',
						'default' => get_option( 'posts_per_page' ),
					],
					array(
						'name'    => 'layout',
						'default' => 'list',
						'class'   => !wptv_fs()->can_use_premium_code__premium_only() ? 'wptv-pro-feature' : '',
						'label'   => __( 'Channel Archive Layout', 'wp-live-tv' ),
						'desc'    => __( 'Choose the Channel Archive Page Listing View. Left (List View), Right (Grid List View).',
						                 'wp-live-tv' ),
						'type'    => 'image_choose',
						'options' => [
							'list' => WPTV_ASSETS . '/images/list.png',
							'grid' => WPTV_ASSETS . '/images/grid.png',
						],
					),

					array(
						'name'    => 'show_description',
						'label'   => __( 'Show Description', 'wp-live-tv' ),
						'desc'    => __( 'Show the channel description on the listing', 'wp-live-tv' ),
						'type'    => 'switch',
						'default' => 'on'
					),
					array(
						'name'    => 'show_search',
						'class'   => !wptv_fs()->can_use_premium_code__premium_only() ? 'wptv-pro-feature' : '',
						'label'   => __( 'Show Search Field', 'wp-live-tv' ),
						'desc'    => __( 'Show the search bar on the listing', 'wp-live-tv' ),
						'type'    => 'switch',
						'default' => 'off',
					),
					array(
						'name'    => 'you_may_like',
						'class'   => ! wptv_fs()->can_use_premium_code__premium_only() ? 'wptv-pro-feature' : '',
						'label'   => __( 'Show Related Channels', 'wp-live-tv' ),
						'desc'    => __( 'Show the related channels on the single channel page.', 'wp-live-tv' ),
						'type'    => 'switch',
						'default' => 'on',
					),
					array(
						'name'    => 'single_next_prev',
						'class'   => ! wptv_fs()->can_use_premium_code__premium_only() ? 'wptv-pro-feature' : '',
						'label'   => __( 'Show Prev/ Next', 'wp-live-tv' ),
						'desc'    => __( 'Show the previous and next channel navigation on the single channel page.',
						                 'wp-live-tv' ),
						'type'    => 'switch',
						'default' => 'off'
					),
				),

				'wptv_player_settings' => array(

					[
						'name'    => 'autoplay',
						'class'   => ! wptv_fs()->can_use_premium_code__premium_only() ? 'wptv-pro-feature' : '',
						'label'   => __( 'Channel Autoplay', 'wp-live-tv' ),
						'desc'    => __( 'ON/ OFF Channel Autoplay.', 'wp-live-tv' ),
						'type'    => 'switch',
						'default' => 'on',
					],
					[
						'name'    => 'player_volume',
						'class'   => ! wptv_fs()->can_use_premium_code__premium_only() ? 'wptv-pro-feature' : '',
						'label'   => __( 'Player Volume :', 'wp-live-tv' ),
						'desc'    => __( 'Set the player default volume.', 'wp-live-tv' ),
						'type'    => 'slider',
						'default' => 70,
					],
				),

				'wptv_color_settings' => [
					[
						'name'    => 'listing_style_heading',
						'default' => __( 'Channel Listing Color', 'wp-live-tv' ),
						'type'    => 'heading',
					],
					[
						'name'    => 'listing_bg',
						'label'   => __( 'Listing Background Color :', 'wp-live-tv' ),
						'desc'    => __( 'Customize the channel listing background color.', 'wp-live-tv' ),
						'default' => '#F7F7F7',
						'class'   => ! wptv_fs()->can_use_premium_code__premium_only() ? 'wptv-pro-feature' : '',
						'type'    => 'color',
					],
					[
						'name'    => 'listing_hover_bg',
						'label'   => __( 'Listing Hover Background :', 'wp-live-tv' ),
						'desc'    => __( 'Customize the channel listing hover background color.', 'wp-live-tv' ),
						'default' => '#F7F7F7',
						'class'   => ! wptv_fs()->can_use_premium_code__premium_only() ? 'wptv-pro-feature' : '',
						'type'    => 'color',
					],
					[
						'name'    => 'listing_play_color',
						'label'   => __( 'Listing Play Color :', 'wp-live-tv' ),
						'desc'    => __( 'Customize the listing play button color.', 'wp-live-tv' ),
						'default' => '#fff',
						'class'   => ! wptv_fs()->can_use_premium_code__premium_only() ? 'wptv-pro-feature' : '',
						'type'    => 'color',
					],

					[
						'name'    => 'listing_play_bg_color',
						'label'   => __( 'Listing Play Background:', 'wp-live-tv' ),
						'desc'    => __( 'Customize the listing play button background color.', 'wp-live-tv' ),
						'default' => '#E12A20',
						'class'   => ! wptv_fs()->can_use_premium_code__premium_only() ? 'wptv-pro-feature' : '',
						'type'    => 'color',
					],

					[
						'name'    => 'player_style_heading',
						'default' => __( 'Video Player Color', 'wp-live-tv' ),
						'type'    => 'heading',
					],

					[
						'name'    => 'player_bg_color',
						'label'   => __( 'Player Background :', 'wp-live-tv' ),
						'desc'    => __( 'Customize the channel video player .', 'wp-live-tv' ),
						'default' => '#000',
						'class'   => ! wptv_fs()->can_use_premium_code__premium_only() ? 'wptv-pro-feature' : '',
						'type'    => 'color',
					],
				],

			);

			self::$settings_api = new WP_Military_Settings_API();

			//set sections and fields
			self::$settings_api->set_sections( $sections );
			self::$settings_api->set_fields( $fields );

			//initialize them
			self::$settings_api->admin_init();
		}

		public function wp_radio_doc(){
			include WPTV_INCLUDES . 'admin/views/html-wpradio-content.php';
		}

		public static function block_doc() { ?>
            <div class="wp-dark-mode-gutenberg-doc">
                <h2>How to display a Channel using Gutenberg block</h2>

                <ol class="doc-section">
                    <li>
                        <h3>While you are on the post/page edit screen click on gutenberg plus icon to add a new gutenberg block</h3>
                        <img src="<?php echo WPTV_ASSETS . 'images/gutenberg/step-1.png'; ?>" alt="step-1">
                    </li>

                    <li>
                        <h3>Add "TV Channel" block from "Text" category</h3>
                        <img src="<?php echo WPTV_ASSETS . 'images/gutenberg/step-2.png'; ?>" alt="step-2">
                    </li>

                    <li>
                        <h3>Enter the ID of the channel you want to display from the right sidebar block settings.</h3>
                        <img src="<?php echo WPTV_ASSETS . 'images/gutenberg/step-3.png'; ?>" alt="step-2">
                    </li>
                </ol>


            </div>
		<?php }

		public static function widget_doc() { ?>
            <div class="wptv-elementor-doc">
                <h2>How to display Channel using Elementor Widget</h2>

                <ol class="doc-section">

                    <li>
                        <h3>Add "TV Channel" widget from "Basic" category</h3>
                        <img src="<?php echo WPTV_ASSETS . 'images/elementor/step-1.png'; ?>" alt="step-2">
                    </li>

                    <li>
                        <h3>Enter the channel ID from widget settings and you are done</h3>
                        <img src="<?php echo WPTV_ASSETS . 'images/elementor/step-2.png'; ?>" alt="step-2">
                    </li>
                </ol>


            </div>
		<?php }

		public function shortcodes_view() { ?>
            <div class="shortcode">
                <p><b>✅</b>
                    <b><code>[wptv_listing]</code></b> - Use this shortcode in a page for listing the TV Channels. This shortcode supports optional country & category attributes where you can pass comma separated country code and category to filter the TV Channels from specific categories and countries.
                </p>

                <p><b>Examples:</b></p>

                <p style="margin: 10px 0 0 40px"> → <code>[wptv_listing]</code> - Display all the TV Channels.</p>
                <p style="margin: 10px 0 0 40px"> →
                    <code>[wptv_listing country="canada, united kingdom, united states"]</code> - Display specific countries TV Channels.
                </p>
                <p style="margin: 10px 0 0 40px"> →
                    <code>[wptv_listing category="news, entertainment, music"]</code> - Display specific categories TV Channels.
                </p>
                <p style="margin: 10px 0 0 40px"> →
                    <code>[wptv_listing country="united kingdom, united states" category="news, music"]</code> - Display specific categories & countries TV Channels.
                </p>
            </div>

            <div class="shortcode">
                <p><b>✅ 💰 (PRO) <code>[wptv_country_list]</code>
                    </b> - Use this short code anywhere display all the clickable country list of the TV Channels.
                </p>

                <p><b>Examples:</b></p>

                <p style="margin: 10px 0 0 40px"> →
                    <code>[wptv_country_list]</code> - Display clickable country list of the TV Channels.</p>
            </div>

            <div class="shortcode">
                <p><b>✅ 💰 (PRO)
                        <code>[wptv_featured]</code></b> - Use this short code anywhere display all the clickable country list of the TV Channels.
                </p>

                <p><b>Example:</b></p>

                <p style="margin: 10px 0 0 40px"> →
                    <code>[wptv_country_list]</code> - Display clickable country list of the TV Channels.</p>
            </div>

            <div class="shortcode">
                <p><b>✅ 💰 (PRO)
                        <code>[wptv_channel]</code></b> -  Display a single TV channel player. This shortcode required an "id" attribute. The id attribute is the id of the channel.
                </p>

                <p><b>Example:</b></p>

                <p style="margin: 10px 0 0 40px"> →
                    <code>[wptv_channel id="9"]</code> - Display a TV channel player.</p>
            </div>

		<?php }

		public function get_started() { ?>
            <div class="get-started-section">
                <h2>How to install & activate the WP Live TV plugin.</h2>
                <h2>How To Import Channels</h2>
                <h2>Add New TV Channel</h2>
                <h2>Settings</h2>
            </div>
		<?php }

		/**
		 * Register the plugin page
		 */
		public function settings_menu() {
			add_submenu_page( 'edit.php?post_type=wptv',
			                  __( 'WP TV Settings', 'wp-live-tv' ),
			                  __( 'Settings', 'wp-live-tv' ),
			                  'manage_options',
			                  'wptv-settings',
			                  array( $this, 'settings_page' ) );
		}

		/**
		 * Display the plugin settings options page
		 */
		public function settings_page() {
			echo '<div class="wrap">';
			settings_errors();

			echo '<div class="wrap">';
			echo sprintf( "<h2>%s</h2>", __( 'WPTV Settings', 'wp-live-tv' ) );
			self::$settings_api->show_settings();
			echo '</div>';

			echo '</div>';
		}

		/**
		 * @return WPTV_Settings|null
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

	}
}

WPTV_Settings::instance();