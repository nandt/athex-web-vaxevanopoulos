jQuery(document).ready(function ($) {

    $(document).on('click', '.burger-btn', function () {
        var $burgerIcon = $(this).find('.burger-icon');
        var $xIcon = $(this).find('.x-icon');
        var $searchIcon = $('.search-icon');
        var $titleMenuMob = $('.titleMenuMob');

        if ($burgerIcon.length) {

            $burgerIcon.removeClass('burger-icon').addClass('x-icon');
            $searchIcon.hide();
            $titleMenuMob.show();
        } else if ($xIcon.length) {

            $xIcon.removeClass('x-icon').addClass('burger-icon');
            $searchIcon.show();
            $titleMenuMob.hide();
        }

        $(this).parents('.layout-container').toggleClass('expanded');
        $(this).parent().siblings('.burger-mobile').toggleClass('mobile-menu-main-expanded');
        $(this).parents('.main-menu').siblings().children().toggleClass('mobile-menu-main-expanded');
    });

    $(document).on('click', '.search-icon', function (e) {
        e.preventDefault();
        $(this).siblings('div').toggleClass('hidden');
    });

});
