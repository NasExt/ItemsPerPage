/**
 * ItemsPerPage
 * @copyright Copyright (c) 2013 Dusan Hudak
 */

(function ($, undefined) {

	$('select[data-items-per-page]').off('change');
	$('select[data-items-per-page]').on("change", function (e) {
		e.preventDefault();
		$(this).closest('form').submit();
	});

})(jQuery);