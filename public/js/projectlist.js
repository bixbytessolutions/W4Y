$(function () {
    $minbudget= parseInt($("#filterproject").attr('min'));
    $maxbudget= parseInt($("#filterproject").attr('max'));
    $maxbudgetslide=( $maxbudget - ($maxbudget * 30/100));
  
    $('[data-toggle="tooltip"]').tooltip();
    //Modal Slider Range
    $( "#slider-range" ).slider({  
        range: true,
        min: 0,
        max: $maxbudget,
        values: [$minbudget, $maxbudgetslide],
        slide: function( event, ui ) {
        
            $(".minbudget").val(ui.values[ 0 ]);      
            $(".maxbudget").val(ui.values[ 1 ]);         
            $( "#leftRange" ).html(  ui.values[ 0 ] );
            $( "#rightRange" ).html(  ui.values[ 1 ] );
        }
    });

    $( "#leftRange" ).html(  $("#slider-range" ).slider( "values", 0 ) );
    $( "#rightRange" ).html(  $("#slider-range" ).slider( "values", 1 ) );
    $(".minbudget").val($("#slider-range" ).slider( "values", 0 ) );      
    $(".maxbudget").val($("#slider-range" ).slider( "values", 1 ));         

    var obj=$('#filter .projectWraper');
    $(obj).bind('change',function (event) {
        event.stopPropagation();

        if ($(this).hasClass('active')) {
            $(this).removeClass('active')
            return;
        }
        else {
            $(this).addClass('active');

        }
    });
});


$('.owl-carousel').owlCarousel({
    loop:true,
    margin:30,
    responsiveClass:true,
    navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa fa-angle-right'></i>"],
    autoplay:true,
    autoplayTimeout:5000,
    autoplayHoverPause:true,
    responsive:{
        0:{
            items:1,
            nav:true
        },
        600:{
            items:3,
            nav:true
        },
        1000:{
            items:3,
            nav:true,
            loop:true
        }
    }
});


$('.owl-nav').on('hover',function(){
    owl.trigger('stop.owl.autoplay');
});

$('.carousel').bcSwipe({ threshold: 50 });

