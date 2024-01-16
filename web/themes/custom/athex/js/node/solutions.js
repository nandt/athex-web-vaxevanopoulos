jQuery(document).ready(function ($) {
    const bullets = $('.bullet');
    const verticalBars = $('.vertical-bar');
    const sections = $('.section');
    const lastVerticalBar = $('.vertical-bar.last');

    setVerticalBarHeights();

    $(window).on('resize', setVerticalBarHeights);

    $(document).on('scroll', function () {
        let currentSectionIndex = 0;

        sections.each(function (index) {
            const rect = $(this).get(0).getBoundingClientRect();

            if (rect.top <= window.innerHeight / 2 && rect.bottom >= window.innerHeight / 2) {
                currentSectionIndex = index;
            }
        });

        setActive(currentSectionIndex);
    });

    function scrollToSection(index) {
        sections.eq(index).get(0).scrollIntoView({ behavior: 'smooth' });
    }

    bullets.on('click', function () {
        const index = $(this).data('index');
        scrollToSection(index);
    });

    function setActive(index) {
        bullets.each(function (i) {
            $(this).toggleClass('active', i === index);
        });

        verticalBars.each(function (i) {
            $(this).toggleClass('active', i === index);
        });
        lastVerticalBar.css('opacity', index === sections.length - 1 ? 0 : 1);
    }

    function setVerticalBarHeights() {
        verticalBars.each(function (i) {
            const sectionHeight = sections.eq(i).outerHeight();
            $(this).css('height', sectionHeight - 38);
        });
    }

    function getAndDisplayMarginRight() {
        var marginRight = $('.container').css('margin-right');
        var negativeMarginRight = '-' + marginRight;
        $('.logo-wrapper').css('margin-right', negativeMarginRight);
      }
    
      // On document ready
      $(document).ready(function() {
        getAndDisplayMarginRight(); // Initial call on page load
    
        // On window resize
        $(window).resize(function() {
          getAndDisplayMarginRight();
        });
    
        // On orientation change
        $(window).on('orientationchange', function() {
          getAndDisplayMarginRight();
        });
      });
});