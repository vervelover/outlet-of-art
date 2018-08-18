jQuery(document).ready(function($){
    $(".option-content").hide();
    $(".arrow-up").hide();
    $(".option-heading").click(function(){
        $(this).next(".option-content").slideToggle(650);
	    $(this).next(".option-content-first").slideToggle(650);
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
