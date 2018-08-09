jQuery(document).ready(function($){

    /**
     * Set page header margin top equal to header
     */
    function setHeaderHeight() {
        var headerHeight = $(".site-header").height();
        $(".site-inner").css("margin-top", headerHeight);
    }
    setHeaderHeight();

    /**
     * Set page header margin top equal to header on window resize and orientation change
     */
    var resize_timeout;

    $(window).on('resize orientationchange', function(){
        clearTimeout(resize_timeout);

        resize_timeout = setTimeout(function(){
            $(window).trigger('resized');
        }, 250);
    });

    $(window).on('resized', function(){
        setHeaderHeight();
    });


});
