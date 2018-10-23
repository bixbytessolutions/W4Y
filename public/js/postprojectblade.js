$('.carousel').carousel({
    interval: false
});


$(document).ready(function () {
    
    $("#post_project").modal();
    $('#successmsg').hide();
    $('#failure').hide();
    $('#project_Carousel').on('slid.bs.carousel', function () {
        var currentSlide = $('div.active').index() + 1;
        var total = $('.postProjectPopup .item').length;
      
        if (currentSlide == total) {

            $('.customsubmit ').show();
            $('.right').hide();

        }
        else {

            $('.customsubmit').hide();
            $('.right').show();

        }

    });

    $('input,textarea').focus(function () {
        _palceholder = $(this).attr('placeholder');
        $(this).attr('placeholder', '')
      }).blur(function () {
        $(this).attr('placeholder', _palceholder)
    })

    $("#date").datepicker({

        dateFormat: "dd.mm.yy",
        minDate:0,
        onChangeMonthYear: function (year, month, element) {
        },
        onClose: function () {
            fnAttachColor(3);
        }

    });
  
    $('.box').bind('change',function (event) {
        event.stopPropagation();
        if ($(this).hasClass('boxactive')) {
            $(this).removeClass('boxactive')
            return;
        }
        else {
            $(this).addClass('boxactive');

        }
    });
});

function fnAttachColor(type) {
    if (type == "1") {

        $("#lblcolor").addClass("attachcolor");
        // $("#attach").addClass("attachcolor");
    }
    else if (type == "2") {
        var disp = $("#ui-datepicker-div").css("display");
        if (disp == "none") {
            $("#lblcolor").removeClass("attachcolor");
        }
    }
    else {
        $("#lblcolor").removeClass("attachcolor");

    }

    return false;
}

$('body').on('hidden.bs.modal', '.modal', function () {
    close();

});

function close(){
    $('.w4ycustom input, .w4ycustom textarea').val("");
    var boxs=$('.box');
    if (boxs.hasClass('boxactive')) {
    boxs.removeClass('boxactive');

    }

    $(".modal").on('show.bs.modal', function () {
        var firstItem = $(this).find(".item:first");
        if ( !firstItem.hasClass("active") ) {
        $(this).find(".active").removeClass("active");
        firstItem.addClass("active");
        }

        $('.w4yindicator li:first').addClass('active');

    });
}


$("#postsubmit").click(function(e){
 
    e.preventDefault();
    if($('#input-38').val() != "")
    {
    var projtitle = $("#input-38").val();
    }

    if($('#input-39').text() != "")
    {
    var projdescription = $("#input-39").text();
    }

    if($('#postuserid').val() != "")
    {
    var postuserid = $("#postuserid").val();
    }

    var myrequirement = new Array();
            $("input:checked").each(function() {
                myrequirement.push($(this).val());
            });

    if($('#input-40').val() != "")
    {
    var budjetmax = $("#input-40").val();
    }

    if($('#date').val() != "")
    {
    var projdate = $("#date").val();
    }
    

    $.ajax({
    type:'POST',
    url: routeUrl+'projects/store',

    data:{title:projtitle,description:projdescription,requirements:myrequirement,budget:budjetmax,deadline_date:projdate,owner_id:postuserid},
    beforeSend:  function() {    
    $('#postsubmit').css({'pointer-events':'none' , 'opacity':'0.5' });
            },
    
    success:function(responce){

    $('#successmsg').show();
    $('#successmsg').html(responce);
    $('#postsubmit').css({'pointer-events':'all' , 'opacity':'1' });
    $('#failure').hide();
    $('#postprojectform')[0].reset();
    $('#input-39').text("");
    $("input:checked").each(function() {
    $(".box").removeClass('boxactive');
    $(this).click();
    boxactive
    });

    },
    error:function(response){
        $('#successmsg').hide();
        let error = jQuery.parseJSON(response.responseText);
        error=error.errors;
    
        let errorText="<ul>";
        if(error.hasOwnProperty('title')){
            if(error.title.length > 0){
                errorText+="<li>"+error.title[0]+"</li>";
            }
        }
        if(error.hasOwnProperty('budget')){

            if(error.budget.length > 0){
                errorText+="<li>"+error.budget[0]+"</li>";
            }
        }

        if(error.hasOwnProperty('deadline_date')){
            if(error.deadline_date.length > 0){
                errorText+="<li>"+error.deadline_date[0]+"</li>";
            }
        }
        if(error.hasOwnProperty('description')){
            if(error.description.length > 0){
                errorText+="<li>"+error.description[0]+"</li>";
            }
        }
        if(error.hasOwnProperty('requirements')){
            if(error.requirements.length > 0){
                errorText+="<li>"+error.requirements[0]+"</li>";
            }
        }
    
        errorText+="</ul>";
        $('#failure').show();

        $('#failure').html(errorText);
        $('#postsubmit').css({'pointer-events':'all' , 'opacity':'1' });
    
        }
    
    });

 });