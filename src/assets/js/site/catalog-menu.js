$(document).ready(function(){
    if ($(window).width() < '651'){
        //меню каталога
        $('.title_katalog').click(function(){
            if ($('.katalog_kategory_menu').css('overflow') == 'hidden') {
                $('.katalog_kategory_menu').css({"height" : "auto", "overflow" : "visible", "-webkit-box-shadow" : "1px 1px 10px 3px rgba(100,100,100,0.25)", "box-shadow" : "1px 1px 10px 3px rgba(100,100,100,0.25)", "margin":"20px 0", "padding":"20px"});
                $('.katalog_kategory_akcii').css({"height" : "0px", "overflow" : "hidden", "-webkit-box-shadow" : "none", "box-shadow" : "none", "margin":"0"});
                $('.katalog_katgory_filter').css({"height" : "0px", "overflow" : "hidden", "-webkit-box-shadow" : "none", "box-shadow" : "none", "margin":"0"});
                return false;
            } else {
                $('.katalog_kategory_menu').css({"height" : "0px", "overflow" : "hidden", "-webkit-box-shadow" : "none", "box-shadow" : "none", "margin":"0", "padding":"0px"});
            }
        });
        if ($('.katalog_kategory_menu').css('overflow') == 'hidden') {
            $('body').click(function(e){
                var katalog = $('.katalog_kategory_menu');
                if (!katalog.is(e.target) && katalog.has(e.target).length === 0){
                    $('.katalog_kategory_menu').css({"height" : "0px", "overflow" : "hidden", "-webkit-box-shadow" : "none", "box-shadow" : "none", "margin":"0"});
                }
            });
        }


        //фильтр производителей
        $('.title_filter').click(function(){
            if ($('.katalog_katgory_filter').css('overflow') == 'hidden') {
                $('.katalog_katgory_filter').css({"height" : "auto", "overflow" : "visible", "-webkit-box-shadow" : "1px 1px 10px 3px rgba(100,100,100,0.25)", "box-shadow" : "1px 1px 10px 3px rgba(100,100,100,0.25)", "margin":"20px 0", "padding":"20px"});
                $('.katalog_kategory_akcii').css({"height" : "0px", "overflow" : "hidden", "-webkit-box-shadow" : "none", "box-shadow" : "none", "margin":"0"});
                $('.katalog_kategory_menu').css({"height" : "0px", "overflow" : "hidden", "-webkit-box-shadow" : "none", "box-shadow" : "none", "margin":"0"});
                return false;
            } else {
                $('.katalog_katgory_filter').css({"height" : "0px", "overflow" : "hidden", "-webkit-box-shadow" : "none", "box-shadow" : "none", "margin":"0", "padding":"0px"});
            }
        });
        if ($('.katalog_katgory_filter').css('overflow') == 'hidden') {
            $('body').click(function(e){
                var katalog = $('.katalog_katgory_filter');
                if (!katalog.is(e.target) && katalog.has(e.target).length === 0){
                    $('.katalog_katgory_filter').css({"height" : "0px", "overflow" : "hidden", "-webkit-box-shadow" : "none", "box-shadow" : "none", "margin":"0"});
                }
            });
        }


        //фильтр акций
        $('.title_akcii').click(function(){
            if ($('.katalog_kategory_akcii').css('overflow') == 'hidden') {
                $('.katalog_kategory_akcii').css({"height" : "auto", "overflow" : "visible", "-webkit-box-shadow" : "1px 1px 10px 3px rgba(100,100,100,0.25)", "box-shadow" : "1px 1px 10px 3px rgba(100,100,100,0.25)", "margin":"20px 0", "padding":"20px"});
                $('.katalog_katgory_filter').css({"height" : "0px", "overflow" : "hidden", "-webkit-box-shadow" : "none", "box-shadow" : "none", "margin":"0"});
                $('.katalog_kategory_menu').css({"height" : "0px", "overflow" : "hidden", "-webkit-box-shadow" : "none", "box-shadow" : "none", "margin":"0"});
                return false;
            } else {
                $('.katalog_kategory_akcii').css({"height" : "0px", "overflow" : "hidden", "-webkit-box-shadow" : "none", "box-shadow" : "none", "margin":"0", "padding":"0px"});
            }
        });
        if ($('.katalog_kategory_akcii').css('overflow') == 'hidden') {
            $('body').click(function(e){
                var katalog = $('.katalog_kategory_akcii');
                if (!katalog.is(e.target) && katalog.has(e.target).length === 0){
                    $('.katalog_kategory_akcii').css({"height" : "0px", "overflow" : "hidden", "-webkit-box-shadow" : "none", "box-shadow" : "none", "margin":"0"});
                }
            });
        }

    };




    $.easing.def = "easeInOutQuad";
    $('li.katalog_kategory_menu_li p').click(function(e){
        var dropDown = $(this).parent().next();
        $('.dropdown').not(dropDown).slideUp('slow');
        dropDown.slideToggle('slow');
        e.preventDefault();
    });





});
