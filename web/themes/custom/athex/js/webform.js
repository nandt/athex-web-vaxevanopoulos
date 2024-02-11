jQuery(document).ready(function ($) {

	$('#edit-country').addClass('filled');

    $(document).on('focus focusout blur change', 'input, select, .select2', function () {
        if ($(this).val() && $(this).val() != '_none') {
            $(this).addClass('filled');
        }
        else {
            $(this).removeClass('filled');
        }
    });

});
