@extends('theme.default')
@section('content')
@section('title', 'My Projects')
<link rel="stylesheet" href="{!! asset('css/myprofile.css') !!}">
<section class="myprofilepage">
   <div class="container content ">
      <div class="col-md-6">
         <h2 class="pageTitle">My Projects</h2>
      </div>
      <div class="col-md-6">
         <span class="wky-custom-right">
            <ul class="nav panel-tabs">
               <li class="active"><a href="#tab1" data-toggle="tab">EMPLOYER</a></li>
               <li><a href="#tab2" data-toggle="tab">FREELANCER</a></li>
            </ul>
         </span>
      </div>
      <div class="container">
         <div class="row">
            <div class="col-md-12  nopaddingzero">
               <div class="panel">
                  <div class="panel-body">
                     <div class="tab-content">
                        <div class="tab-pane active" id="tab1">
                           <div class="col-md-12 customserachbtm nopaddingzero">
                              <div class="col-lg-5 col-md-5 col-sm-5 col-xs-8 nopaddingzero">
                                 <div class="searchsec">
                                    <input id="searchTermEmp" type="text" class="form-control" placeholder="Search for Project Name, Freelancer etc." />
                                 </div>
                              </div>
                              <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 wkyysearch">
                                 <a href="javascript:void(0);" class="btn"><i class="fa fa-search"></i></a>
                              </div>
                           </div>
                           <div class="">
                              <div class="insidetabcontent">
                                 <div class="col-md-12 nopaddingzero">
                                    <div class="tabbable-panel">
                                       <div class="tabbable-line">
                                          <ul class="nav nav-tabs ">
                                             <li class="active">
                                                <a href="#tab_default_1" data-toggle="tab">
                                                <i class="fa fa-hourglass-half" aria-hidden="true"></i>
                                                Open
                                                </a>
                                             </li>
                                             <li>
                                                <a href="#tab_default_2" data-toggle="tab">
                                                <i class="fa fa-tachometer" aria-hidden="true"></i>
                                                Work in Progress
                                                </a>
                                             </li>
                                             <li>
                                                <a href="#tab_default_3" data-toggle="tab">
                                                <i class="fa fa-check" aria-hidden="true"></i>
                                                Past Projects
                                                </a>
                                             </li>
                                          </ul>
                                          <div class="tab-content">
                                             <div class="tab-pane dataTables_wrapper customwkyd active" id="tab_default_1">
                                                <table id="openprojects" class="display no-footer" cellspacing="0" width="100%" role="grid" aria-describedby="project_list_info " style="width: 100%;">
                                                   <thead>
                                                      <tr role="row">
                                                         <th class="no-sort sorting_disabled" rowspan="1" colspan="1" aria-label="Project Title" style="width: 40%;">Project Title</th>
                                                         <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Functions" style="width: 15%">Bids</th>
                                                         <th class="sorting" tabindex="0" aria-controls="project_list" rowspan="1" colspan="1" aria-label="Bids: activate to sort column ascending" style="width: 15%;">Avg.Bids</th>
                                                         <th class="nosort sorting_desc" tabindex="0" aria-controls="project_list" rowspan="1" colspan="1" aria-sort="descending" aria-label="Deadline: activate to sort column ascending" style="width: 15%;">Bid End date </th>
                                                         <th class="none sorting" tabindex="0" aria-controls="project_list" rowspan="1" colspan="1" aria-label="Budget: activate to sort column ascending" style="width: 15%;">Action</th>
                                                      </tr>
                                                   </thead>
                                                   <tbody>
                                                      
                                                   </tbody>
                                                </table>
                                                <input type="hidden" name="csrfToken" id="csrfToken" value="{{ csrf_token() }}">
                                             </div>
                                             <div class="tab-pane" id="tab_default_2">
                                                <div class="tab-pane dataTables_wrapper customwkyd active" id="tab_default_1">
                                                   <table id="workinprogressprojects" class="display  no-footer" cellspacing="0" width="100%" role="grid" aria-describedby="project_list_info " style="width: 100%;">
                                                      <thead>
                                                         <tr role="row">
                                                            <th class="no-sort sorting_disabled" rowspan="1" colspan="1" aria-label="Project Title" style="width: 40% !important;">Project Title</th>
                                                            <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Functions" style="width: 15% !important">Freelancer</th>
                                                            <th class="sorting" tabindex="0" aria-controls="project_list" rowspan="1" colspan="1" aria-label="Bids: activate to sort column ascending" style="width: 15%;!important">Awarded Bid</th>
                                                            <th class="nosort sorting_desc" tabindex="0" aria-controls="project_list" rowspan="1" colspan="1" aria-sort="descending" aria-label="Deadline: activate to sort column ascending" style="width: 15%;!important">Deadline       </th>
                                                            <th class="none sorting" tabindex="0" aria-controls="project_list" rowspan="1" colspan="1" aria-label="Budget: activate to sort column ascending" style="width: 15% !important;">Action</th>
                                                         </tr>
                                                      </thead>
                                                      <tbody>
                                                       
                                                      </tbody>
                                                   </table>
                                               </div>
                                             </div>
                                             <div class="tab-pane" id="tab_default_3">
                                                <div class="tab-pane dataTables_wrapper customwkyd active" id="tab_default_1">
                                                   <table id="pastprojects" class="display dataTable no-footer" cellspacing="0" width="100%" role="grid" aria-describedby="project_list_info " style="width: 100%;">
                                                      <thead>
                                                         <tr role="row">
                                                            <th class="no-sort sorting_disabled" rowspan="1" colspan="1" aria-label="Project Title" style="width: 40%;">Project Title</th>
                                                            <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Functions" style="width: 15%">Freelancer</th>
                                                            <th class="sorting" tabindex="0" aria-controls="project_list" rowspan="1" colspan="1" aria-label="Bids: activate to sort column ascending" style="width: 15%;">Awarded Bid</th>
                                                            <th class="nosort sorting_desc" tabindex="0" aria-controls="project_list" rowspan="1" colspan="1" aria-sort="descending" aria-label="Deadline: activate to sort column ascending" style="width: 15%;">End Date       </th>
                                                            <th class="none sorting" tabindex="0" aria-controls="project_list" rowspan="1" colspan="1" aria-label="Budget: activate to sort column ascending" style="width: 15%;">Outcome</th>
                                                         </tr>
                                                      </thead>
                                                      <tbody>
                                                         
                                                      </tbody>
                                                   </table>
                                              </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="tab-pane" id="tab2">
                           <div class="col-md-12 nopaddingzero">
                              <div class="col-lg-5 col-md-5 col-sm-5 col-xs-8 nopaddingzero">
                                 <div class="searchsec">
                                    <input type="text" id="searchTermFlncr" class="form-control" placeholder="Search for Project Name, Freelancer etc." />
                                 </div>
                              </div>
                              <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 wkyysearch">
                                 <a href="javascript:void(0);" class="btn"><i class="fa fa-search"></i></a>
                              </div>
                           </div>
                           <div class="">
                              <div class="insidetabcontent">
                                 <div class="col-md-12 nopaddingzero">
                                    <div class="tabbable-panel">
                                       <div class="tabbable-line">
                                          <ul class="nav nav-tabs ">
                                             <li class="active">
                                                <a href="#tab_default_4" data-toggle="tab">
                                                <i class="fa fa-hourglass-half" aria-hidden="true"></i>
                                                Active Bids
                                                </a>
                                             </li>
                                             <li>
                                                <a href="#tab_default_5" data-toggle="tab">
                                                <i class="fa fa-tachometer" aria-hidden="true"></i>
                                                Work in Progress
                                                </a>
                                             </li>
                                             <li>
                                                <a href="#tab_default_6" data-toggle="tab">
                                                <i class="fa fa-check" aria-hidden="true"></i>
                                                Past Projects
                                                </a>
                                             </li>
                                          </ul>
                                          <div class="tab-content">
                                             <div class="tab-pane dataTables_wrapper customwkyd active" id="tab_default_4">
                                                <table id="activebids" class="display dataTable no-footer" cellspacing="0" width="100%" role="grid" aria-describedby="project_list_info " style="width: 100%;">
                                                   <thead>
                                                      <tr role="row">
                                                         <th class="no-sort sorting_disabled" rowspan="1" colspan="1" aria-label="Project Title" style="width: 25%;">Project Title</th>
                                                         <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Functions" style="width: 15%">Bids</th>
                                                         <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Functions" style="width: 15%">My Bid</th>
                                                         <th class="sorting" tabindex="0" aria-controls="project_list" rowspan="1" colspan="1" aria-label="Bids: activate to sort column ascending" style="width: 15%;">Avg.Bids</th>
                                                         <th class="nosort sorting_desc" tabindex="0" aria-controls="project_list" rowspan="1" colspan="1" aria-sort="descending" aria-label="Deadline: activate to sort column ascending" style="width: 15%;">Bid End Date</th>
                                                         <th class="none sorting" tabindex="0" aria-controls="project_list" rowspan="1" colspan="1" aria-label="Budget: activate to sort column ascending" style="width: 15%;">Action</th>
                                                      </tr>
                                                   </thead>
                                                   <tbody>
                                                    
                                                   </tbody>
                                                </table>
                                                
                                             </div>
                                             <div class="tab-pane" id="tab_default_5">
                                                <div class="tab-pane dataTables_wrapper customwkyd active" id="tab_default_1">
                                                   <table id="workprogressflncr" class="display dataTable no-footer" cellspacing="0" width="100%" role="grid" aria-describedby="project_list_info " style="width: 100%;">
                                                      <thead>
                                                         <tr role="row">
                                                            <th class="no-sort sorting_disabled" rowspan="1" colspan="1" aria-label="Project Title" style="width: 40%;">Project Title</th>
                                                            <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Functions" style="width: 15%">Employer</th>
                                                            <th class="sorting" tabindex="0" aria-controls="project_list" rowspan="1" colspan="1" aria-label="Bids: activate to sort column ascending" style="width: 15%;">Awarded Bid</th>
                                                            <th class="nosort sorting_desc" tabindex="0" aria-controls="project_list" rowspan="1" colspan="1" aria-sort="descending" aria-label="Deadline: activate to sort column ascending" style="width: 15%;">Deadline       </th>
                                                            <th class="none sorting" tabindex="0" aria-controls="project_list" rowspan="1" colspan="1" aria-label="Budget: activate to sort column ascending" style="width: 15%;">Action</th>
                                                         </tr>
                                                      </thead>
                                                      <tbody>
                                                       
                                                      </tbody>
                                                   </table>
                                                  </div>
                                             </div>
                                             <div class="tab-pane" id="tab_default_6">
                                                <div class="tab-pane dataTables_wrapper customwkyd active" id="tab_default_6">
                                                   <table id="pastprojectsfrelancer" class="display dataTable no-footer" cellspacing="0" width="100%" role="grid" aria-describedby="project_list_info " style="width: 100%;">
                                                      <thead>
                                                         <tr role="row">
                                                            <th class="no-sort sorting_disabled" rowspan="1" colspan="1" aria-label="Project Title" style="width: 40%;">Project Title</th>
                                                            <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Functions" style="width: 15%">Employer</th>
                                                            <th class="sorting" tabindex="0" aria-controls="project_list" rowspan="1" colspan="1" aria-label="Bids: activate to sort column ascending" style="width: 15%;">Awarded Bid</th>
                                                            <th class="nosort sorting_desc" tabindex="0" aria-controls="project_list" rowspan="1" colspan="1" aria-sort="descending" aria-label="Deadline: activate to sort column ascending" style="width: 15%;">Time       </th>
                                                            <th class="none sorting" tabindex="0" aria-controls="project_list" rowspan="1" colspan="1" aria-label="Budget: activate to sort column ascending" style="width: 15%;">Outcome</th>
                                                         </tr>
                                                      </thead>
                                                      <tbody>
                                                     
                                                      </tbody>
                                                   </table>
                                                 
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <script src="{!! asset('js/myprojects.js') !!}"></script>
</section>


@endsection