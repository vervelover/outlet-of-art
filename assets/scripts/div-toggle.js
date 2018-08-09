jQuery(document).ready(function($){
    $(".option-content").hide();
    $(".arrow-up").hide();
    $(".option-heading").click(function(){
            $(this).next(".option-content").slideToggle(500);
	    $(this).next(".option-content-first").slideToggle(500);
            $(this).find(".arrow-up, .arrow-down").toggle();
    });
    $("#mobile-products-filter .option-content a").click(function(){
	    $(".option-content").hide();
            $(".option-heading").find(".arrow-up, .arrow-down").toggle();
    });
    $(".option-close").click(function(){
            $(".option-content").hide();
            $(".option-heading").find(".arrow-up, .arrow-down").toggle();
    });
});
