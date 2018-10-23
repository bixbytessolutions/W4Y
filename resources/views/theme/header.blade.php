<div class="container">
   <nav class="navbar navbar-default w4ynavbar" role="navigation">
      <div class="container">
         <!-- Brand and toggle get grouped for better mobile display -->
         <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-brand-centered">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            </button>
            @guest
            <ul class="nav navbar-nav navbar-right customnavbar">
               <li class="login"><a href="{{ route('login') }}">LOGIN</a></li>
            </ul>
            @else
            <ul class="nav navbar-nav navbar-right  visibe-xs hidden-sm hidden-md hidden-lg">
               <li class="dropdown fetchmsglist dropdown-notification ">
                  <div class="fullscreen"></div>
                  <a class="dropdown-toggle" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="true">
                  <i class="fa fa-comments"></i>
                  </a>
                  <ul class="dropdown-menu ">
                     <li class="message">Messages</li>
                     <span class="dividerinner"></span>
                     <div class="overflow msgblocklist">
                      
                      
                     </div>
                   
                  </ul>
               </li>
               <li class="dropdown dropdown-notification ">
                  <div class="fullscreen"></div>
                  <a class="dropdown-toggle uparrowbell" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="true">
                  <i class="fa fa-bell"></i>
                  <span class="fa fa-circle"></span>
                  </a>
                  <ul class="dropdown-menu ">
                     <li class="message">Notifications</li>
                     <span class="dividerinner"></span>
                     <div class="overflow">
                        <li class="msg-sec">
                           <ul class="dropdown-menu-msglist" >
                              <li class="msg" >
                                 <div class="col-xs-2 noticecolor customnotifygreen">
                                    <i class="fa fa-circle" aria-hidden="true"></i>
                                 </div>
                                 <div class="col-xs-10">
                                    <span class="mainusername">Milestone reached</span>
                                    <p class="whitespaceellips">Good News! A milestone has been</p>
                                    <span>1 hour ago</span>
                                 </div>
                              </li>
                           </ul>
                        </li>
                        <li class="msg-sec">
                           <ul class="dropdown-menu-msglist" >
                              <li class="msg" >
                                 <div class="col-xs-2 noticecolor customnotifyred">
                                    <i class="fa fa-circle" aria-hidden="true"></i>
                                 </div>
                                 <div class="col-xs-10">
                                    <span class="mainusername">Paymet failed!</span>
                                    <p class="whitespaceellips">Oh boy! We had problems with your</p>
                                    <span>30 minutes ago</span>
                                 </div>
                              </li>
                           </ul>
                        </li>
                     </div>
                  </ul>
               </li>
               <li class="dropdown smallerdropdown">
                  <div class="fullscreen"></div>
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars "></i></a>
                  <ul class="dropdown-menu  custom-innerdropdown">
                     <li class="external" id="lbl_LY_TotalNotifications">
                        <img src="{!! asset('uploads/avatar/'. Auth::user()->photo) !!}" id="UserImg" alt="" class="img-circle round_topImg" width="50" height="50">
                        <span class="cs">Hello {{ Auth::user()->first_name }}</span>
                     </li>
                     <span class="dividerinner"></span>
                     <li><a href="{{url('/edit-profile')}}">Profile<span class="fa fa-user"></span></a></li>
                     <li><a href="{{url('/my-projects')}}">My projects <span class="fa fa-briefcase "></span></a></li>
                     <li><a href="#">Settings<span class="fa fa-cog">  </span></a></li>
                     <li>
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();">logout <span class="fa fa-sign-out "></span></a> 
                        <form id="logout-form-btn" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                        </form>
                     </li>
                  </ul>
               </li>
            </ul>
            @endguest
            <div class="navbar-brand "><img src="{!! asset('images/logo/w4y.png') !!}"></div>
         </div>
         <!-- Collect the nav links, forms, and other content for toggling -->
         <div class="collapse navbar-collapse" id="navbar-brand-centered">
            <ul class="nav navbar-nav navbar-brand-centered">
              
               <li><a class="hvr-underline-from-center" href="javascript:void(0);">How It Works  </a></li>
               <li><a class="hvr-underline-from-center" href="{{ url('/projects') }}">Find Work </a></li>
                @guest
                <li><a class="hvr-underline-from-center"  href="{{ route('login') }}">Post a Project </a></li>
                @else
                <li><a class="hvr-underline-from-center"   id="clpop" href="javascript:void(0);">Post a Project </a></li> 
                @endguest
                </ul>
                @guest
            <ul class="nav navbar-nav navbar-right custom">
               <li class="login"><a href="{{ route('login') }}">LOGIN</a></li>
            </ul>
            @else
            <ul class="nav navbar-nav navbar-right custom top-nav">
               <li class="dropdown  fetchmsglist dropdown-notification ">
                  <a class="dropdown-toggle" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="true">
                  <i class="fa fa-comments"></i>
                  </a>
                  <ul class="dropdown-menu ">
                     <li class="message">Messages</li>
                     <span class="dividerinner"></span>
                     <div  class="overflow msgblocklist">
                       
                     </div>
                  </ul>
               </li>
               <li class="dropdown dropdown-notification ">
                  <a class="dropdown-toggle uparrowbell" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="true">
                  <i class="fa fa-bell"></i>
                  <span class="fa fa-circle"></span>
                  </a>
                  <ul class="dropdown-menu ">
                     <li class="message">Notifications</li>
                     <span class="dividerinner"></span>
                     <div class="overflow">
                        <li class="msg-sec">
                           <ul class="dropdown-menu-msglist" >
                              <li class="msg" >
                                 <div class="col-xs-2 noticecolor customnotifygreen">
                                    <i class="fa fa-circle" aria-hidden="true"></i>
                                 </div>
                                 <div class="col-xs-10 lastspn">
                                    <span class="mainusername">Milestone reached</span>
                                    <p class="whitespaceellips">Good News! A milestone has been Good News! </p>
                                    <span>1 hour ago</span>
                                 </div>
                              </li>
                           </ul>
                        </li>
                        <li class="msg-sec">
                           <ul class="dropdown-menu-msglist" >
                              <li class="msg" >
                                 <div class="col-xs-2 noticecolor customnotifyred">
                                    <i class="fa fa-circle" aria-hidden="true"></i>
                                 </div>
                                 <div class="col-xs-10 lastspn">
                                    <span class="mainusername">Paymet failed!</span>
                                    <p class="whitespaceellips">Oh boy! We had problems with yourOh boy! </p>
                                    <span>30 minutes ago</span>
                                 </div>
                              </li>
                           </ul>
                        </li>
                     </div>
                  </ul>
               </li>
               <li class="dropdown  dropdown-notification ">
                  <a class="dropdown-toggle" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="true">
                  <i class="fa fa-bars"></i>
                  </a>
                  <ul class="dropdown-menu profile_resize ">
                     <li class="external" id="lbl_LY_TotalNotifications">
                        <img src="{!! asset('uploads/avatar/'. Auth::user()->photo) !!}" id="UserImg" alt="" class="img-circle round_topImg" width="50" height="50">
                        <span id="mainusername" class="hidden-xs">Hello {{ Auth::user()->first_name }}</span>
                     </li>
                     <span class="dividerinner"></span>
                     <li>
                        <ul class="dropdown-menu-list" id="LY_Notification_UL">
                           <li><a href="{{url('/edit-profile')}}"><span class="time"><i class="fa fa-user" aria-hidden="true"></i></span> <span class="details"><span class="label label-sm label-icon label-success"></span>Profile</span></a></li>
                           <li><a href="{{url('/my-projects')}}"><span class="time"><i class="fa fa-briefcase" aria-hidden="true"></i></span> <span class="details"><span class="label label-sm label-icon label-danger"></span>My Projects </span></a></li>
                           <li><a href="javascript:;"><span class="time"><i class="fa fa-cog" aria-hidden="true"></i></span> <span class="details"><span class="label label-sm label-icon label-warning"></span>Settings </span></a></li>
                           <li>
                              <a  href="{{ route('logout') }}"
                                 onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();"><span class="time"><i class="fa fa-sign-out" aria-hidden="true"></i></span> <span class="details"><span class="label label-sm label-icon label-info"></span>Logout</span></a>  
                              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                              {{ csrf_field() }}
                              </form>
                           </li>
                        </ul>
                     </li>
                  </ul>
               </li>
            </ul>
            @endguest
         </div>
         <!-- /.navbar-collapse -->
      </div>
      <!-- /.container-fluid -->
   </nav>
</div>
<div id="placeholder-element"> </div>