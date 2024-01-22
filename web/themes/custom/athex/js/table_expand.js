jQuery(document).ready(function ($) {


    $(document).on('click', '.view-table .arrow-wrapper', function () {
        $(this).children().toggleClass('arrow-expanded')
        let currRow = $(this).closest('tr');

        currRow.toggleClass('expanded-row');
        
        currRow.siblings('.childRow').remove();

        let hiddenTd = currRow.find("td.mobile-hidden");

        if (hiddenTd.length > 0) { 
            let childRow = $("<tr class='childRow'></tr>");
            let childTd =$(`<td colspan='5'></td>`)

            hiddenTd.each(function () { 
                let columnIndex = $(this).index();
                let correspondingTH = currRow.closest("table").find("th").eq(columnIndex);

                let childDiv = $("<div class='childDiv'></div>")
                
                let newTH = $("<span class='expanded-th textColorPurple7 body2'></span>").text(correspondingTH.text());
                let newTD = $("<span class='expanded-td textColorPurple2 body2'></span>").text($(this).text());
                
                childDiv.append(newTH);
                childDiv.append(newTD);

                childTd.append(childDiv);

                childRow.append(childTd);
            })

            currRow.after(childRow);   
        }
        
    })
})
