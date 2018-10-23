@extends('theme.default') @section('content')
@section('title', 'Projects')
<link rel="stylesheet" href="{!! asset('css/jquery-ui.css') !!}">
<section class="content">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
            <div class="row">
               <div class="col-md-7 leftdescription" style="min-height:430px;">
                  <h2>{{ $project->title }}</h2>
                  <p> {{ 'Greeting, dear freelancer!' }}</p>
                  <p> {!! nl2br( $project->description ) !!} </p>
                  <p> {{ 'Thank you for your time' }}</p>
               </div>
               <div class="col-md-1">
               </div>
               <div class="col-md-4 rightdescription @guest @else afterlogin @endguest">
                  <div class="custom-left">
                     <h4>Budget {{ $project->budget }} USD </h4>
                  </div>
                  <div class="custom-right">
                     <h4><span style="color:#1eb858;" id="deadLineStatus" ></span> <span id="deadLineDate"></span></h4>
                  </div>
                  <div class="overlay"></div>
                  <form id="bidproejctform" class="form-horizontal " data-spy="affix" data-offset-top="205" id="formfloat">
                     {{ csrf_field() }}
                     <div class="innerForm">
                        <div id="bidsuccessmsg" class="alert alert-success hide">
                        </div>
                        <div  id="bidfailure" class="alert alert-danger hide">
                        </div>
                        <div class="text-right customclosew4y"><i class="fa fa-times" aria-hidden="true"></i></div>
                        <div class="row form-group">
                           <div class="col-sm-6 labelclass">
                              @if(isset($session)){{ $session->bidamt }} @endif
                              <input type="text" class="form-control" name="bidamt" id="bidamt" value="">
                              <p>Your bid</p>
                           </div>
                           <div class="col-sm-6 labelclass">
                              <input type="text"  name="finishdate"  class="form-control finishdate" id="datetimepicker">
                              <p>Finish date</p>
                           </div>
                        </div>
                        <div class="row form-group">
                           <div class="col-md-12">
                              <textarea class="form-control describeskills" name="describeskills" id="mensaje" placeholder="Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua"></textarea>
                              <p class="visible " style="display: none;">Describe your skills</p>
                           </div>
                        </div>
                        <div class="text-left desc ">
                           <p>Describe your skills</p>
                        </div>
                        <div class="text-right stc">
                           <button type="button" id="bidbutton" class="btn btn-w4y  "> BID</button>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="customjumbotron">
      <div class="container">
         <div class="row">
            <div class="col-md-12 ">
               <div class="row">
                  <div class="col-md-3 col-xs-6">
                     <div class="detail">
                        <h4>{{ $project->first_name }} {{ $project->last_name }}</h4>
                        <p><i class="fa fa-map-marker" aria-hidden="true"></i><span> {{ $project->city }}, {{ $project->country }}</span></p>
                     </div>
                  </div>
                  <div class="col-md-3 sectiondet  col-xs-6">
                     <div class="rating">
                        <div class="">
                           @if(isset($totalavg)) <span class="badge"> {{ $totalavg }}    </span> @endif
                        </div>
                        <div class="stars">
                           @if(isset($totalavg))
                           @for($startot=1;$startot<=(int)$totalavg;$startot++)
                           <span class="fa fa-star checked"></span>
                           @endfor
                           @if(( $totalavg * 2 ) % 2)
                           <span class="fa fa-star-half"></span>
                           @endif
                           @endif
                        </div>
                        <div class="">
                           <p>{{ count($reviews) }} @if(count($reviews)>1) reviews @else review @endif</p>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="links">
                        <span class="fa fa-credit-card-alt" data-toggle="tooltip" data-placement="top" title="" data-original-title="Card"></span>
                        <span class="fa fa fa-envelope-o" data-toggle="tooltip" data-placement="top" title="" data-original-title="Mail"></span>
                        <span class="fa fa-user" data-toggle="tooltip" data-placement="top" title="" data-original-title="User"></span>
                        <span class="fa fa-phone" data-toggle="tooltip" data-placement="top" title="" data-original-title="Phone"></span>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="container">
      <div class="resultsection">
         <div class="col-md-12">
            <div class="row">
               <h3>{{count($bidders)}} webdesigners are bidding on average $ {{ (int)$bidders->avgBidamt }}  for this job</h3>
               @foreach($bidders as $bidder)
               <div class="col-md-9 detailview">
                  <div class="row">
                     <div class="col-md-3 col-xs-3">
                     @if($bidder->photo)
                        <div class="desc-image img-responsivedescimg"  style=" background-image: url({!! asset('uploads/avatar/'. $bidder->photo ) !!}) " >
                        </div>
                        @else
                        <div class="desc-image img-responsivedescimg"  style=" background-image: url({!! asset('uploads/avatar/userdefault.jpg' ) !!}) " >
                        </div>
                     @endif

                     </div>
                     <div class="col-md-6 col-xs-9">
                        <div class="desc-title">
                           <span class="head">{{ $bidder->firstName}} {{ $bidder->lastName}}</span> 
                           <span class="place">{{ $bidder->city}} {{ $bidder->country}}</span>
                           <p class="show-read-more"> {{$bidder->descriptionProfile }}</p>
                        </div>
                     </div>
                     <div class="col-md-3">
                        <ul class="custom-listing">
                           <h5 class="sizes">$ {{ $bidder->bidAmt }} </h5>
                           <h5 class="review-no">{{$bidder->takendays}} days</h5>
                           <div class="rating custom-rating">
                              <div class="stars">
                                 @if(isset($bidder->avgtotal))  <span class="badge">{{ number_format($bidder->avgtotal,1) }}</span> @endif
                                 @if(isset($bidder->avgtotal))
                                 @for($startot=1;$startot<=(int)$bidder->avgtotal;$startot++)
                                 <span class="fa fa-star checked"></span>
                                 @endfor
                                 @if(( $bidder->avgtotal * 2 ) % 2)
                                 <span class="fa fa-star-half"></span>
                                 @endif
                                 @endif
                              </div>
                           </div>
                        </ul>
                     </div>
                  </div>
               </div>
               @endforeach
            </div>
         </div>
      </div>
   </div>
</section>
<div class="float">
   <button type="button" class="btn btn-w4y  "> BID</button>
</div>
<script src="{!! asset('js/bidpage.js') !!}"></script>
<script>
   var maxLength = 250;
   
   $("#bidbutton").click(function(e){
        e.preventDefault();
        if($('#bidamt').val() != "")
        {
        var bidamt = $("#bidamt").val();
        }
        
        if($('.finishdate').val() != "")
        {
        var finishdate = $(".finishdate").val();
        }
        
        if($('.describeskills').val() != "")
        {
        var describeskills = $(".describeskills").val();
        }
        var projectid = '{{ $project->id }}';
   
         $.ajax({
            type:'POST',
            url: '{{url("/projects/bidproject")}}',
            data:{bidamt:bidamt,projectfinishdate:finishdate,biddermsg:describeskills,projectid:projectid},
            
                beforeSend:  function() {    
            $('#bidbutton').css({'pointer-events':'none' , 'opacity':'0.5' });
            
                    },
            
            success:function(responce){
                $('#bidsuccessmsg').show();
                $('#bidsuccessmsg').removeClass('hide');
                $('#bidsuccessmsg').html(responce);
                $('#bidsuccessmsg').delay(3000).fadeOut(350);
                $('#bidbutton').css({'pointer-events':'all' , 'opacity':'1' });
                $('#bidfailure').addClass('hide');;
                $('#bidproejctform')[0].reset();
                $('.describeskills').text('');
                $('.describeskills').attr('placeholder', '');
                
            },
            error:function(response){
            
                if(response.status == 401){
                    window.location='{{url("/login")}}';
                }
                
                let error = jQuery.parseJSON(response.responseText);
            
                error=error.errors;
            
                let errorText="<ul>";
                if(response.status == 404)
                {
                    let errorDuplicate = response.responseText;
                    $('#bidfailure').show();
                    $('#bidfailure').removeClass('hide');
                    $('#bidfailure').delay(3000).fadeOut(350);
                    $('#bidfailure').html(errorDuplicate);
                    $('#bidproejctform')[0].reset();
                    $('.describeskills').text('');
                    $('.describeskills').attr('placeholder', '');
                    
                }
                if(error.hasOwnProperty('bidamt')){
                    if(error.bidamt.length > 0){
                        errorText+="<li>"+error.bidamt[0]+"</li>";
                    }
                }
                if(error.hasOwnProperty('projectfinishdate')){
            
                if(error.projectfinishdate.length > 0){
                    errorText+="<li>"+error.projectfinishdate[0]+"</li>";
                }
                    }
            
                if(error.hasOwnProperty('biddermsg')){
                    if(error.biddermsg.length > 0){
                        errorText+="<li>"+error.biddermsg[0]+"</li>";
                    }
                }
            
                errorText+="</ul>";
                $('#bidfailure').show();
                $('#bidfailure').removeClass('hide');
                $('#bidfailure').html(errorText);
                $('#bidfailure').delay(3000).fadeOut(350);
                $('#bidbutton').css({'pointer-events':'all' , 'opacity':'1' });
            
                }
        
        });
   
   });
   
   var deadline = new Date("{{ $project->end }}").getTime();
    console.log(deadline);
   var x = setInterval(function() {
   
   var now = new Date().getTime();
   
   var t =   deadline - now;
   
   var days = Math.floor(t / (1000 * 60 * 60 * 24));
   var hours = Math.floor((t%(1000 * 60 * 60 * 24))/(1000 * 60 * 60));
   var minutes = Math.floor((t % (1000 * 60 * 60)) / (1000 * 60));
   var seconds = Math.floor((t % (1000 * 60)) / 1000);
   
   var str = days +':'+hours +':'+minutes +':'+seconds;
   
   $('#deadLineDate').html(str);
   $('#deadLineStatus').text("Open");
   if (t < 0) {
           clearInterval(x);
           var statusStr = "Closed";
           $('#deadLineDate').hide();
           $('#deadLineStatus').html(statusStr);
           $('#bidbutton').prop('disabled',true)
     
       }
   }, 1000);
</script>
@endsection