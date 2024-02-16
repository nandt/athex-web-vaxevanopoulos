jQuery(document).ready(function ($) {
    const bullets = $('.bullet');
    const verticalBars = $('.vertical-bar');
    const sections = $('.section');
    let lastScrollTop = 0; // Καταγράφει την τελευταία θέση κύλισης
    let activeIndices = new Set(); // Καταγράφει τους ενεργούς δείκτες

    setVerticalBarHeights();

    $(window).on('resize', setVerticalBarHeights);

    $(document).on('scroll', function () {
        let currentSectionIndex = -1;
        const st = $(this).scrollTop();

        // Εύρεση της ενότητας που βρίσκεται στο κέντρο της οθόνης
        sections.each(function (index) {
            const rect = $(this).get(0).getBoundingClientRect();
            if (rect.top <= window.innerHeight / 2 && rect.bottom >= window.innerHeight / 2) {
                currentSectionIndex = index;
            }
        });

        if (st > lastScrollTop) {
            // Scroll προς τα κάτω
            if (currentSectionIndex !== -1) activeIndices.add(currentSectionIndex);
        } else {
            // Scroll προς τα πάνω
            if (currentSectionIndex !== -1 && activeIndices.has(currentSectionIndex)) activeIndices.delete(currentSectionIndex);
        }
        lastScrollTop = st <= 0 ? 0 : st;

        updateActiveElements();
    });

    function updateActiveElements() {
        bullets.each(function (index) {
            $(this).toggleClass('active', activeIndices.has(index));
        });

        verticalBars.each(function (index) {
            $(this).toggleClass('active', activeIndices.has(index));
        });
    }

    function setVerticalBarHeights() {
        verticalBars.each(function (i) {
            const sectionHeight = sections.eq(i).outerHeight(true); // Ίσως χρειαστεί προσαρμογή
            $(this).css('height', sectionHeight + 'px');
        });
    }
});
