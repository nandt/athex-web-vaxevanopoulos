jQuery(document).ready(function ($) {

    var sliderNewsPress;


    sliderNewsPress = tns({
        container: '.newsPressItemsWrapper',
        loop: false,
        items: 1,
        nav: false,
        autoplay: false,
        speed: 400,
        autoplayButtonOutput: false,
        mouseDrag: true,
        preventScrollOnTouch: 'auto',
        lazyload: true,
        controlsContainer: "#customize-controls-view",
        responsive: {
            768: {
                items: 3,
                fixedWidth: 335,
                gutter: 56
            }
        }
    });

    $(document).ajaxComplete(function (event, xhr, settings) {
        if (settings.url.indexOf('/views/ajax') !== -1) {
            sliderNewsPress.destroy();
            sliderNewsPress = tns({
                container: '.newsPressItemsWrapper',
                loop: false,
                items: 1,
                nav: false,
                autoplay: false,
                speed: 400,
                autoplayButtonOutput: false,
                mouseDrag: true,
                preventScrollOnTouch: 'auto',
                lazyload: true,
                controlsContainer: "#customize-controls-view",
                responsive: {
                    768: {
                        items: 3,
                        fixedWidth: 335,
                        gutter: 56
                    }
                }
            });
            $('.layout_padding:nth-child(even)').addClass('even-slide');

            $('[id^="views-exposed-form-news-press-releases-news-press"] .js-form-item').click(function () {

                $(this).addClass('active');
                setTimeout(function () {
                    $('[id^="edit-submit-news-press-releases"]').click();
                }, 1000);
            });
        }
    });

    $('.layout_padding:nth-child(even)').addClass('even-slide');

    $('[id^="views-exposed-form-news-press-releases-news-press"] .js-form-item:nth-child(2)').addClass('active');
    $('[id^="views-exposed-form-news-press-releases-news-press"] .js-form-item').click(function () {
        $(this).addClass('active');
        setTimeout(function () {
            $('[id^="edit-submit-news-press-releases"]').click();
        }, 1000);
    });
})