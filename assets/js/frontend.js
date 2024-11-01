;(function ($) {

    const app = {

        init: () => {
            app.handleSearch();
            app.handlePlayer();

            $('.sidebar-listing .wptv-lazy-load').lazy({
                appendScroll: $('.sidebar-listing')
            });
        },

        /** handle station search */
        handleSearch: function () {

            $('#tv_channel_search [name="country"], #tv_channel_search [name="category"]').select2({
                placeholder: function () {
                    $(this).data('placeholder')
                },

            });

            $('#tv_channel_search [name="keyword"]').select2({
                minimumInputLength: 2,
                placeholder: function () {
                    $(this).data('placeholder')
                },
                tags: [],
                ajax: {
                    url: wptv.ajax_url,
                    dataType: 'json',
                    type: "POST",
                    quietMillis: 50,

                    data: term => ({
                        term: term,
                        action: 'tv_channel_search'
                    }),

                    processResults: data => ({results: data}),

                    cache: true

                }
            });
        },

        /** handle player */
        handlePlayer: () => {
            new MediaElementPlayer('wptv_media_player', {
                videoWidth: '100%',
                videoHeight: '100%',
                startVolume: (wptv.volume / 100),
            });
        }

    };

    $(document).ready(app.init);
})(jQuery);