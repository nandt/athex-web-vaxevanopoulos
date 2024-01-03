jQuery(document).ready(function ($) {

    var sliderHero;


    sliderHero = tns({
        container: '.wrapperHero',
        loop: false,
        items: 1,
        nav: false,
        autoplay: false,
        speed: 400,
        autoplayButtonOutput: false,
        mouseDrag: true,
        preventScrollOnTouch: 'auto',
        lazyload: true
    });

    var itemsArray = $(".hbiCategory").toArray();
    var $itemsArrayDiv = $("<div>").attr("class", "linkDiv");

    $.each(itemsArray, function (index, item) {
        $itemsArrayDiv.append(item);
    });

    $itemsArrayDiv.find("div:first").addClass("clicked");

    $('.wrapperHero').after($itemsArrayDiv);

    var autoClickInterval;

    function autoClick() {
        $('.linkDiv .hbiCategory').each(function (index) {
            setTimeout(function () {
                if (autoClickInterval) {
                    $('.linkDiv .hbiCategory').removeClass('clicked');
                    $(this).addClass('clicked');
                    sliderHero.goTo(index);
                }
            }.bind(this), index * 3000);
        });
    }

    autoClickInterval = setInterval(autoClick, 3000);


    $itemsArrayDiv.on('click', 'div', function () {
        $itemsArrayDiv.find("div").removeClass("clicked");
        $(this).addClass("clicked");
        var clickedIndex = $(this).index();
        sliderHero.goTo(clickedIndex);
        clearInterval(autoClickInterval);
        autoClickInterval = null;
    });

})