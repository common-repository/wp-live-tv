<div class="wrap">

    <div class="tab-wrap">

        <div class="tab-links">
            <a href="javascript:;" data-target="new" class="tab-link active">
                <i class="dashicons dashicons-info-outline"></i>
                What's New?
            </a>

            <a href="javascript:;" data-target="setup" class="tab-link">
                <i class="dashicons dashicons-admin-tools"></i>
                Plugin Setup</a>

            <a href="javascript:;" data-target="import" class="tab-link">
                <i class="dashicons dashicons-database-import"></i>
                Import Channels</a>

            <a href="javascript:;" data-target="shortcodes" class="tab-link">
                <i class="dashicons dashicons-shortcode"></i>
                Shortcodes</a>

            <a href="javascript:;" data-target="gutenberg" class="tab-link">
                <i class="dashicons dashicons-table-row-after"></i>
                Gutenberg Block</a>

            <a href="javascript:;" data-target="elementor" class="tab-link">
                <i class="dashicons dashicons-align-pull-left"></i>
                Elementor Widget</a>

            <a href="javascript:;" data-target="faq" class="tab-link">
                <i class="dashicons dashicons-editor-help"></i>
                FAQ</a>
        </div>

        <div id="new" class="tab-content active">
		    <?php include_once WPTV_INCLUDES . '/admin/views/get-started/new.php'; ?>
        </div>

        <div id="setup" class="tab-content">
	        <?php include_once WPTV_INCLUDES . '/admin/views/get-started/setup.php'; ?>
        </div>

        <div id="import" class="tab-content">
		    <?php include_once WPTV_INCLUDES . '/admin/views/get-started/import.php'; ?>
        </div>

        <div id="shortcodes" class="tab-content">
	        <?php include_once WPTV_INCLUDES . '/admin/views/get-started/shortcodes.php'; ?>
        </div>

        <div id="gutenberg" class="tab-content">
	        <?php include_once WPTV_INCLUDES . '/admin/views/get-started/gutenberg.php'; ?>
        </div>

        <div id="elementor" class="tab-content">
	        <?php include_once WPTV_INCLUDES . '/admin/views/get-started/elementor.php'; ?>
        </div>

    </div>

</div>