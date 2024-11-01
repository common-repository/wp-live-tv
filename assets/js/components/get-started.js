;(function ($) {
    $(document).ready(function () {

        /*---- Handle tab links -----*/
        $(document).on('click', '.tab-links .tab-link', function (e) {
            e.preventDefault();

            $('.tab-links .tab-link, .tab-content').removeClass('active');
            $(this).addClass('active');

            const target = $(this).data('target');
            $(`#${target}`).addClass('active');

        });


        /*----- Handle FAQ collapse -------*/
        $('#faq .tab-content-section-title').on('click', function () {
            $('i', $(this)).toggleClass('dashicons-plus-alt dashicons-minus');
            $(this).next().slideToggle('active');
        });

    });
})(jQuery);