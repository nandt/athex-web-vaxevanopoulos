jQuery(document).ready(function ($) {


    const itemsDesktop = $('.collapsible-container.desktop .collapsible-item.desktop');
    itemsDesktop.first().addClass('active');
    itemsDesktop.not(':first-child').addClass('inactive');

    itemsDesktop.each(function (index) {
        let label;
        if ($(this).closest('.collapsible-container').hasClass('esg')) {
            const letters = ['E', 'S', 'G'];
            label = letters[index % letters.length]; // Επιλέγει το γράμμα ανάλογα με τον δείκτη
        } else {
            const itemNumber = index + 1;
            label = '0' + itemNumber;
        }
        $(this).prepend($('<div class="number">' + label + '</div>'));
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
    itemsMobile.first().addClass('active');
    itemsMobile.not(':first-child').addClass('inactive');

    itemsMobile.each(function (index) {
        let label;
        if ($(this).closest('.collapsible-container').hasClass('esg')) {
            const letters = ['E', 'S', 'G'];
            label = letters[index % letters.length];
        } else {
            const itemNumberMobile = index + 1;
            label = '0' + itemNumberMobile;
        }
        $(this).prepend($('<div class="numberMobile">' + label + '</div>'));
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

});


document.addEventListener('DOMContentLoaded', function() {
    const marqueeText = document.getElementById('marquee-text');
    const marqueeContainer = document.querySelector('.marquee-container');

    if (!marqueeText || marqueeText.textContent.trim() === '') {
        marqueeContainer.style.display = 'none';
    } else {
        animateMarquee();
    }
});

function animateMarquee() {
    const marqueeText = document.getElementById('marquee-text');
    let marqueeWidth = marqueeText.offsetWidth;
    let containerWidth = marqueeText.parentElement.offsetWidth;
    let start = containerWidth;
    let end = -marqueeWidth;

    function step() {
        if (start <= end) {
            start = containerWidth;
        } else {
            start -= 1;
        }
        marqueeText.style.transform = `translateX(${start}px)`;
        window.requestAnimationFrame(step);
    }

    window.requestAnimationFrame(step);
}
