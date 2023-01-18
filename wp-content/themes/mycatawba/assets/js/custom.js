jQuery(document).ready(function($){
    if(!$('#wpadminbar').is(':visible')){
        $('html').css({"margin": "0 !important"});
    }
    $('.badges-slider').slick({
        slidesToShow: 3,
        infinite: false,
        dots: false,
        prevArrow: $('.slick-prev'),
        nextArrow: $('.slick-next'),
        responsive: [
        {
          breakpoint: 991,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 2            
          }
        }
            ,{
          breakpoint: 600,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1            
          }
        }
        ]
    });
    function headerScroll(){
        if($(window).scrollTop() > 180){
            $('#masthead').addClass('scrolled');
        }
        else{
            $('#masthead').removeClass('scrolled');
        }
    }
    $(window).scroll(function(){
        headerScroll();
    });
    headerScroll();
    
    function minHeight(){
        var headerHeight = $('#masthead').outerHeight();
        var footerHeight = $('#main-footer').outerHeight();
        var bodyHeight = $(window).height() - headerHeight - footerHeight;
        $('#masthead+div, #masthead+section').css({"min-height": bodyHeight});
    }
    minHeight();
    
    $(window).resize(function(){
        minHeight();
    });    
    $('.reg-next-btn').click(function(e){
        e.preventDefault();
        var $this = $(this);
        var activeSection = $this.parents().find('.active');       
        var fields = $(activeSection).find('input:required:not([type="hidden"]), select:required:not([type="hidden"]), textarea:required:not([type="hidden"])');
        var flag = false;
        $(fields).each(function(){            
            if(($(this).val().length != 0)){
                flag = true;
            }
            if(($(this).val().length == 0)){                
                $('.user-reg-form .message-error').fadeIn(300);
                setTimeout(function(){
                    $('.user-reg-form .message-error').fadeOut(300);
                }, 1500);
                flag = false;
                return false;
            }
        });
        if(flag == true){
            $(activeSection).next('.form-tab').addClass('active');
            $(activeSection).removeClass('active');

            if($('.reg-form-3').hasClass('active')){
               $('div[class*="reg-form"]').addClass('active');
               $('.reg-next').hide();
            }
        }
        if($('.reg-form-2').hasClass('active')){
            $('.form-progress li').removeClass('active');
            $('.form-progress li:nth-child(2)').addClass('active');
        }
        if($('.reg-form-3').hasClass('active')){
            $('.form-progress li').removeClass('active');
            $('.form-progress li:nth-child(3)').addClass('active');
        }
    });
});