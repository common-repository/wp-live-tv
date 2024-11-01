=== Live TV Player - Worldwide Live TV Channels Player for WordPress ===
Contributors: wpmilitary, princeahmed
Tags: live, online, tv, player, live channel, youtube, vimeo, dailymotion, video, live tv, stream, player, audio
Requires at least: 5.0
Tested up to: 5.8
Requires PHP: 5.6
Stable tag: 1.0.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Make Worldwide TV Channels Directory Website. Import And Play TV Channels From All Over The World and
Can Add Unlimited TV Channels From Youtube, Vimeo, Dailymotion, Soundcloud etc.

== Description ==

Live TV Player is a Plugin for WordPress to create a Worldwide TV Station Channel Directory Website.
You Can Add and Play Unlimited Live TV Video Stream Link Such as: Youtube, Dailymotion, Vimeo, Souncloud or Self Hosted Videos.

👁️ [View Demo](http://webradiodirectory.com/live-tv)


== Features ==
* **Import Channels** - (You can import more than 400+ channels from 40+ countries)
* **Add Unlimited Channels** – (You can add unlimited channel with TV channel Logo, Categories, Description, Youtube Channel Link, Website Link).
* **Multiple Video Sources Supported** - (You can play Youtube, Vimeo, Dailymotion, Souncloud and Self Hosted Videos)
* **M3U8 stream link supports** – (.m3u8 extension stream can be played)
* **Channel Search** – (Users can search/ filter channels using country, category and channel’s name)
* **Channel Autoplay** – Control the channel autoplay behaviour.
* **Channel Archive Shortcode** – ([wptv_listing] – Display the channel archive listing)
* **Featured Channel Shortcode** – ([wptv_featured] – Display the featured channels)
* **Country List Shortcode** – ([wptv_country_list] – Display all the countries of the TV channels)

== How To Use ==
After activating the plugin you can see a new menu **TV Channels** added to the admin sidebar menu panel.

Then you need to import tv channels from the **Import Channels** page or add new TV channels from **Add New Channel** menu.

In the Add New Channel page you need to add a channel by entering Channel name as Title, Description of the Channel, Channel Logo as featured image, channel video link, channel youtube and website link, Channel country and categories.

After adding the new channel you can view the new channel with a formatted design with a video player to play the channel live video in the single page.

You can list all the channels that you have added, in any page using the `[wptv_listing]` shortcode, Channels also can be listed by country and categories.

Users can search any channel by the channel name, category and country by using the search bar of the channel archive listing page.

== IMPORT CHANNELS: ==

After installing and activating the plugin successfully, The next step is to import the tv channels.

To import the tv channels, you need to click the Import Channels submenu under the TV Channels main menu in your WordPress sidebar admin menu.


== Add New Channel ==
You can add unlimited TV channels. For adding a new TV channel navigate to `TV Channels > Add New Channel`. On the Add New Channel page you need to fill some field to add a channel properly.

1. **Channel Title** - You need to enter the channel name as title.
2. **Description** - Add some description about the channel.</li>
3. **Category** - Select the related category for the channel. Like: Entertainment, New etc.
4. **Country** - Select the country for the channel.</li>
5. **Channel Logo** - Use the featured image as the channel logo.</li>
6. **Channel Video Link** - Enter the video link for the channel. You can enter Youtube, Dailymotion, Vimeo, Soundcloud or self hosted video link.
7. **Featured Channel** - Use the switch to featured the channel. Later you can display the featured channel by using the`[wptv_featured]` shortcode.
8. **Channel Website** - Enter the website URL of the channel.</li>
9. **Youtube Channel** - Enter the youtube channel link.</li>

== Shortcodes ==
The Plugin provides 4 Shortcodes.

* **[wptv_listing]** – Use this shortcode in a page for listing the channels. This shortcode supports country && category attributes where you can pass comma separated country code and category.
**Example:** `[wptv_listing country="us, ru, bd" category="rock,news"]`

* **[wptv_country_list]** - Display all the channels countries. There is no attribute for this shortcode.

* **[wptv_featured]** - Display all the featured TV channels. This shortcode supports 3 (count, country, col) attributes.
**Example:** `[wptv_featured count="12" country="germany" col="4"]`

* **[wptv_player]** - Display a single TV channel player. This shortcode required an **id** attribute. The id attribute is the id of the channel.
**Example:** `[wptv_channel id="9"]`

== Settings ==
* **Select Archive Page** - On the Select Archive Page settings field you have to choose the archive page for the tv channels. You have to put the <code>[wptv_listing]</code> shortcode on the page.
* **Channel Autoplay** - Control the channel autoplay.
* **Show Description** - You can change whether the channel description show on the channel listing.
* **Show Search Field** - You can change whether the channel search bar show on the archive page.
* **Show Related Channels** - You can change whether the related channels show on the channel single page.
* **Show Prev/ Next** - You can change whether the previous/ next channel arrows show on the channel single page.

== 🔥 WHAT’S NEXT ==
If you like this docs plugin, then consider checking out our other projects:

* [WP Radio - Wordlwide Radio Station Directory Player](https://wordpress.org/plugins/wp-radio) - WP Radio is a Worldwide Radio Station Directory Player Plugin for WordPress, to Create Worldwide Radio Station Directory Website.

== Screenshots ==
1. TV Channels Import
2. TV Channels Archive Page
3. TV Channel Single Page
4. TV Channel Player Gutenberg Block
5. TV Channel Player Elementor Widget

== Changelog ==

= 1.0.5 =
* Fix: Added WordPress - v8 compatibility.

= 1.0.4 =
* New: Added Channel Player shortcode
* New: Added Country List Widget
* New: Added External Type Video Link
* New: Channels per page settings
* New: Added Color Settings
* New: Added Default Volume Settings
* Improvement: Updated TV Player gutenberg block
* Improvement: Updated TV Player elementor widget

= 1.0.3 =
* New: Add WordPress 5.6 Compatibility

= 1.0.2 =
* New: Added autoplay settings
* New: Added country list shortcode
* New: Added wptv_featured shortcode
* New: Added TV channel player shortcode
* Fix: Fixed the no working settings field


= 1.0.1 =
* Add wptv_country_list shortcode
* Add archive listing layout settings
* Add watch live button
* Add iframe video support
* Add TV Channel Gutenberg Block
* Add TV Channel Elementor Widget

= 1.0.0 =
*   Initial release