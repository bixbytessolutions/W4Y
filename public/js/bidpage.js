$(document).ready(function() {

    $(document).on("click",".read-moreproftext",function(){
        $(this).siblings(".more-text").slideDown();
        $(this).parents('.show-read-more').append('<b class="read-less"> Less</b>');
        $(this).hide();
        console.log( $(this).parents('.show-read-more'));
        
    });

    $(document).on("click",".read-less",function(){
        $(this).siblings(".more-text").slideUp();
        $(this).parents('.show-read-more').append('<b class="read-moreproftext">... More</b>');
        $(this).hide();
        console.log( $(this).parents('.show-read-more'));
       
    });

    $(".show-read-more").each(function(){
        var myStr = $(this).text();
        if($.trim(myStr).length > maxLength){
            var newStr = myStr.substring(0, maxLength);
            var removedStr = myStr.substring(maxLength, $.trim(myStr).length);
            $(this).empty().html(newStr);
            $(this).append('<b class="read-moreproftext">... More</b> ');
            $(this).append('<span class="more-text">' + removedStr + '</span>');
        }
    });

    $("[data-toggle='tooltip']").tooltip();
        
    $('#datetimepicker').datepicker({
        dateFormat: "dd.mm.yy",
        minDate:0,
    });
        
    
    $(".float button").click(function(){
        $('.overlay').fadeIn(100).delay(400);
        $(".rightdescription form").addClass("animated fadeInDown  minsize");
    });

    $(".customclosew4y").click(function(){
        $('.overlay').fadeOut(100).delay(400);
        $(".rightdescription form").removeClass("animated fadeInUp minsize");
    });
    
        
    $('input,textarea').focus(function() {
        _palceholder=$(this).attr('placeholder');
        $(this).attr('placeholder', '')
    }).blur(function() {
        $(this).attr('placeholder', _palceholder)
    })
            
    
    $(window).scroll(function(){

        if( /Android|webOS|iPhone|iPad|iPod|Opera Mini/i.test(navigator.userAgent) ) 
            var aTop = 1595;
        else
            aTop = 805;
        
        if($(this).scrollTop()>=aTop){
            if( /Android|webOS|iPhone|iPad|iPod|Opera Mini/i.test(navigator.userAgent) ) 
                $('.float').show();
            
            
        }else{
            
            if( /Android|webOS|iPhone|iPad|iPod|Opera Mini/i.test(navigator.userAgent) ) 
                $('.float').hide();
        }

    
    });
    
    
    });
    
    