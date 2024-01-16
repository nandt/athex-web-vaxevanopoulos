jQuery(document).ready(function ($) {

    $(document).ajaxComplete(function (event, xhr, settings) {
        if (settings.url.indexOf('/views/ajax') !== -1) {
            $('[id^="views-exposed-form-solutions-solutions-overview"] .js-form-item').click(function () {

                $(this).addClass('active');
                setTimeout(function () {
                    $('[id^="edit-submit-solutions"]').click();
                }, 1000);
            });
        }
    });

    $('[id^="views-exposed-form-solutions-solutions-overview"] .js-form-item:nth-child(2)').addClass('active');
    $('[id^="views-exposed-form-solutions-solutions-overview"] .js-form-item').click(function () {
        $(this).addClass('active');
        setTimeout(function () {
            $('[id^="edit-submit-solutions"]').click();
        }, 1000);
    });
})