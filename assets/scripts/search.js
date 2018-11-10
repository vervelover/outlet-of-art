jQuery(document).ready(function($){

	$('.search-results-filter__all-filter').on("click", function() {
		$(this).addClass('active');
		$('.search-results-filter__artist-filter').removeClass('active');
		$('.search-results-filter__artwork-filter').removeClass('active');
		$('article').show();
		$('.type-artist').show();
	});
	$('.search-results-filter__artist-filter').on("click", function() {
		$(this).addClass('active');
		$('.search-results-filter__all-filter').removeClass('active');
		$('.search-results-filter__artwork-filter').removeClass('active');
		$('article').hide();
		$('.type-artist').show();
	});
	$('.search-results-filter__artwork-filter').on("click", function() {
		$(this).addClass('active');
		$('.search-results-filter__artist-filter').removeClass('active');
		$('.search-results-filter__all-filter').removeClass('active');
		$('article').hide();
		$('.type-product').show();
	});

});