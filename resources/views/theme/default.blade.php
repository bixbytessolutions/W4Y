<!DOCTYPE html>
<html lang="en">
<head>
<title> W4Y | @yield('title', 'Welcome')</title>
    <meta charset="utf-8">
  
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="images/logo/favicon.png">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,300i,400,400i,500,500i,600,600i,700" rel="stylesheet">
    <link href="https:/cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet" href="{!! asset('css/jquery.dataTables.min.css') !!}">
    <link rel="stylesheet" href="{!! asset('css/bootstrap.min.css') !!}">
    <link rel="stylesheet" href="{!! asset('css/jquery-ui.css') !!}">
    <link rel="stylesheet" href="{!! asset('css/font-awesome.min.css') !!}"> 
    <link rel="stylesheet" href="{!! asset('css/hover.css') !!}">
    <link rel="stylesheet" href="{!! asset('css/animate.css') !!}">
    <link rel="stylesheet" href="{!! asset('css/style.css') !!}">
    <link rel="stylesheet" href="{!! asset('css/custom-inner.css') !!}">
    <link rel="stylesheet" href="{!! asset('css/profile-page.css') !!}">
    <link rel="stylesheet" href="{!! asset('css/custom.css') !!}">
    <script src="{!! asset('js/jquery.min.js') !!}"></script>
    <script src="{!! asset('js/owl.carousel.js') !!}"></script>
    <script src="{!! asset('js/jquery-ui.js') !!}"></script>
    <script src="{!! asset('js/moment.js') !!}"></script>
    <script src="{!! asset('js/bootstrap.min.js') !!}"></script>
    <script src="{!! asset('js/jquery.dataTables.min.js') !!}"></script>  
</head>
<body >
    <section class="header"> 
    @include('theme.header')
    </section>

    @yield('content')

    <section class="footer">
    @include('theme.footer')
    </section>
    
    <script>
    var  routeUrl = '/';
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
    });

    $("#clpop").click(function(e){
        $.ajax({
            url: '{{ url("/projects/create") }}',
            type: "get",
            success: function (responce) {

                $('#placeholder-element').html(responce.html);
            
            }
        });
    });

    $(".fetchmsglist").click(function(e){
        $.ajax({
        url: '{{ url("/chat/menulist-messages") }}',
        type: "get",
        success: function (response) {
        var msgblock="";
    
        response.forEach(function(data) {
            //online and offline 
            if(data.last_activity===null)
            sesststus="gray";

            var theDate = new Date(data.last_activity * 1000);
            
            start = theDate;

            var end = new Date();
            var timeDiffactivity = Math.abs(end.getTime() - start.getTime());
            if(parseFloat(timeDiffactivity) <= 120000)
                sesststus="green";
            else
            sesststus="gray";
        //last name
            if(data.last_name!=null) {
                var  lastname= data.last_name
            }
            else{
                lastname="";
            }

    
            var msgtime="";
            var date1 = new Date(data.ChatDate);
            var date2 = new Date();
            
            var timeDiff = Math.abs(date2.getTime() - date1.getTime());
            var diffDays = Math.floor(timeDiff / (1000 * 3600 * 24)); 
            if(diffDays < 1){
                value = data.ChatDate.split(" ");
                time=value[1];
                time = time.split(":");
                if(parseInt(date2.getMinutes()) == parseInt(time[1])){
                    msgtime= "Just Now";
                }else{
                    $time = Math.floor((timeDiff/1000)/60);
                    if($time < 60){
                        msgtime= $time+" Minutes ago";
                    }else{
                        msgtime = Math.floor($time/60)+" Hours ago";
                    }
                }
            }else if(diffDays = 1)
            msgtime= diffDays+ " Day ago";
            else if(diffDays > 1)
            msgtime= diffDays+ " Days ago";
        
        
        var chaturl='{{url("/chat/init-message?to_user_id=") }}'+ data.fromid ;

            if(data.message!="staticspy") {
                msgblock +='<a href="'+ chaturl+'" ><li class="msg-sec"><ul class="dropdown-menu-msglist" ><li class="msg" ><div class="col-xs-3">';
                msgblock+='<img src="{!! asset("uploads/avatar/'+data.photo+'") !!}" id="UserImg" alt="" class="img-circle round_topImg" height="50" width="50"></div>';
                msgblock+='<div class="col-xs-7 msgdescrp">';
                msgblock+='<span class="mainusername">'+ data.first_name+' '+ lastname +'</span>'; 
                msgblock+='<p class="whitespaceellips">'+data.message+'</p>';
                msgblock+=' <span>'+msgtime+'</span></div>';
                msgblock+='<div class="col-xs-2 '+sesststus+'"> <i class="fa fa-circle" aria-hidden="true"></i> </div>';
                msgblock+='</li> </ul></li> </a>';
            }

            });

        if(response.status == 401){
            
                msgblock +='<li class="msg-sec"><ul class="dropdown-menu-msglist" ><li class="msg" ><div class="col-xs-3">';
                msgblock+='<img src="{!! asset("/images/default.png") !!}" id="UserImg" alt="" class="img-circle round_topImg" height="50" width="50"></div>';
                msgblock+='<div class="col-xs-7 msgdescrp">';
                msgblock+='<span class="mainusername">'+ response.firstname+ ' '+response.lastname+ '</span>'; 
                msgblock+='<p class="whitespaceellips"> You don’t have any messages </p></div>';
                msgblock+='</li> </ul></li>';

            }

            $('.msgblocklist').html(msgblock);
    
        },
        error:function(response){
        if(response.status == 401){
            let msgdata=jQuery.parseJSON(response.responseText);
            
            if(msgdata.lastname!=null)
            {
                var userlastname=msgdata.lastname;
            }
            else{
                var userlastname="";
            }
            var nomsgblock = "";
                nomsgblock +='<li class="msg-sec"><ul class="dropdown-menu-msglist" ><li class="msg" ><div class="col-xs-3">';
                nomsgblock+='<img src="{!! asset("/images/default.png") !!}" id="UserImg" alt="" class="img-circle round_topImg" width="50"></div>';
                nomsgblock+='<div class="col-xs-7 msgdescrp">';
                nomsgblock+='<span class="mainusername">'+ msgdata.firstname+ ' '+userlastname+ '</span>'; 
                nomsgblock+='<p class="whitespaceellips"> You don’t have any messages </p></div>';
                nomsgblock+='</li> </ul></li>';

            }
            $('.msgblocklist').html(nomsgblock);
        }
    });

    });



</script>
</body>

</html>
