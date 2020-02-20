$(document).ready(function() {
	var hidden_categ_arr;

	$('body').on('click', '.show_more_category', function(e) {
		e.preventDefault();
		var this_ = this;
		if(typeof hidden_categ_arr == 'undefined' || hidden_categ_arr == '') {
			hidden_categ_arr = $(this).closest('.top_block_filter_section').find('.hidden_category');
		}

		hidden_categ_arr.each(function(index, el) {
			$(el).toggleClass('hidden_category');
			if($(el).hasClass('hidden_category')){
				$(this_).text('Показать еще');
			} else {
				$(this_).text('Скрыть');
			}
		});
	});
});