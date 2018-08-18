jQuery(document).ready(function($){
    // if menu is visible use this, else use div-toggle.js
    $('.artist-menu__list-item--link').click(function () {
        var clicked = $(this);
        $('.artist-menu__list-item--link').each(function(){
            var menu = $(this);
            if (!menu.is(clicked))
            {
                $(menu.attr('data-item')).hide();
            }
        });
        $(clicked.attr('data-item')).fadeToggle();
    });
    $(".option-content").hide();
    $(".arrow-up").hide();
    $(".option-heading").click(function(){
        $(this).next(".option-content").find('.option-content__content').css('display', 'block');
        $(this).next(".option-content").slideToggle(650);
        $(this).next(".option-content-first").slideToggle(650);
        $(this).find(".arrow-up, .arrow-down").toggle();
    });
    $(".option-close").click(function(){
        $(".option-content").hide();
        $(".option-heading").find(".arrow-up, .arrow-down").toggle();
    });
});
