// user menu
jQuery(function($){
    $('.user-menu-link').on('click', function(){
        $(this).toggleClass('active');
        $('.user-menu').slideToggle(300);
    });
});
// end user menu

// main menu
jQuery(function($){
    $('.main-menu-link').on('click', function(){
        $(this).toggleClass('active');
        $('.main-menu').slideToggle(300).toggleClass('clear');
    });
});
// end main menu

// different select

jQuery(function($){
    
    if($("select.different").val() != undefined){
        var selectBox = $("select.different").selectBoxIt({
            autoWidth: false
        });
    }
});

// end different select

// popup
jQuery(function($){
    var OpenPopupLink = $('a.open-popup-link');
    var ClosePopupLink = $('a.close-popup-link');
    var PopupWrapper = $('.popup-wrapper');
    OpenPopupLink.click(function() {
        var clickId = this.id;
        $('#popup-' + clickId).fadeIn(300);
        PopupWrapper.fadeIn(300);
        $('body').css('overflow','hidden').css('height','100%');
    });
    ClosePopupLink.click(function() {
        $(this).parents('.popup').fadeOut(300);
        PopupWrapper.fadeOut(300);
        $('body').css('overflow','auto').css('height','100%');
    });

    $(document).keydown(function(eventObject) {
        if ($('[id^="popup-"]').is(":visible")){
            if (eventObject.which == '27') {
                $('[id^="popup-"]').fadeOut(300);
                PopupWrapper.fadeOut(300);
                $('body').css('overflow','auto').css('height','100%');
            }
        }
    });
  
/*
    $(document).mouseup(function (e) {
        var container = $('[id^="popup-"]');
        if (container.has(e.target).length === 0){
            container.fadeOut(300);
            PopupWrapper.fadeOut(300);
            $('body').css('overflow','auto').css('height','100%');
        }
    });
*/
});
// end popup

// jquery extend function
$.extend({
    redirectPost: function(location, args){
        var form = '';
        $.each( args, function( key, value ) {
            value = String(value);
            value = value.split('"').join('\"')
            form += '<input type="hidden" name="'+key+'" value="'+value+'">';
        });
        $('<form action="' + location + '" method="POST">' + form + '</form>').appendTo($(document.body)).submit();
    }
});




// menu link
$(document).ready(function() {
    $('.menu-link a').click(function(){
        $('.menu-section nav').slideToggle(300);
    })
});

//card link
jQuery(function($){
    $('.card-edit-link').on('click', function(){
        $('.tariff-section_card-info_data').fadeOut(300);
        $('.tariff-section_card-info_edit').fadeIn(300);
    });
});
//end card link

// owl slider
$(document).ready(function() {
/*    
    $('.carousel').owlCarousel({
        items: 1,
        loop: true,
        mouseDrag: false,
        animateIn: 'fadeIn',
        autoplay: false,
        autoplayTimeout: 1000,
        nav: false,
        dots: true
    });
*/
});