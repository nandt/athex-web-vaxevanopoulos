jQuery(document).ready(function ($) {
    $('.overlay_link_item').each(function(index) {
        var teaserNumber = $(this).find('.oli-teaser-number');

        if($('.overlay_link_item').length<10){
        teaserNumber.text('0' + (index+1));
        } else {
            teaserNumber.text(index+1);
        }
    });
});