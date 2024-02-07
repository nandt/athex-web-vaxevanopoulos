jQuery(document).ready(function ($) {

    $('.nav-tabs .nav-link').on('click', function(){
        $('.nav-tabs .nav-link').removeClass('active');
        $(this).addClass('active');
    });

    $('.nav-pills .nav-link').on('click', function(){
        $('.nav-pills .nav-link').removeClass('active');
        $(this).addClass('active');
    });
});
