<?php

defined( 'ABSPATH' ) || exit();

$selected = get_option( 'wptv_imported_countries' );
$selected = array_filter( (array) $selected );

?>

<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e( 'Import Channels', 'wp-live-tv' ); ?></h1>

        <div class="import-instructions">
            <h4>Import Instructions:</h4>
            <p>❈ Select the countries from the left country list, which you want to import.</p>
            <p>❈ After selecting any country, the country will move to the right box where all the imported countries will be listed.</p>
            <p>❈ If any error occurred during import, Please reload the page and try again with the previous selected countries.</p>
        </div>

    <div class="wptv-importer">
        <select multiple="multiple" id="wptv-import-country-select" name="import-country-select[]">
		    <?php

		    $i = 0;
		    foreach ( wptv_country_channels_map() as $key => $country ) {
			    printf( '<option value="%1$s" data-country="%1$s" data-count="%2$s" %4$s %5$s>%3$s</option>', $key, $country['count'],
				    $country['country'], in_array( $key, $selected ) ? 'selected' : '',
				    ( ! wptv_fs()->can_use_premium_code__premium_only() ? ( $i < 5 ? '' : 'disabled' ) : '' ) );

			    $i ++;
		    }

		    ?>
        </select>

        <div class="import-actions">
            <a href="javascript:void(0)" class="run-import button button-primary button-hero" id="wptv-run-import">
                <i class="dashicons dashicons-database-import"></i> <?php _e( 'Start Import', 'wp-live-tv' ); ?>
            </a>
        </div>

        <div class="import-progress" id="wptv-import-progress">
            <div class="import-progress-content">
                <h3> Please wait, This may take several minutes.</h3>
                <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" id="progress"
                            style="width:0.2%">
                    </div>
                </div>
                <div class="progress-status">
                    <span class="progress-percentage">0%</span>
                    <span class="progress-count">
                        <span class="progress-count-number">0</span>/
                        <span class="progress-count-all">0</span>
                    </span>

                    <div class="new-added"><strong>New Added:</strong> <span class="new-added-count">0</span> Channels</div>
                    <span class="updated"><strong>Updated:</strong> <span class="updated-count">0</span> Channels</span>
                </div>
                <div class="progress-actions">

                    <a href="<?php echo admin_url( 'edit.php?post_type=wptv' ); ?>"
                            class="button button-primary"
                            id="import-done"><?php _e( 'See all Channels', 'wp-live-tv' ); ?></a>

                    <a href="<?php echo admin_url( 'edit.php?post_type=wptv&page=import-channels' ); ?>"
                            class="button button-secondary"
                            id="import-more"><?php _e( 'Import More', 'wp-live-tv' ); ?></a>

                    <a href="<?php echo admin_url( 'edit.php?post_type=wptv&page=import-channels' ); ?>"
                            class="button button-large button-link-delete" id="cancel-import">Cancel Import</a>

                </div>
            </div>
        </div>

    </div>
</div>
