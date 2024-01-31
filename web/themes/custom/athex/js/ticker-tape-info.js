jQuery(document).ready(function ($) {
    $(".ticker-tape-symbol").each(function() {
      var newText = $(this).text().replace(".ATH", "");
      $(this).text(newText);
    });
});
