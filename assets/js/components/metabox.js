;(function ($) {
    $(document).ready(function () {
        const app = {

            init: function () {
                app.logoUpload();

                $(document).on('click', '.wptv-pro-feature', app.showPopup);
            },

            showPopup: () => {
                $('.wptv-promo').removeClass('hidden');
            },

            logoUpload: function () {
                var frame,
                    metaBox = $('.wptv-logo-metabox'),
                    addImgLink = metaBox.find('.select_img'),
                    delImgLink = metaBox.find('.delete_img'),
                    imgContainer = metaBox.find('.logo-metabox-preview'),
                    imgIdInput = metaBox.find('#_logo_url');

                //Handle Image Upload
                addImgLink.on('click', function (event) {

                    event.preventDefault();

                    if (frame) {
                        frame.open();
                        return;
                    }

                    frame = wp.media({
                        title: 'Select Image',
                        library: {
                            type: 'image'
                        },
                        button: {
                            text: 'Use this media'
                        },
                        multiple: false
                    });

                    frame.on('select', function () {

                        var attachment = frame.state().get('selection').first().toJSON();

                        imgContainer.attr('src', attachment.url);
                        imgIdInput.val(attachment.url);
                        delImgLink.removeClass('hidden');
                    });

                    frame.open();
                });

                //Hanlde Image Delete
                delImgLink.on('click', function (event) {

                    event.preventDefault();
                    imgContainer.attr('src', '');
                    delImgLink.addClass('hidden');
                    imgIdInput.val('');

                });
            }

        };

        $(document).ready(app.init);

    })
})(jQuery);
