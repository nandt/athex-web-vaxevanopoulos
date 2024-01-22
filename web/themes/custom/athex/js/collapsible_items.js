jQuery(document).ready(function ($) {

    const itemsDesktop = $('.collapsible-container.desktop .collapsible-item.desktop');
    itemsDesktop.first().addClass('active');
    itemsDesktop.not(':first-child').addClass('inactive');

    itemsDesktop.each(function (index) {
        const itemNumber = index + 1;
        $(this).prepend($('<div class="number">' + '0' + itemNumber + '</div>'));
    });

    itemsDesktop.click(function () {
        const currentItem = $(this);
        if (!currentItem.hasClass('active')) {
            currentItem.addClass('active');
            currentItem.removeClass('inactive');
            currentItem.siblings().removeClass('active');
            currentItem.siblings().addClass('inactive');
        }
    });

    const itemsMobile = $('.collapsible-container.mobile .collapsible-item.mobile');
    $('.collapsible-container.mobile .collapsible-item.mobile:first').addClass('active');
    $('.collapsible-container.mobile .collapsible-item.mobile:not(:first)').addClass('inactive');
    itemsMobile.each(function (index) {
        const itemNumberMobile = index + 1;
        $(this).prepend($('<div class="numberMobile">' + '0' + itemNumberMobile + '</div>'));
    });

    itemsMobile.click(function () {
        const currentItemMobile = $(this);
        if (!currentItemMobile.hasClass('active')) {
            currentItemMobile.addClass('active');
            currentItemMobile.removeClass('inactive');
            currentItemMobile.siblings().removeClass('active');
            currentItemMobile.siblings().addClass('inactive');
        }
    });

})