jQuery(document).ready(function ($) {

    $(".is_ValuePercentage, .is_sValue, .indices_overview_container .col-12 > div > div > div:nth-child(2) table td:nth-child(5), .indices_overview_container .col-12 > div > div > div:nth-child(2) table td:nth-child(4)").each(function () {
        var currentText = $(this).text();

        if (currentText.indexOf("-") === -1) {
            $(this).text("+" + currentText);
            $(this).toggleClass('pos-change');
        }
        else {
            $(this).toggleClass('neg-change');
        }
    })

    $('.nav-tabs .nav-link').on('click', function(){
        $('.nav-tabs .nav-link').removeClass('active');
        $(this).addClass('active');
    });

    $('.nav-pills .nav-link').on('click', function(){
        $('.nav-pills .nav-link').removeClass('active');
        $(this).addClass('active');
    });
});
