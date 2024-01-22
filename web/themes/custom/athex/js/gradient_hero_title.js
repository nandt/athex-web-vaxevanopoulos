jQuery(document).ready(function ($) {
    $('.scrollToTop-btn').click(function () {
        $('html, body').animate({ scrollTop: 0 }, 800);
    })
})