@extends('theme.default') @section('content')
@section('title', 'Projects')
<link rel="stylesheet" href="{!! asset('css/page_list.css') !!}">
<link rel="stylesheet" type="text/css" href="{!! asset('css/slick.css') !!}" />
<link rel="stylesheet" type="text/css" href="{!! asset('css/slick-theme.css') !!}" />
<link rel="stylesheet" href="{!! asset('css/owl.carousel.min.css') !!}">

<section>
    <div class="container mtop">
        <h2 class="pageTitle">Featured Projects</h2>
        <div id="" class="pListCarousel owl-carousel">
            @foreach($projects as $project)
            <div class="projectWraper project">
                <h5>{{ $project->title}} </h5>
                <p>@if ( strlen( $project->description ) > 160 ) {{ substr( $project->description, 0, 160 ) }} ... @else {{ $project->description }} @endif </p>
                <div>
                    <div class="pull-left">
                        <i class="fa fa-envelope-o picon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Mail"></i>
                        <i class="fa fa-mobile picon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Mobile"></i>
                        <i class="fa fa-cogs picon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Phone"></i>
                        <i class="fa fa-shopping-cart picon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Cart"></i>
                    </div>

                    <div class="pull-right ptop">
                        <span>Until {{ date('d.m.Y', strtotime($project->deadline_date )) }}</span>
                        <span class="price">{{ $project->budget }} $</span>
                    </div>

                    <div class="clearfix"></div>
                </div>
            </div>
            @endforeach

        </div>
</section>
<div class="container"><span class="divider"> </span> </div>
<section>
    <div class="container">
        <h2 class="pageTitle">All Projects</h2>
        <button type="button" id="filterproject" min="{{ $budgetmin }}" max="{{ $budgetmax }}" class="btn btn-w4y pull-right" data-toggle="modal" data-target="#filter">Filter</button>
        <div class="tbdy">
            <table id="TABLE_2" class="display dataTable" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th class="no-sort">Project Title</th>
                        <th>Functions</th>
                        <th>Bids</th>
                        <th class="nosort">Deadline</th>
                        <th class="none">Budget</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($projects as $project)
                    <tr alt="{{ $project->id}}">
                        <td>{{ $project->title}}</td>

                        <td>
                            <i class="fa fa-envelope-o picon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Mail"></i>
                            <i class="fa fa-mobile picon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Mobile"></i>
                            <i class="fa fa-cogs picon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Share"></i>
                            <i class="fa fa-shopping-cart picon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Cart"></i>

                        </td>

                        <td>{{ $project->bidcount }}</td>
                        <td>Until {{ date('d.m.Y', strtotime($project->deadline_date )) }}</td>
                        <td>{{ $project->budget }} $</td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
<form method="post" action="{{ url('project') }}">
{{ csrf_field() }}
    <!-- Modal -->
    <div id="filter" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">

                    <div class="row">

                        <div class="col-xs-4">
                            <div class="closeModal pull-left" data-dismiss="modal"><i class="fa fa-close"></i></div>
                        </div>
                        <div class="col-xs-4">
                            <h3 class="title">Filters</h3></div>
                        <div class="col-xs-4">
                            <button type="button" id="filterbtnlist" data-dismiss="modal" class="btn btn-w4y pull-right">Apply</button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div>
                        <p class="listHeading">Functions</p>

                        <div id="functionalCarousel" class="carousel slide" data-interval="false" data-ride="carousel">
                            <!-- Indicators -->

                            <!-- Wrapper for slides -->
                            <div class="carousel-inner">
                                <div class="item active">
									@foreach ($projectrequirements as $key=>$projectrequirement)
									 @if($key%4==0 && $key>0)
                                </div>
                                <div class="item ">
                                    @endif

                                    <label class="projectWraper handCursor {{ $key=='0' ? 'active' :'' }}">
                                    <input class="hideInput" {{ $key=='0' ? 'checked="checked"' :'' }}  type="checkbox" value="{{  $projectrequirement->id }}" name="requirements[]">
                                        <i class="fa fa-shopping-cart picon" aria-hidden="true"></i>
                                        <p> {{ ucfirst($projectrequirement->name) }}</p>
                                    </label>

                                    @endforeach

                                </div>
                            </div>

                            <!-- Left and right controls -->
                            <a class="left carousel-control" href="#functionalCarousel" data-slide="prev">
                                <i class="fa fa-angle-left" aria-hidden="true"></i>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="right carousel-control" href="#functionalCarousel" data-slide="next">
                                <i class="fa fa-angle-right" aria-hidden="true"></i>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                        <p class="listHeading">Budget</p>
                        <div id="slider-range"></div>
                        <input type="hidden" class="form-control minbudget" value="0" name="minbudjet" id="budjetmin">
                        <input type="hidden" class="form-control maxbudget" value="0" name="maxbudjet" id="budjetmax">
                        <p id="leftRange" class="pull-left"></p>
                        <p id="rightRange" class="pull-right"> </p>
                        <div class="clearfix"></div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</form>

<script src="{!! asset('js/jquery-touch.js') !!}"></script>
<script src="{!! asset('js/jquery-mobile.js') !!}"></script>
<script src="{!! asset('js/custom.js') !!}"></script>
<script src="{!! asset('js/projectlist.js') !!}"></script>
<script>

$("#filterbtnlist").click(function(e){
    e.preventDefault();
    var myCheckboxes = new Array();
    $("input:checked").each(function() {
        myCheckboxes.push($(this).val());
    });

        
    if($('#budjetmin').val() != "")
    {
    var budjetmin = $("#budjetmin").val();
    }
    if($('#budjetmax').val() != "")
    {
    var budjetmax = $("#budjetmax").val();
    }

    $.ajax({
        type:'POST',
        url: '{{ url("/projectfilter") }}',
        data:{myCheckboxes:myCheckboxes,budjetmin:budjetmin,budjetmax:budjetmax},
        success:function(responce){
         $('.tbdy').html(responce.html);
        }

    });

});
</script>
@endsection