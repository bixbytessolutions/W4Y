@extends('theme.default')
@section('content')
@section('title', 'Profile')
<link rel="stylesheet" href="{!! asset('css/page_list.css') !!}">
<style>
    .footer {
        margin-top: 0% !important;
    }
    #tab_default_1{
        margin-bottom:0%;
    }
    
    .timeline {
        padding-bottom: 100px;
    }
    
    #profile_page_list {
        margin-bottom: 0px;
    }
</style>
<section class="content">
    <div class="container">
        <div class="col-md-12">
            <div class="col-md-4 profiledetail">
            <div style="background-image: url({!! asset('uploads/avatar/'. $profile->photo) !!})" class="img-custom-responsiveprofilepage">
        
        </div>
               

                <div class="links desc-center">
                    <span class="fa fa-credit-card-alt" data-toggle="tooltip" data-placement="top" title="" data-original-title="Card"></span>
                    <span class="fa fa fa-envelope-o" data-toggle="tooltip" data-placement="top" title="" data-original-title="Mail"></span>
                    <span class="fa fa-user" data-toggle="tooltip" data-placement="top" title="" data-original-title="User"></span>
                    <span class="fa fa-phone" data-toggle="tooltip" data-placement="top" title="" data-original-title="Phone"></span>
                </div>
                <div class="btn-section">
                    <button id="hireus" type="button" class="btn btn-w4y"> {{ $profile->company==1 ? 'HIRE US' :'HIRE ME' }} </button>
                    <a href="{{url('/chat/init-message?to_user_id='.$profile->id) }}">

                        <button type="button" class="btn btn-w4y"> MESSAGE</button>
                    </a>
                </div>
                <div id="hireussuccess" class="alert alert-success hide">
           
              </div>
            </div>
            <div class="col-md-8 profiledetaildes">
                <h2 class="pageTitle">{{ $profile->first_name}} {{ $profile->last_name}}</h2>
                @if($profile->company==1) @else <p class="tag"><i class="fa fa-building-o"></i>{{ $profile->profile_title }}</p> @endif

              
                <div class="rating detaildiv">
               
                    <div class="">
                
                         @if(isset($totalavg)) <span class="badge"> {{ $totalavg }}   </span> @endif
                
                    </div>

                    <div class="stars ">
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
                        <p>{{ count($reviews) }} {{ count($reviews)>1 ? 'reviews' :'review' }}  </p>
                    </div>
                    <div class="">
                        <p><i class="fa fa-map-marker" aria-hidden="true"></i><span> {{ $profile->city }}, {{ $profile->country }} </span></p>
                    </div>
                    <div class="">
                        <p><i class="fa fa-clock-o" aria-hidden="true"></i><span> {{ $dt->format('h:i a') }} local time</span></p>
                    </div>
                </div>
                <p>{!! $profile->profile_description !!}
                </p>
            </div>
        </div>
    </div>

    <div class="profilepagejumbotron">
        <div class="container">
            <ul class="jumbotrondetails">
                <li>
                    <span class="bold"> &dollar; @if($profile->company==1 ) {{ number_format($employees->avghourlyrate,2) }} @else  {{ $profile->hourly_rate=="" ? '0' : $profile->hourly_rate  }} @endif</span>
                    <span>{{ ($profile->company==1) ? 'Average Hourly rate': 'Hourly rate' }}  </span>
                </li>
                <li>
                    <span class="bold">{{ $profile->totaljobs== "" ? '0': $profile->totaljobs }}</span>
                    <span>Jobs</span>
                </li>
                <li>
                    <span class="bold">112 </span>
                    <span>Respects</span>
                </li>
                <li>
                    <span class="bold">{{  $profile->completed_jobs== "" ? '0' : $profile->completed_jobs }}%</span>
                    <span>Jobs completed</span>
                </li>
            </ul>
        </div>
    </div>

  @if($profile->company==1 )
    <div class="container">
    <div class="employeelist mb-20">
        <div class="row">
            <h2 class="pageTitle text-center title-space"> {{ 'Employees' }}</h2>
            <div class="text-center">
                @foreach($employees as $employee)
                <div class="empObj">
                <div class="employeephoto img-custom-responsiveEmp" style="background-image: url({!! asset('uploads/avatar/'.$employee->photo) !!})"  >
                    <img src="" class="img-responsive">
                </div>
                <span>  {{ $employee->first_name }}{{ $employee->last_name }}</span>
                <p> {{ $employee->profile_title}}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    </div>
@else
@endif
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="tabbable-panel">
                    <div class="tabbable-line">
                        <ul class="nav nav-tabs ">
                            <li class="active">
                                <a href="#tab_default_1" data-toggle="tab">
                                    <i class="fa fa-star" aria-hidden="true"></i> Reviews
                                </a>
                            </li>
                            <li>
                                <a href="#tab_default_2" data-toggle="tab">
                                    <i class="fa fa-graduation-cap" aria-hidden="true"></i>{{($profile->company!=1)?'Skills & Certificates & Education':'Skills & Certificates'}}   </a>
                            </li>
                            <li>
                                <a href="#tab_default_3" data-toggle="tab">
                                    <i class="fa fa-picture-o" aria-hidden="true"></i> Portfolio </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane customwkyd active" id="tab_default_1">
                                <table id="profile_page_list" class="display dataTable no-footer"  cellspacing="0" width="100%" role="grid" aria-describedby="project_list_info " style="width: 100%;">
                                    <thead>
                                        <tr role="row">
                                            <th class="no-sort sorting_disabled" rowspan="1" colspan="1" aria-label="Project Title" style="width: 42.8%;">Project Title</th>
                                            <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Functions" style="width: 10%">Reviewer</th>
                                            <th class="sorting" tabindex="0" aria-controls="project_list" rowspan="1" colspan="1" aria-label="Bids: activate to sort column ascending" style="width: 15%;">Price</th>
                                            <th class="nosort sorting_desc" tabindex="0" aria-controls="project_list" rowspan="1" colspan="1" aria-sort="descending" aria-label="Deadline: activate to sort column ascending" style="width: 15%;">Date</th>
                                            <th class="none sorting" tabindex="0" aria-controls="project_list" rowspan="1" colspan="1" aria-label="Budget: activate to sort column ascending" style="width: 15%;">Rating</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($reviews as $review)
                                        <tr role="row" alt="{{ $review->id }}" class="odd">
                                            <td>{{ $review->title }}</td>
                                            <td>
                                                <span>{{ $review->first_name }}</span>
                                            </td>
                                            <td>$ {{$review->budget}}</td>
                                            <td class="sorting_1">{{ date('d.m.Y', strtotime($review->reviewedon )) }} </td>
                                            <td>
                                                <div class="rating detaildiv">
                                                    <div class="">
                                                       
                                                        <span class="badge">{{ $avg=number_format($review->avgrate,1) }}</span>
                                                      
                                                    </div>
                                                    <div class="stars ">
                                                    
                                                   @for($star=1;$star<=(int)$avg;$star++)
                                                   <span class="fa fa-star checked"></span>
                                                   @endfor
                                                   @if(( $avg * 2 ) % 2)

                                                    <span class="fa fa-star-half"></span>

                                                    @endif
                                                  

                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                      @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="tab_default_2">
                                <div class="skills">
                                  
                                    @if(!$skills->isEmpty())
                                    <h2 class="skillTitle">Skills</h2>
                                    <div class="pills">
                                        @foreach($skills as $skill)
                                        <a href="javascript:void(0);" class="btn pil">{{ $skill->name }}</a>
                                         @endforeach
                                    </div>
                                    @else
                                    <h2 class="skillTitle">Skills are not available</h2>
                                    @endif
                                </div>
                                @if(!$certificates->isEmpty())
                                <div class="Certificates {{($profile->company!=1)?'':'eductiongap'}}">
                                    <h2 class="skillTitle CertificatesTitle">Certificates</h2>
                                    <div class="col-md-12">
                                        @foreach($certificates as $certificate)
                                        <div class="col-md-4 mb-20">
                                            <div class="cerficatebox">
                                                <h2 class="text-left">{{ $certificate->title }}</h2>
                                                <p> {{ $certificate->description }}
                                                </p>
                                                <div class="certficate">
                                                    <img src="{!! asset('images/certficate.png') !!}">
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                @else
                                <h2 class="skillTitle CertificatesTitle"> Certificates are not available</h2>
                                 @endif

                                <div class="timeline {{($profile->company!=1)?'':'hide'}}"  >
                                @if(!$educations->isEmpty())
                                    <h2 class="skillTitle">Education</h2>
                                @else
                                <h2 class="skillTitle" style="{{($profile->company!=1)?'':'display:none'}}"> Education are not available</h2>
                                 @endif
                                    <div class="cd-timeline__container" style="{{($profile->company!=1)?'':'display:none'}}" >
                                        <div class="cd-timeline__block js-cd-block">
                                            <div class="cd-timeline__img cd-timeline__img--picture js-cd-img">
                                                <span class="number">'{{ date("y")}} </span>
                                            </div>
                                        </div>
                                        @if(!$educations->isEmpty())
                                        @foreach($educations as $education)
                                        <div class="cd-timeline__block ">
                                            <div class="cd-timeline__img cd-timeline__img--three js-cd-img" >
                                            </div>
                                            <div class="cd-timeline__content js-cd-content">
                                                <h2>{{ $education->title }}</h2>
                                                <span><b>{{ $education->sub_description }}</b></span>
                                                <p>{{ $education->description }}</p>
                                                <p class="text-right">{{ date('M Y', strtotime( $education->start_date)) }} - {{ date('M Y', strtotime( $education->completiondate)) }}</p>
                                            </div>
                                        </div>
                                        @endforeach
                                       
                                        <div class="cd-timeline__block js-cd-block">
                                            <div class="cd-timeline__img cd-timeline__img--four js-cd-img">
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    
                                </div>
                            </div>
                           
                            <div class="tab-pane paginatediv" id="tab_default_3">
                       
                                <div class="col-md-12" style="padding: 0px;" id="list" >
                                    @foreach($portfolios as $portfolio)
                                    <div class="col-md-4 childdiv">
                                        <div class="wrapbox" >
                                            <div class="imgbox" data-toggle="modal" data-target="#profile_page_{{$loop->iteration}}" style="background-image: url({!! asset('uploads/portfolio/'.$portfolio->portfolioimg) !!});"></div>
                                            <h2 class="porttitle" alt="{{ $portfolio->portfoliourl }}"  >{{ $portfolio->title }}</h2>
                                            <div class="text-right date">
                                                <a href="javascript:void(0);">{{ date('M Y', strtotime( $portfolio->start_date)) }} - {{ date('M Y', strtotime( $portfolio->completiondate)) }}</a>
                                            </div>
                                        </div>
                                   </div>
                                   
                                    @endforeach
                                    
                              </div>
                             
                            </div>
                            
                </div>
            </div>
        </div>
    </div>
</section>

@foreach($portfolios as $portfolio)
<div id="profile_page_{{$loop->iteration}}" class="modal fade w4yModal in" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body ">
                <div class="closeModal" data-dismiss="modal"><i class="fa fa-close"></i></div>
                <h3 class="title">DESCRIPTION</h3>
                <div class="imageload">
                    <img src="{!! asset('uploads/portfolio/'.$portfolio->portfolioimg) !!}" class="img-responsive">
                </div>
                <div class="imageloaddescription">
                    <p>{{ $portfolio->description }}</p>
                    <p class="text-right" style="text-align: right;"><b>$ {{$portfolio->budget}}</b></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
<script src="{!! asset('js/profile_page.js') !!}"></script>

<script>
var number_of_pages=0;
$(document).ready(function() {
    var show_per_page = 9;
    var number_of_items = $('#list').children('.childdiv').length;
    number_of_pages = Math.ceil(number_of_items / show_per_page);
    $('.paginatediv').append('<div class="col-lg-12 clearfix" style="padding-bottom: 115px;"> <div class="dataTables_info" id="profile_page_list_info" role="status" aria-live="polite">Showing 1 to '+ show_per_page +' of '+ number_of_items +' entries</div> <ul class="pagination w4uPagination"></ul></div><input id=current_page type=hidden><input id=show_per_page type=hidden>');

    $('#current_page').val(0);
    $('#show_per_page').val(show_per_page);

    var navigation_html = '<li class="page-item"><a class="page-link prev disabled" onclick="previous()" ><i class="fa fa-angle-left"></i></a></li>';
    var current_link = 0;
    while (number_of_pages > current_link) {
        navigation_html += '<li class="page-item page"  longdesc="' + current_link + '"><a class="page-link" onclick="go_to_page(' + current_link + ')" >' + (current_link + 1) + '</a></li>';
        current_link++;
    }
    navigation_html += '   <li class="page-item"><a class="page-link next" onclick="next()" ><i class="fa fa-angle-right"></i></a></li> ';

    $('.w4uPagination').html(navigation_html);
    $('.w4uPagination .page:first').addClass('active');
    $('#list').children().css('display', 'none');
    $('#list').children().slice(0, show_per_page).css('display', 'block');

});


function go_to_page(page_num) {
    if(page_num==0){
        $('.w4uPagination .page-link.prev').addClass('disabled');
        $('.w4uPagination .page-link.next').removeClass('disabled');
    }else if(page_num == number_of_pages-1){
        $('.w4uPagination .page-link.next').addClass('disabled');
        $('.w4uPagination .page-link.prev').removeClass('disabled');
    }
    var show_per_page = parseInt($('#show_per_page').val(), 0);
    start_from = page_num * show_per_page;
    end_on = start_from + show_per_page;
    $('#list').children().css('display', 'none').slice(start_from, end_on).css('display', 'block');
    console.log()
    //$('.pagination').find('.active').removeClass('active'); // to remove active class
    $('.page[longdesc=' + page_num + ']').addClass('active').siblings('.active').removeClass('active');
    $('#current_page').val(page_num);
}

function previous() {
    new_page = parseInt($('#current_page').val(), 0) - 1;
    //if there is an item before the current active link run the function
    if ($('.active').prev('.page').length == true) {
        go_to_page(new_page);
    }
}

function next() {
    new_page = parseInt($('#current_page').val(), 0) + 1;
//if there is an item after the current active link run the function
    if ($('.active').next('.page').length == true) {
        go_to_page(new_page);
    }

}
</script>
<script>
    $('[data-toggle="tooltip"]').tooltip();

    $('.wrapbox').click(function() {
        $('#post_project').modal('show');
    });

    $("#hireus").click(function(e){
     var toID="{{ $profile->id }}";
        $.ajax({
        url: '{{ url("/hireUsEmail") }}',
        type: "post",
        data:{toID:toID},
            beforeSend:  function() {    
            $('#hireus').css({'pointer-events':'none' , 'opacity':'0.5' });
            },
            success: function (response) {
            $('#hireussuccess').removeClass('hide');
            $('#hireussuccess').delay(3000).fadeOut(350);
            $('#hireussuccess').html('Mail sent!');
            $('#hireus').css({'pointer-events':'all' , 'opacity':'1' });
            },
            error:function(response){
            if(response.status == 401){
                window.location='{{url("/login")}}';
            }}
        });
    }); 
            
    $('.porttitle').click(function (event) {
        event.preventDefault();
        var href = $(this).attr('alt')
        window.open(href);
    });
</script>

@endsection