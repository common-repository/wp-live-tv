<h3 class="tab-content-title"><?php _e( 'Shortcodes', 'wp-live-tv' ) ?></h3>

<p>This plugin provides the following shortcodes:</p>

<!--wptv listing shortcode-->
<div class="tab-content-section">
    <h4 class="tab-content-section-title">TV Channels Listing Shortcode - [wptv_listing]</h4>

    <p><code>[wptv_listing]</code> - For displaying the tv channels listing use
        <strong>[wptv_listing]</strong> shortcode. This shortcode supports one attribute, the attribute is <code>country</code> </p>
    <p>In the <b>country</b> attribute, you can pass comma separated country codes to filter the listing.</p>

    <p><b>Example: </b> <code>[wptv_listing country="us, in"]</code></p>
</div>

<!--wptv country list shortcode-->
<div class="tab-content-section">
    <h4 class="tab-content-section-title">Country List Shortcode - [wptv_country_list]</h4>
    <p><code>[wptv_country_list]</code> – Use this short code for displaying all the countries in a list. Where users can browse the country's channels.</p>
    <p>This shortcode has no attributes</p>

    <p><b>Example:</b> <code>[wptv_country_list]</code></p>
</div>

<!--channel player shortcode-->
<div class="tab-content-section">
    <h4 class="tab-content-section-title">Channel Player Shortcode - [wptv_player]</h4>
    <p><code>[wptv_player]</code> – For displaying a specific channel player use <b>[wptv_player]</b> shortcode.</p>
    <p>This Shortcode supports an <code>id</code> attribute where you have to pass the id of a tv channel.</p>
    <p><b>Example:</b> <code>[wptv_player id="11"]</code></p>
</div>

<!--featured shortcode-->
<div class="tab-content-section">
    <h4 class="tab-content-section-title">Featured Channels Listing Shortcode - [wptv_featured]</h4>
    <p><code>[wptv_featured]</code> – For displaying the listing of the featured channels, use <b>[wptv_featured]</b>shortcode.</p>
    <p>This Shortcode supports 2 optional filter attributes. Those are <code>count</code> & <code>country</code>attributes.</p>
    <p>Use <b>count</b> attribute to limit how many Radio Stations you want to show on the listing. You can pass any valid number to this attribute</p>
    <p>Use <b>country</b> attribute to filter the listing by country. You can pass comma separated country codes to this attribute.</p>
    <p><b>Example:</b> <code>[wptv_featured count="10" country="us"]</code></p>
</div>
