
let productsTable = $('.products-table');
let tableTr = productsTable.find('tr');

$(window).on("resize", function () {
    if ($(window).width() < 1200) {
        tableTr.each(function () {
            if (!$(this).next().hasClass('foldedTable')) {
                let foldedTable = $("<table class='foldedTable'></table>");
                let foldedTd = $(this).find('td:not(:first-child):not(.mobile-hidden):not(.arrow)');
                foldedTd.toggleClass('folded');

                foldedTd.each(function () {
                    let foldedDiv = $("<div class='foldedDiv'></div>");
                    let newTD = $("<span class='expanded-td textColorPurple2 body2'></span>").text($(this).text());
                    foldedDiv.append(newTD);
                    foldedTable.append($(`<td></td>`).append(foldedDiv));
                });

                $(this).after(foldedTable);
            }
        });
    } else {
        if (productsTable.find('.foldedTable').length > 0) {
            tableTr.find('.foldedTable').remove();
        }
    }
});
