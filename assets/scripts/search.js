jQuery(document).ready(function($){

	$('.search-results-filter__artist-filter').on("click", function() {
		$('article').hide();
		$('.type-artist').show();
	});
	$('.search-results-filter__artwork-filter').on("click", function() {
		$('article').hide();
		$('.type-product').show();
	});

});