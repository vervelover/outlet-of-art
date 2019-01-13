function setCookie(name,value,days) {
	var expires;
	var date;
    if (days) {
        date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        expires = "; expires="+date.toGMTString();
    }
    else expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
}

jQuery(document).ready(function($){

	if (!expireData.cookieSet) {
		setTimeout(function(){
			$('#popup-newsletter, #popup-newsletter .popup__content').addClass('showpopup--newsletter');
		}, 3000);
	}
	
	$('.show-newsletter').on('click', function() {
		$('#popup-newsletter, #popup-newsletter .popup__content').addClass('showpopup--newsletter');
	});
	$('.popup__close--newsletter').on('click', function() {
		$('#popup-newsletter, .popup__content').removeClass('showpopup--newsletter');
		if (!expireData.cookieSet) {
			setCookie('nl_cookie','1',30 * 12);
		}
	});

	//Hide popup when clicking outside of it
	$('#popup-newsletter').click(function(event) { 
		if(!$(event.target).closest('.popup__content').length) {
        	if($('.popup__content').hasClass('showpopup--newsletter')) {
            	$('#popup-newsletter, .popup__content').removeClass('showpopup--newsletter');
            	if (!expireData.cookieSet) {
					setCookie('nl_cookie','1',30 * 12);
				}
        	}
    	}   
	});
});