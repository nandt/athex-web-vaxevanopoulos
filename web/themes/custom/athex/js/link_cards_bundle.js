jQuery(document).ready(function ($) {
    $('.link_card').each(function (index) {
        var teaserNumber = $(this).find('.oli-teaser-number');

        if ($('.link_card').length < 10) {
            teaserNumber.text('0' + (index + 1));
        } else {
            teaserNumber.text(index + 1);
        }
    });
});