<?php

global  $post_id ;
$video_link = wptv_get_meta( $post_id, '_video_link' );
$is_external = wptv_get_meta( $post_id, 'is_external' );
$logo_url = wptv_get_meta( $post_id, '_logo_url' );
$is_featured = wptv_get_meta( $post_id, '_featured_channel' );
$website = wptv_get_meta( $post_id, '_website' );
$youtube = wptv_get_meta( $post_id, '_youtube' );
?>

<table class="form-table">
    <tbody>

    <!-- Video link-->
    <tr>
        <th scope="row">
            <label for="_video_link"><?php 
esc_html_e( 'Video Link:', 'wp-live-tv' );
?></label>
        </th>
        <td>
            <input name="_video_link" type="text" id="_video_link" value="<?php 
echo  esc_url( $video_link ) ;
?>" class="regular-text ltr">
            <p class="description">
		        <?php 
_e( 'Enter the Live Stream Video Link of the tv channel.<br> You can enter <b>Youtube</b>, <b>Vimeo</b>, <b>Dailymotion</b>, <b>Soundcloud</b> or <b>Self Hosted</b> Video URL.', 'wp-live-tv' );
?>
                <br> You also can use the channel website or youtbube URL as external link.
            </p>
        </td>
    </tr>

    <!-- is_external -->
    <tr>
        <th scope="row">
            <label for="is_external"><?php 
esc_html_e( 'Is External Link?:', 'wp-live-tv' );
?></label>
        </th>
        <td>

            <div class="checkbox">
                <input type="checkbox" name="is_external" id="is_external"
                        value="yes" <?php 
checked( 'yes', $is_external );
?>/>
                <div>
                    <label for="is_external"></label>
                </div>
            </div>

            <p class="description">
                Check if the video link is an external website URL.
            </p>
        </td>
    </tr>

    <!--Logo-->
    <tr>
        <th scope="row">
            <label for="_logo_url"><?php 
esc_html_e( 'Channel Logo:', 'wp-live-tv' );
?></label>
        </th>
        <td>
            <div class="wptv-logo-metabox">
                <div class="logo-metabox-actions">
                    <input type="text" id="_logo_url" name="_logo_url" value="<?php 
echo  esc_url( $logo_url ) ;
?>" placeholder="<?php 
_e( 'Enter the image url or select by clicking the plus icon.', 'wp-live-tv' );
?>">
                    <a href="#" class="button button-primary select_img"><i class="dashicons dashicons-plus-alt"></i></a>
                    <a href="#" class="button button-link-delete delete_img <?php 
echo  ( !empty($logo_url) ? '' : 'hidden' ) ;
?> "><i class="dashicons dashicons-trash"></i></a>
                </div>

                <p class="description">Enter the URL or select the channel logo image.</p>

                <img src="<?php 
echo  esc_url( $logo_url ) ;
?>" class="logo-metabox-preview">

            </div>
        </td>
    </tr>

    <!-- Station website URL-->
    <tr>
        <th scope="row">
            <label for="_website"><?php 
esc_html_e( 'Channel Website:', 'wp-live-tv' );
?></label>
        </th>
        <td>
            <input name="_website" type="text" id="_website" value="<?php 
echo  esc_url( $website ) ;
?>" class="regular-text ltr">
            <p class="description"><?php 
_e( 'Enter the website url of the channel.', 'wp-live-tv' );
?></p>
        </td>
    </tr>

    <!-- Station youtube channel-->
    <tr>
        <th scope="row">
            <label for="_youtube"><?php 
esc_html_e( 'Youtube Channel:', 'wp-live-tv' );
?></label>
        </th>
        <td>
            <input name="_youtube" type="text" id="_youtube" value="<?php 
echo  esc_url( $youtube ) ;
?>" class="regular-text ltr">
            <p class="description"><?php 
_e( 'Enter the youtube channel link of this channel.', 'wp-live-tv' );
?></p>
        </td>
    </tr>

    <!-- Featured Channel switcher -->
    <tr class="<?php 
echo  ( !wptv_fs()->can_use_premium_code__premium_only() ? 'wptv-pro-feature' : '' ) ;
?>">
        <th scope="row">
            <label for="_featured_channel"><?php 
esc_html_e( 'Featured Channel:', 'wp-live-tv' );
?></label>
        </th>
        <td>

            <div class="checkbox">
                <input type="checkbox" name="_featured_channel" id="_featured_channel"
                        value="yes" <?php 
checked( 'yes', $is_featured );
?>/>
                <div>
                    <label for="_featured_channel"></label>
                </div>
            </div>

            <p class="description">
			    <?php 
_e( 'Turn ON, to featured this channel. Get the featured channels by using <code>[wptv_featured]</code> shortcode.', 'wp-live-tv' );
?>
            </p>
        </td>
    </tr>


    </tbody>
</table>

<?php 
wptv()->get_template( 'admin/promo', [
    'is_hidden' => true,
] );