<form id="postprojectform" action="{{ route('projects.store') }}"  method="POST" autocomplete="off">
{{ csrf_field() }}
<div id="post_project" class="modal fade w4yModal" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-body ">
               
            <div id="successmsg" class="alert alert-success">
              
            </div>
            <div  id="failure" class="alert alert-danger">

            </div>
            <div class="closeModal" data-dismiss="modal"><i class="fa fa-close"></i></div>
            <h3 class="title">Post a project</h3>
            <div id="project_Carousel" class="carousel slide" data-ride="carousel">
               <!-- Indicators -->
               <ol class="carousel-indicators w4yindicator">
                  <li data-target="#project_Carousel" data-slide-to="0" class="active"></li>
                  <li data-target="#project_Carousel" data-slide-to="1"></li>
                  <li data-target="#project_Carousel" data-slide-to="2"></li>
               </ol>
               <!-- Wrapper for slides -->
               <div class="carousel-inner w4ypopup postProjectPopup">
                  <div class="item active">
                     <h4 class="list-title">1. Describe your job</h4>
                     <div class="w4ycustom">
                        <span class="input input--isao">
                        <span class="pull-right cspull"></span>
                        <input class="input__field input__field--isao pull-left " type="text" id="input-38" name="title" value="{{ old('title') }}" placeholder="Example: Webshop with PayPal integration">
                        <label class="input__label input__label--isao" for="input-38" data-content="Project title">
                        <span class="input__label-content input__label-content--isao">Project title</span>
                        </label>
                        </span>
                        <span class="input input--isao">
                        <span class="pull-right cspull"></span>
                        <div class="textreadiv" >
                              <div  class="editableTextArea input__field input__field--isao" id="input-39" contenteditable="true" data-text="Example: Looking for an experienced front end developer for a 3-6 month project. You will work with a team of international experts for this project. This contract includes multiple sub-projects. Must be experienced with Javascript, AngularJS, Bootstrap, and Kendo UI. Please note we are creating a Rich Internet Application, not a web- site/blog/etc. We have specifications available for applicants to review upon request."></div>
                              <label class="input__label input__label--isao descriptionTxt" for="input-39" data-content="Project description">
                                     <span class="input__label-content input__label-content--isao">Project description</span>
                              </label>
                        </div>
                    
                        </span>
                        <span class="clearfix"> </span>
                     </div>
                  </div>

                  <div class="item">
                     <h4 class="list-title">2. Choose your requirements </h4>
                     <div class="col-md-12">
                        @if(isset($projectrequirements) )
                        @foreach ($projectrequirements as $projectrequirement)                   
                        <div class="col-sm-3 col-xs-4">
                        <label class="box">
                              <span class="fa fa-mobile"></span>
                              <p class="name"><input type="checkbox" value="{{  $projectrequirement->id }}" name="requirements[]">{{ ucfirst($projectrequirement->name) }}</p>
                        </label>
                        </div>
                        @endforeach
                        @endif
                     </div>
                     <div class="col-xs-12">
                        <div class="text-right ">
                           <span class="advance"> <a href="javascript:void(0);">Advanced</a><i class="fa fa-angle-right" aria-hidden="true"></i></span>
                        </div>
                     </div>
                  </div>
                  <div class="item">
                     <h4 class="list-title">3. Set the details </h4>
                     <div class="col-md-12 w4ycustom ">
                        <span class="input input--isao">
                        <span class="pull-right cspull"><i class="fa fa-usd" aria-hidden="true"></i></i></span>
                        <input class="input__field input__field--isao pull-left " type="text" value="{{ old('budget') }}" id="input-40" name="budget">
                        <label class="input__label input__label--isao" for="input-38" data-content="Max. project budget">
                        <span class="input__label-content input__label-content--isao">Max. project budget</span>
                        </label>
                        </span>
                        <span class="input input--isao " id="attach">
                        <span class="pull-right cspull"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                        <input value="{{ old('deadline_date') }}" class="input__field input__field--isao pull-left" onblur="return fnAttachColor(2)" onfocus="return fnAttachColor(1)" type="text" id="date" name="deadline_date">
                        <label id="lblcolor" class="input__label input__label--isao" for="input-39" data-content="Estimated project deadline">
                        <span class="input__label-content input__label-content--isao" id="spandd">Estimated project deadline</span>
                        </label>
                        </span>
                        <span class="clearfix"> </span>
                     </div>
                     <input type="hidden" id="postuserid" name="owner_id" value="{{ Auth::user()->id }}">
                  </div>
               </div>
               <!-- Left and right controls -->
               <a class="left carousel-control " href="#project_Carousel" data-slide="prev">
               <span class="sr-only">Back</span>
               </a>
               <a class="right carousel-control" href="#project_Carousel" data-slide="next">
               <span class="sr-only">Next</span>
               </a>
               <!-- <a class="customsubmit "  href="javascript:$('form').submit();">
               <span class="sr-only">SUBMIT</span>
               </a> -->
               <a class="customsubmit " href="javascript:void(0);" id="postsubmit" >
               <span class="sr-only">SUBMIT</span>
               </a>

            </div>
         </div>
      </div>
   </div>
</div>
</form>
<script src="{!! asset('js/postprojectblade.js') !!}"></script>

