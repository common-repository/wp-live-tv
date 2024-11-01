;(function ($) {
    const $importer = {

        //check if the page from csv importer file uploader
        is_csv: window.location.search.indexOf('type=csv_import') > -1,

        //set initial file path to false = 0
        file: 0,

        init: () => {

            //don't need to run if is custom CSV importer
            if (!$importer.is_csv) {
                $importer.initMultiselect();

                $('.ms-selection>.country-search.selection').on('keyup', $importer.handleSelectionSearch);
                $('.wptv-remove-country').on('click', $importer.handleRemove)
            }

            $('#wptv-run-import').on('click', $importer.initImporter);

            $(document).on('click', '.ms-selectable .ms-list>li.disabled', $importer.showPromo);

        },

        /**
         * Initialize The Importer
         */
        initImporter: function () {

            if ($(this).hasClass('import-done')) {
                window.location.reload();

                return;
            }

            let $countries = [];
            let $total = 0;
            let file = 0;

            if (!$importer.is_csv) {
                const li = $('.ms-selection .ms-list>.ms-selected').not('.disabled');

                if (null === $countries || li.length < 1) {
                    alert(wptv.i18n.alert_no_country);
                    return;
                }

                $('#run-import').text(wptv.i18n.running);

                li.each(function () {
                    $total += parseInt($(this).attr('data-count'));
                    $countries.push($(this).attr('data-country'));
                });

            } else {
                file = $('#csv_file').val();
            }

            $('#wptv-import-progress').css('display', 'flex');
            $('.progress-count-all').text($total);

            $importer.handleImport($countries, $total, file);

        },

        handleImport: function (countries, $total, file = 0) {

            wp.ajax.send('wptv_import_channels', {
                data: {
                    countries,
                    file
                },

                success: response => {

                    // Get imported count from response
                    const $imported = response.imported;

                    // Update the progress bar
                    $('#progress').css('width', (100 / $total) * $imported + '%');

                    /**
                     * If not update then update the percentage and total text
                     * else updated the updated count and new added count text
                     */
                    const percentage = $imported / $total * 100;
                    $('.progress-percentage').text(Math.ceil(percentage) + '%');
                    $('.progress-count-number').text($imported);

                    // Import is done
                    if (response.done) {
                        $('.import-progress-content>h3').text('Hooray! Import is Complete.');
                        $('.import-progress-content').addClass('done');
                        $('#progress').removeClass('progress-bar-animated progress-bar-striped');

                        return;
                    } else if (response.error) {
                        $('.import-progress-content>h3').text(':( No channels found! Please, contact with the plugin author about the issue.');
                        return;
                    }

                    // Recursive import
                    $importer.handleImport(countries, $total);

                },

                error: error => console.log(error),

            });
        },

        initMultiselect: function () {

            let imported_countries = wptv.imported_countries;

            if (imported_countries !== '') {

                $('.deselect').addClass('disabled');

                if (Array.isArray(imported_countries)) {
                    imported_countries.forEach(function (country) {
                        $('#wptv-import-country-select option[value="' + country + '"]').attr('selected', 'selected');
                    });
                } else {
                    Object.keys(imported_countries).forEach(function (index) {
                        $('#wptv-import-country-select option[value="' + imported_countries[index] + '"]').attr('selected', 'selected');
                    });
                }

            }

            $('#wptv-import-country-select').multiSelect({
                selectableHeader: $importer.selectableHeader,

                selectableFooter: $importer.selectableFooter,

                selectionHeader: $importer.selectionHeader,

                selectionFooter: $importer.selectableFooter,

                //afterSelect: $importer.handeSelectionCount,
                //afterDeselect: $importer.handeSelectionCount,

                afterInit: () => {

                    $importer.appendMeta();

                    $('.wptv-importer .ms-selectable>.country-search').hideseek({
                        noData: wptv.i18n.no_country_found,
                        highlight: true
                    });

                    if (imported_countries.length !== '') {
                        $('.wptv-importer .ms-selection .ms-list>.ms-selected').addClass('disabled')
                            .append(`<a href="#" class="button wptv-remove-country button-link-delete">Remove</a>`);
                    }

                    //$importer.handeSelectionCount();

                }
            });

        },

        handeSelectionCount: function (elem) {

            const selector = $('#import-country-select');
            const selected = $('.ms-selection .ms-list>.ms-selected');

            let $total = 0;
            selected.each(function () {
                $total += parseInt($(this).attr('data-count'));
            });

            $('.selected-count').text(selected.length);

            const $allowed_country = 40;

            $('.remain-count').text($allowed_country - selected.length);
            $('.total-selection-count').text($total);

            if (selected.length > 20 && !wpradio.isPremium) {
                selector.multiSelect('deselect', elem);
                alert('Maximum 20 countries can be imported in the free version.');
            }

        },

        handleSelectionSearch: function () {

            const li = $('.ms-selection .ms-list>li');

            li.each(function () {
                if ('display: none;' === $(this).attr('style')) {
                    $(this).addClass('hide');
                } else {
                    $(this).removeClass('hide');
                }
            })

        },

        /**
         * Append channel number to the right
         */
        appendMeta: function () {

            const li = $('.wptv-importer .ms-list>li');

            li.each(function () {

                let $meta = `<span class="count" title="${wptv.i18n.count_title}">${$(this).attr('data-count')}</span>`;

                const country = $(this).data('country');

                if ($(this).hasClass('disabled')) {
                    $meta = `<span class="premium"> <span class="dashicons dashicons-star-filled"></span> <span class="pro-badge">PRO</span> </span> ${$meta}`;
                }

                $(this).prepend(`<img src="${wptv.pluginUrl}/assets/images/flags/${country}.svg" />`);

                $(this).append(`<span class="country-meta">${$meta}</span>`);

            });

            $('.wptv-importer .ms-selectable .ms-list>li')
                .append('<a href="javascript:;" class="button select-country" title="Select Country"><i class="dashicons dashicons-database-add"></i> </a>');

        },

        selectableHeader: () => {
            return `<input class="country-search" placeholder="${wptv.i18n.select_add_country}" type="text" data-list=".ms-selectable .ms-list" >
            <div class="ms-selectable-header">Available Countries <span>( 40+ ) | Total Channels: 400+</span></div>`;
        },

        selectableFooter: () => {
            return wptv.isPremium ? false : `<div class="ms-selectable-footer"><a href="${wptv.pricingPage}" class="button"> ${wptv.i18n.get_premium} </a> ${wptv.i18n.premium_promo}</div>`;
        },

        selectionHeader: () => {
            //const $remain = 40;

            //return `<div class="ms-selected-header">${wptv.i18n.selected_countries} <span> ( ${wptv.i18n.selected} <span class="selected-count">0</span> | ${wptv.i18n.remaining} <span class="remain-count">${$remain}</span> ) | <span>${wptv.i18n.total_channel} <span class="total-selection-count">0</span> </span> </span></div>`;
        },

        handleRemove: function (e) {

            $(this).attr('href', `${wptv.adminUrl}?action=wptv_remove_country&country=${$(this).parent().attr('data-country')}&nonce=${wptv.nonce}`);

            let foo = confirm('Are you sure you want to remove this country and all it\'s channels?');
            if (!foo) {
                e.preventDefault();
            }
        },

        showPromo: function () {
            $('.wptv-promo').removeClass('hidden');
        },
    };

    $(document).ready($importer.init);

    return $importer;

})(jQuery);