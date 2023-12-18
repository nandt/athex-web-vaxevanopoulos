jQuery(document).ready(function ($) {
    $(".ticker-tape-symbol").each(function() {
      var newText = $(this).text().replace(".ATH", "");
      $(this).text(newText);
    });

	$(".ticker-tape-change").each(function() {
		var currentText = $(this).text();

		if (currentText.indexOf("-") === -1) {
			$(this).text("+" + currentText);
			$(this).toggleClass('pos-change');
		}
		else {
			$(this).toggleClass('neg-change');
		}
	})
});
