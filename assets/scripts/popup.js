jQuery(document).ready(function($){
	
	$('.home__title').on('click', function() {
		$('#popup-newsletter, .popup__content').addClass('showpopup');
	});
	$('.popup__close').on('click', function() {
		$('#popup-newsletter, .popup__content').removeClass('showpopup');
	});

	//Hide popup when clicking outside of it
	$('#popup-newsletter').click(function(event) { 
		if(!$(event.target).closest('.popup__content').length) {
        	if($('.popup__content').hasClass('showpopup')) {
            	$('#popup-newsletter, .popup__content').removeClass('showpopup');
        	}
    	}   
	});
});