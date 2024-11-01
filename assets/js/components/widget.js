;(function ($) {
    $(document).ready(function () {

        if (!$('body').hasClass('widgets-php')) {
            return;
        }

        $('[id*="_tv_country_list"] .widget-title  h3').prepend(`<img src="${wptv.pluginUrl}/assets/images/wptv-icon-20x20.png" alt="WP TV Country List"/>`)

    });
})(jQuery);