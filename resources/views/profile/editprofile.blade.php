@extends('theme.default')
@section('content')
@section('title', 'Edit Profile')
<link rel="stylesheet" href="{!! asset('css/selectize.default.css') !!} ">
<link rel="stylesheet" href="{!! asset('css/editProfile.css') !!} ">
<style>
   .footer {
   margin-top: 0% !important;
   }
   .timeline {
   padding-bottom: 75px; 
   }
   [v-cloak] {display: none}
</style>
<div id="editProfile"  v-cloak>
   <section class="content" >
      <div class="container">
         <div class="row">
            <div class="col-md-12">
               <div class="row">
                  <div class="col-md-4 profiledetail">
                     <div :style="{ backgroundImage: 'url(' + urlPrefix + 'uploads/avatar/' +userData.photo + ')' }" id="profile_file_holder" class="img-custom-responsive"> </div>
                     <div class="btn-section">
                        <label type="button" for="profile_file"  class="btn btn-w4y btn-rounded ">
                        <i class="fa fa-edit"></i>
                        </label>
                        <input type="file" class="op_0" id="profile_file"  v-show="false" v-model="selectedFile" v-on:change="uploadFile()" accept="image/*"  />
                     </div>
                  </div>
                  <div class="col-md-8 profiledetaildes">
                     <h2 v-if="isCompany" class="pageTitle pull-left"><% userData.first_name %> <% userData.last_name %> </h2>
                     <h2 v-else class="pageTitle pull-left"><% userData.title %>.<% userData.first_name %> <% userData.last_name %> </h2>
                     <button v-if="isCompany" type="button"  :disabled="$v.userData.zip.$error || $v.userData.first_name.$error  || $v.userData.email.$error || $v.userData.phone_no.$error" v-on:click="updateCompanyUserData()" class="btn btn-w4y pull-right">SAVE PROFILE</button>
                     <button v-else="isCompany" type="button"  :disabled="$v.userData.zip.$error || $v.userData.first_name.$error  || $v.userData.email.$error || $v.userData.phone_no.$error" v-on:click="updateUserData()" class="btn btn-w4y pull-right"> SAVE PROFILE</button>
                     <div class="col-md-12">
                        <div id="successmsgUpdate" class="alert alert-success"  v-if="updateprofileMsg==true">Updated successfully!</div>
                     </div>
                     <div class="editWraper">
                        <div class="textArea" id="description" v-html="userData.profile_description" contenteditable="true">
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
   <div class="container sectionTop" id="saveProfileTop" :class="{ 'mb-0' : isCompany }">
      <div class="row" v-show="isCompany">
         <!-- company Details start -->
         <div class="col-md-6">
            <h2 class="pageTitle mb-4">Company Details</h2>
            <form autocomplete="off" class="title_mB">
               <div class="row">
                  <div class="form-group col-md-12">
                     <input type="text" v-model="userData.first_name" :class="status($v.userData.first_name)" :blur="$v.userData.first_name.$touch()" class="form-control input-rounded mb-10 first_name"  placeholder="First Name">
                  </div>
                  <div class="form-group col-md-12">
                     <input type="text" v-model="userData.last_name"  class="form-control input-rounded mb-10 last_name"  placeholder="Last Name">
                  </div>
               </div>
               <div class="row">
                  <div class="form-group col-md-6">
                     <input type="text" v-model="userData.zip" :blur="$v.userData.zip.$touch()" class="form-control input-rounded mb-10 zip"  placeholder="Pincode">
                     <div class="error" v-if="$v.userData.zip.$error">
                        <div class="error" v-if="!$v.userData.zip.numeric">Pincode Must be number.</div>
                     </div>
                  </div>
                  <div class="form-group col-md-6">
                     <input type="text" v-model="userData.city" class="form-control input-rounded mb-10 city"  placeholder="City">
                  </div>
               </div>
               <div class="row">
                  <div class="form-group col-md-6">
                     <div class="select-wrapper csdropdown" id="country">
                        <select class="form-control input-rounded" v-model="userData.country">
                           <option value="null">Please select</option>
                           <option value="Switzerland">Switzerland</option>
                           <option value="India">India</option>
                           <option value="US">US</option>
                        </select>
                     </div>
                  </div>
                  <div class="form-group col-md-6">
                  </div>
               </div>
         </div>
         <div class="col-md-6">
         <div class="row">
         <div class="form-group col-md-12 mt-62">
         <input type="text" autocomplete="off" v-model="userData.phone_no" v-on:blur="$v.userData.phone_no.$touch()" class="form-control input-rounded mb-10 phone"  placeholder="Phone">
         <div class="error" v-if="$v.userData.phone_no.$error">
         <div class="error" v-if="!$v.userData.phone_no.phoneRegx">Please Enter valid mobile number.</div>
         </div>
         </div>
         <div class="form-group col-md-12">
         <input type="text" v-model="userData.email" v-on:blur="$v.userData.email.$touch()"  :class="status($v.userData.email)" class="form-control input-rounded mb-10 email"  placeholder="email">
         <div class="error" v-if="$v.userData.email.$error">
         <div class="error" v-if="!$v.userData.email.email">Please Enter valid Email.</div>
         </div>
         <div class="error" v-if="updateemailerror==true">Email already Exist</div>
         </div>
         </div>
         </form>
         </div>
      </div>
      <!-- company Details end -->
      <!-- profile Details start -->
      <div class="row" v-show="isCompany==false">
         <div class="col-md-6">
            <h2 class="pageTitle mb-4">Profile Details </h2>
            <form autocomplete="off" class="">
               <div class="row">
                  <div class="form-group col-md-3">
                     <div class="select-wrapper csdropdown" id="title">
                        <select class="form-control input-rounded"  v-model="userData.title">
                           <option value="null">&nbsp;</option>
                           <option value="He">He</option>
                           <option value="Mr">Mr</option>
                           <option value="Herr">Herr</option>
                           <option value="Mrs">Mrs</option>
                        </select>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="form-group col-md-6">
                     <input type="text" class="form-control input-rounded mb-10 first_name" v-on:blur="$v.userData.first_name.$touch()" v-model="userData.first_name "  :class="status($v.userData.first_name)"  placeholder="First Name">
                  </div>
                  <div class="form-group col-md-6">
                     <input type="text" class="form-control input-rounded mb-10 last_name" v-model="userData.last_name "  placeholder="Last Name">
                  </div>
               </div>
               <div class="row">
                  <div class="form-group col-md-12">
                     <input type="text" v-model="userData.street" class="form-control input-rounded mb-10 street"  placeholder="Address">
                  </div>
               </div>
               <div class="row">
                  <div class="form-group col-md-6">
                     <input type="text" v-model="userData.zip" v-on:blur="$v.userData.zip.$touch()" class="form-control input-rounded mb-10 zip"  placeholder="Pincode">
                     <div class="error" v-if="$v.userData.zip.$error">
                        <div class="error" v-if="!$v.userData.zip.numeric">Pincode Must be number.</div>
                     </div>
                  </div>
                  <div class="form-group col-md-6">
                     <input type="text" v-model="userData.city" class="form-control input-rounded mb-10 city"  placeholder="City">
                  </div>
               </div>
               <div class="row">
                  <div class="form-group col-md-6">
                     <div class="select-wrapper csdropdown" id="country" >
                        <select class="form-control input-rounded" v-model="userData.country">
                           <option value="null"> Select country</option>
                           <option value="Switzerland">Switzerland</option>
                           <option value="India">India</option>
                           <option value="US">US</option>
                        </select>
                     </div>
                  </div>
                  <div class="form-group col-md-6">
                     <div class="dateWraper">
                        <input type="text" readonly v-model="userData.dob" class="form-control input-rounded mb-10" id="date" placeholder="DOB">
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="form-group col-md-12">
                     <input type="text" v-model="userData.email" v-on:blur="$v.userData.email.$touch()"  :class="status($v.userData.email)" class="form-control input-rounded mb-10" id="email" placeholder="Enter email">
                     <div class="error" v-if="$v.userData.email.$error">
                        <div class="error" v-if="!$v.userData.email.email">Please Enter valid Email.</div>
                     </div>
                     <div class="error" v-if="updateemailerror==true">Email already Exist</div>
                  </div>
                  <div class="form-group col-md-12">
                     <input type="text" v-model="userData.phone_no" v-on:blur="$v.userData.phone_no.$touch()"  class="form-control input-rounded mb-10" id="phone" placeholder="Enter phone">
                     <div class="error" v-if="$v.userData.phone_no.$error">
                        <div class="error" v-if="!$v.userData.phone_no.phoneRegx">Please Enter valid mobile number.</div>
                     </div>
                  </div>
               </div>
            </form>
         </div>
         <div class="col-md-6">
            <h2 class="pageTitle mb-4">Change Password</h2>
            <div class="row">
               <div class="form-group col-md-12 mt-4">
                  <input type="password" autocomplete="new-password" v-model.trim="passwordNew" v-on:blur="$v.passwordNew.$touch()" class="form-control input-rounded mb-10"  :class="status($v.passwordNew)" id="exampleInputEmail8" placeholder="Password" >
                  <div class="error" v-if="$v.passwordNew.$error ">
                     <div class="error" v-if="!$v.passwordNew.pwdRegx">Password must have Uppercase, Lowercase, Numeric, Special Character and minimum 6 characters</div>
                  </div>
               </div>
               <div class="form-group col-md-12">
                  <input type="password" autocomplete="off" v-model.trim="$v.passwordConfirm.$model" v-on:blur="$v.passwordConfirm.$touch()" :class="status($v.passwordConfirm)" class="form-control input-rounded mb-10" id="exampleInputEmail9" placeholder="Confirm Password">
                  <div class="error" v-if="$v.passwordConfirm.$error">
                     <div class="error" v-if="!$v.passwordConfirm.sameAsPassword">Passwords must be identical.</div>
                  </div>
               </div>
               <div class="form-group col-md-12">
                  <span class="success" v-if="successMsg==true">Updated successfully!</span>
                  <button type="button"  :disabled="$v.passwordNew.$error  || $v.passwordConfirm.$error" v-on:click="changePassword()" class="btn btn-w4y pull-right"> CHANGE PASSWORD</button>
               </div>
            </div>
         </div>
      </div>
      <!-- profile  Details end -->
      <!-- Employees section start -->
      <div class="row" v-show="userData.companyid !=0 ||  isCompany">
         <div class="container">
            <h2 class="pageTitle"> Employees</h2>
            <table id="employees_list" class="display dataTable" cellspacing="0" width="100%">
               <thead>
                  <tr>
                     <th class="no-sort">Name</th>
                     <th>E-Mail Address</th>
                     <th>Position</th>
                     <th v-if="userData.role_id==adminRoleId ||  isCompany" class="nosort">Permissions</th>
                     <th v-if="userData.role_id==adminRoleId ||  isCompany" class="nosort text-center">Edit</th>
                     <th v-if="userData.role_id==adminRoleId ||  isCompany" class="nosort text-center">Delete</th>
                  </tr>
               </thead>
               <tbody>
                  <tr v-for="(companyUser, key) in  companyUserData">
                     <td><% companyUser.first_name %> <% companyUser.last_name %></td>
                     <td>
                        <% companyUser.email %>
                     </td>
                     <td><% companyUser.profile_title %></td>
                     <td v-if="userData.role_id==adminRoleId ||  isCompany"><% companyUser.name %></td>
                     <td  v-if="userData.role_id==adminRoleId ||  isCompany"  class="text-center"><i data-toggle="modal" v-on:click="loadComapnyUsers(companyUser)" data-target="#AddEmployee" class="fa fa-edit  pr-10  icon-btn"></i></td>
                     <td v-if="userData.role_id==adminRoleId ||  isCompany " v-model="companyUser.id" class="text-center"> <i data-toggle="modal" data-target="#deleteModal"  v-on:click="loadComapnyUsers(companyUser)" class="fa fa-trash pr-25  icon-btn"></i></td>
                  </tr>
               </tbody>
            </table>
            <button type="button" v-if="userData.role_id==adminRoleId ||  isCompany" class="btn btn-w4y pull-right mt-20" data-toggle="modal" v-on:click="loadComapnyUsers(null)" data-target="#AddEmployee"> ADD EMPLOYEE</button>
         </div>
      </div>
      <div class="row" v-show="isCompany">
         <div class="col-md-7">
            <h2 class="pageTitle">Invite Employee</h2>
            <div class="row" v-if="loadingInvite==true">
               <div class="form-group col-md-12">
                  <div id="successmsgUpdate" class="alert alert-info">Sending... </div>
               </div>
            </div>
            <div class="row" v-if="successInvite">
               <div class="form-group col-md-12">
                  <div id="successmsgUpdate" class="alert alert-success"  >Invited successfully!</div>
               </div>
            </div>
            <div class="row" v-if="errorInvite || $v.employeeEmail.$error  ">
               <div class="form-group col-md-12">
                  <div id="successmsgUpdate" class="alert alert-danger">Invalid Email!</div>
               </div>
            </div>
            <span for="" class="displayFlex">
               <input type="text" class="form-control input-rounded" v-on:blur="$v.employeeEmail.$touch()" :class="status($v.employeeEmail)"  v-model="employeeEmail" style="margin-right: 20px;" placeholder="Username or E-mail address" />
               <div><button type="button" v-on:click="inviteEmployee()" :disabled="$v.employeeEmail.$error" class="btn btn-w4y middle-btn"> INVITE EMPLOYEE</button></div>
            </span>
         </div>
      </div>
      <!-- Employees section end -->
   </div>
   <div class="container" >
      <div class="row">
         <div class="col-md-12">
            <div class="tabbable-panel">
               <div class="tabbable-line">
                  <ul class="nav nav-tabs ">
                     <li v-if="isCompany" class="active">
                        <a href="#tab_default_2" data-toggle="tab">
                        <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                        Certificates </a>
                     </li>
                     <li v-else="isCompany" class="active">
                        <a href="#tab_default_2" data-toggle="tab">
                        <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                        Skills & Certificates & Education </a>
                     </li>
                     <li>
                        <a href="#tab_default_3" data-toggle="tab">
                        <i class="fa fa-picture-o"></i>
                        Portfolio </a>
                     </li>
                  </ul>
                  <div class="tab-content">
                     <div class="tab-pane active" id="tab_default_2">
                        <!-- skill  section start -->
                        <div class="skills" v-if="isCompany==false">
                           <h2 class="skillTitle">Skills</h2>
                           <div class="pills">
                              <input class="skillInput" id="skillInput" value="" />
                              <button class="btn btn-rounded b-color skillBtn" v-on:click="addSkill()">
                              <i class="fa fa-plus"></i>
                              </button>
                           </div>
                           <div class="pills">
                              <a href="javascript:void(0);"  v-for="(item, key) in skills" class="btn pil removeBtn"><% item.name %>
                              <span class="removeBtnIcon" v-on:click="deleteUserSkill(item.id)" v-bind:id="item.id">
                              <i class="fa fa-times"></i>
                              </span>
                              </a>
                           </div>
                        </div>
                        <!-- skill  section end -->
                        <!-- certificate  section start -->
                        <div class="Certificates">
                           <h2 v-if="isCompany==false" class="skillTitle CertificatesTitle">Certificates</h2>
                           <button type="button" class="btn btn-w4y middle-btn" data-toggle="modal"  v-on:click="loadCertificates(null)" data-target="#AddCertificate" > ADD CERTIFICATE</button>
                           <div class="col-md-12">
                              <div class="col-md-4 mb-20" v-for="(certificate, key) in certificates">
                                 <div class="cerficatebox editBtnGroup"   >
                                    <h2 class="text-left"><% certificate.title %></h2>
                                    <p>
                                       <% certificate.description %>
                                    </p>
                                    <div class="certficate">
                                       <img src="{!! asset('images/certficate.png') !!}">
                                    </div>
                                    <div class="editBtnWraper">
                                       <span data-toggle="modal" v-on:click="loadCertificates(certificate)" data-target="#AddCertificate" class="removeBtnIcon">
                                       <i class="fa fa-edit"></i>
                                       </span>
                                       <span  v-on:click="deleteCertificate(certificate.id)"  class="removeBtnIcon">
                                       <i class="fa fa-times"></i>
                                       </span>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="clearfix"></div>
                        </div>
                        <!-- certificate  section end -->
                        <!-- education  section start -->
                        <div class="timeline" v-if="isCompany==false">
                           <h2 class="skillTitle">Education</h2>
                           <button type="button" class="btn btn-w4y middle-btn" data-toggle="modal" v-on:click="loadEducations(null)" data-target="#AddEducation"> ADD EDUCATION</button>
                           <div class="cd-timeline__container">
                              <div class="cd-timeline__block js-cd-block">
                                 <div class="cd-timeline__img cd-timeline__img--picture js-cd-img">
                                    <span class="number">'<% new Date().getFullYear().toString().substr(2, 2)  %></span>
                                 </div>
                              </div>
                              <div v-for="(education, key) in educations"  class="cd-timeline__block ">
                                 <div class="cd-timeline__img cd-timeline__img--three js-cd-img">
                                 </div>
                                 <div  class="cd-timeline__content editBtnGroup js-cd-content">
                                    <h2><% education.title %></h2>
                                    <span><% education.sub_description %></span>
                                    <p><% education.description %></p>
                                    <p class="text-right"><% education.view_start_date %> - <% education.view_completiondate %></p>
                                    <div class="editBtnWraper">
                                      
                                       <span data-toggle="modal" data-target="#AddEducation" v-on:click="loadEducations(education)" class="removeBtnIcon">
                                       <i class="fa fa-edit"></i>
                                       </span>
                                       <span class="removeBtnIcon" v-on:click="deleteEducation(education.id)" >
                                       <i class="fa fa-times"></i>
                                       </span>
                                    </div>
                                 </div>
                              </div>
                              <div class="cd-timeline__block js-cd-block">
                                 <div class="cd-timeline__img cd-timeline__img--four js-cd-img">
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!-- education  section end -->
                     </div>
                     <div class="tab-pane paginatediv" id="tab_default_3">
                        <!-- portfolio  section start -->
                        <button type="button" class="btn btn-w4y middle-btn" data-toggle="modal" v-on:click="loadportfolios(null)" data-target="#AddRefrence"> ADD REFERENCE</button>
                        <div class="row"  v-if="deleteMsgPortfolio==true">
                           <div class="form-group col-md-12">
                              <div id="successmsgUpdate" class="alert alert-success" >Deleted successfully!</div>
                           </div>
                        </div>
                        <div class="col-md-12" style="padding: 0px;" id="listNew">
                           <div class="col-md-4 childdiv" v-for="(portfolio, key) in portfolios.data">
                              <div class="wrapbox editBtnGroup" >
                                 <div class="imgbox" :style="{ backgroundImage: 'url(' + urlPrefix + 'uploads/portfolio/' +portfolio.portfolioimg + ')' }" data-toggle="modal" v-on:click="loadportfolios(portfolio)" data-target="#profile_page"></div>
                                 <h2 class="porttitle" v-on:click="redirectPortfolio(portfolio.portfoliourl)" ><% portfolio.title %></h2>
                                 <div class="text-right date">
                                    <a href="javascript:void(0);"><% portfolio.view_start_date %> - <% portfolio.view_completiondate %></a>
                                 </div>
                                 <div class="editBtnWraper">
                                   
                                    <span class="removeBtnIcon"  data-toggle="modal" data-target="#AddRefrence" v-on:click="loadportfolios(portfolio)" >
                                    <i class="fa fa-edit"></i>
                                    </span>
                                    <span class="removeBtnIcon"  v-on:click="deletePortfolio(portfolio.id)">
                                    <i class="fa fa-times"></i>
                                    </span>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-12 clearfix" style="padding-bottom: 75px;">
                           <div class="dataTables_info" id="profile_page_list_info" role="status" aria-live="polite">Showing <% portfolios.from %> to <% portfolios.to %> of <% portfolios.total %> entries</div>
                           <ul class="pagination w4uPagination">
                              <li  class="page-item" >
                                 <a class="page-link prev" :class="{'disabled':pagination.current_page == 1}" href="javascript:void(0)" v-on:click.prevent="changePage(pagination.current_page - 1)">
                                 <i class="fa fa-angle-left"></i>
                                 </a>
                              </li>
                              <li v-for="page in pageNumbers" class="page-item" >
                                 <a class="page-link"  href="javascript:void(0)" :class="{'active': page == pagination.current_page}" v-on:click.prevent="changePage(page)"><% page %></a>
                              </li>
                              <li  class="page-item "  >
                                 <a class="page-link next" :class="{'disabled':pagination.current_page >= pagination.last_page}" v-on:click.prevent="changePage(pagination.current_page + 1)" href="javascript:void(0)">
                                 <i class="fa fa-angle-right"></i>
                                 </a>
                              </li>
                           </ul>
                        </div>
                     </div>
                     <!-- portfolio  section end -->
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- delete modal -->
   <div id="deleteModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
         <!-- Modal content-->
         <div class="modal-content curvedModal">
            <div class="modal-header">
               <div data-dismiss="modal" class="closeModal">
                  <i class="fa fa-times"></i>
               </div>
               <h3 class="title">DELETE EMPLOYEE</h3>
            </div>
            <div class="modal-body">
               Are you sure you want to delete this employee?
            </div>
            <div class="modal-footer">
               <button type="button"   data-dismiss="modal"  class="btn btn-w4y middle-btn " v-on:click="deleteEmployee(employeeData.id)"  >YES </button>
            </div>
         </div>
      </div>
   </div>
   <!-- delete modal -->
   <!-- portfoilio modal start-->
   <div id="profile_page" class="modal fade w4yModal in" role="dialog">
      <div class="modal-dialog">
         <!-- Modal content-->
         <div class="modal-content">
            <div class="modal-body ">
               <div class="closeModal" data-dismiss="modal">
                  <i class="fa fa-times"></i>
               </div>
               <h3 class="title">DESCRIPTION</h3>
               <div :style="{ backgroundImage: 'url(' + urlPrefix + 'uploads/portfolio/' +userPortfolios.portfolio_portfolioimg + ')' }" class="portfolio-img-custom-responsive imageload">
                  <!-- <img src="./images/portfolio.jpg" class="img-responsive"> -->
               </div>
               <div class="imageloaddescription">
                  <p v-html="userPortfolios.portfolio_description"></p>
                  <p class="text-right" style="text-align: right;">
                     <b>$250</b>
                  </p>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- portfoilio modal end-->
   <!-- certificate modal start-->
   <div id="AddCertificate" class="modal fade"  role="dialog">
      <div class="modal-dialog">
         <!-- Modal content-->
         <div class="modal-content curvedModal">
            <div class="modal-header">
               <div class="closeModal" data-dismiss="modal">
                  <i class="fa fa-times"></i>
               </div>
               <h3 v-if="modalAction == 'add'" class="title">  Add Certificates</h3>
               <h3  v-else class="title">  Edit Certificates</h3>
            </div>
            <div class="modal-body">
               <form autocomplete="off" class="">
                  <div class="row"  v-if="successMsgCerctifiacte==true">
                     <div class="form-group col-md-12">
                        <div id="successmsgUpdate" class="alert alert-success" >Added successfully!</div>
                     </div>
                  </div>
                  <div class="row" v-if="updateMsgCerctifiacte==true">
                     <div class="form-group col-md-12">
                        <div id="successmsgUpdate" class="alert alert-success" >Updated successfully!</div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="form-group col-md-12">
                        <input type="text" class="form-control input-rounded" :class="status($v.usercertificates.certification_name)" v-on:blur="$v.usercertificates.certification_name.$touch()"   v-model="usercertificates.certification_name"  placeholder="Certificate Title">
                     </div>
                  </div>
                  <div class="row">
                     <div class="form-group col-md-12">
                        <textarea type="text" class="form-control input-rounded" :class="status($v.usercertificates.certification_desc)"   v-on:blur="$v.usercertificates.certification_desc.$touch()"   v-model="usercertificates.certification_desc" placeholder="Certificate Description"></textarea>
                     </div>
                  </div>
                  <div class="row">
                     <div class="form-group col-md-12">
                        <div  class="displayFlex">
                           <input type="text" readonly class="form-control input-rounded"  v-model="usercertificates.certification_file" id="certificateFile" style="margin-right: 20px;" placeholder="Certificate name">
                           <div><button type="button" v-on:click="$('#fileUpload').click()" class="btn btn-w4y middle-btn"> CHOOSE FILE</button></div>
                        </div>
                        <input type="file" id="fileUpload" v-show="false" accept="image/*" >
                     </div>
                  </div>
               </form>
            </div>
            <div class="modal-footer">
               <button type="button" v-if="modalAction == 'add'" :disabled="$v.usercertificates.certification_name.$error  || $v.usercertificates.certification_desc.$error" class="btn btn-w4y middle-btn" v-on:click="saveCertificate()" >SAVE</button>
               <button type="button" v-else class="btn btn-w4y middle-btn" v-on:click="updateCertificate()"  :disabled="$v.usercertificates.certification_name.$error  || $v.usercertificates.certification_desc.$error" class="btn btn-w4y middle-btn" v-on:click="saveCertificate()" >SAVE</button>
            </div>
         </div>
      </div>
   </div>
   <!-- certificate modal end-->
   <!-- education modals start-->
   <div  v-if="isCompany ==false" id="AddEducation" class="modal fade" role="dialog">
      <div class="modal-dialog">
         <!-- Modal content-->
         <div class="modal-content curvedModal">
            <div class="modal-header">
               <div class="closeModal" data-dismiss="modal">
                  <i class="fa fa-times"></i>
               </div>
               <h3 v-if="modalAction == 'add'" class="title">Add Education</h3>
               <h3 v-else class="title">Edit Education</h3>
            </div>
            <div class="modal-body">
               <form autocomplete="off" class="">
                  <div class="row"  v-if="successMsgEducation==true">
                     <div class="form-group col-md-12">
                        <div id="successmsgUpdate" class="alert alert-success" >Added successfully!</div>
                     </div>
                  </div>
                  <div class="row" v-if="updateMsgEducation==true">
                     <div class="form-group col-md-12">
                        <div id="successmsgUpdate" class="alert alert-success"  >Updated successfully!</div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="form-group col-md-12">
                        <input type="text" v-model="userEducations.education_title" v-on:blur="$v.userEducations.education_title.$touch()"  :class="status($v.userEducations.education_title)" class="form-control input-rounded" placeholder="Education Title">
                     </div>
                  </div>
                  <div class="row">
                     <div class="form-group col-md-12">
                        <input type="text"  v-model="userEducations.education_schoolname" v-on:blur="$v.userEducations.education_schoolname.$touch()" :class="status($v.userEducations.education_schoolname)" class="form-control input-rounded" placeholder="School Name">
                     </div>
                  </div>
                  <div class="row">
                     <div class="form-group col-md-12">
                        <textarea type="text" v-model="userEducations.education_desc" v-on:blur="$v.userEducations.education_desc.$touch()" :class="status($v.userEducations.education_desc)" class="form-control input-rounded" placeholder="Education Description"></textarea>
                     </div>
                  </div>
                  <div class="row">
                     <div class="form-group col-md-12">
                        <label class="displayFlex">
                           <input type="text" readonly class="form-control input-rounded" v-model="userEducations.education_start_date" v-on:blur="$v.userEducations.education_start_date.$touch()" :class="status($v.userEducations.education_start_date)"  id="FromDate1" placeholder="From Date">
                           <div><button type="button" class="btn btn-rounded b-color c-width FromDate1" >
                              <i class="fa fa-calendar"></i>
                              </button>
                           </div>
                        </label>
                     </div>
                  </div>
                  <div class="row">
                     <div class="form-group col-md-12">
                        <label class="displayFlex">
                           <input type="text" readonly v-model="userEducations.education_completiondate" v-on:blur="$v.userEducations.education_completiondate.$touch()" :class="status($v.userEducations.education_completiondate)" class="form-control input-rounded" id="untillDate1" placeholder="To Date">
                           <div><button type="button" class="btn btn-rounded b-color c-width untillDate1">
                              <i class="fa fa-calendar"></i>
                              </button>
                           </div>
                        </label>
                     </div>
                  </div>
               </form>
            </div>
            <div class="modal-footer">
               <button v-if="modalAction == 'add'" v-on:click="saveEduction()" :disabled="$v.userEducations.education_title.$error  || $v.userEducations.education_schoolname.$error || $v.userEducations.education_desc.$error || $v.userEducations.education_start_date.$error || $v.userEducations.education_completiondate.$error" type="button"  class="btn btn-w4y middle-btn">SAVE</button>
               <button v-else type="button" :disabled="$v.userEducations.education_title.$error  || $v.userEducations.education_schoolname.$error || $v.userEducations.education_desc.$error || $v.userEducations.education_start_date.$error || $v.userEducations.education_completiondate.$error " v-on:click="updateEducation()" class="btn btn-w4y middle-btn"> SAVE</button>
            </div>
         </div>
      </div>
   </div>
   <!-- education modals end-->
   <!-- add enployees start -->
   <div v-show="userData.role_id==adminRoleId ||  isCompany" id="AddEmployee" class="modal fade AddEmployee" role="dialog">
      <div class="modal-dialog">
         <!-- Modal content-->
         <div class="modal-content curvedModal">
            <div class="modal-header">
               <div class="closeModal" data-dismiss="modal">
                  <i class="fa fa-times"></i>
               </div>
               <h3 v-if="modalAction=='add'"  class="title">Add Employee</h3>
               <h3 v-if="modalAction=='edit'"  class="title">Edit Employee</h3>
            </div>
            <div class="modal-body">
               <form autocomplete="off" class="">
                  <div class="row"  v-if="successMsgEmployee==true">
                     <div class="form-group col-md-12">
                        <div id="successmsgUpdate" class="alert alert-success" >Added successfully!</div>
                     </div>
                  </div>
                  <div class="row" v-if="updateMsgEmployee==true">
                     <div class="form-group col-md-12">
                        <div id="successmsgUpdate" class="alert alert-success" >Updated successfully!</div>
                     </div>
                  </div>
                  <div class="row" v-if="loading==true">
                     <div class="form-group col-md-12">
                        <div id="successmsgUpdate" class="alert alert-info">Processing... </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="form-group col-md-6">
                        <input  type="text" v-model="employeeData.first_name" v-on:blur="$v.employeeData.first_name.$touch" :class="status($v.employeeData.first_name)" class="form-control input-rounded" placeholder="First name">
                        <input  type="hidden" v-model="employeeData.companyid" class="form-control input-rounded">
                        <input  type="hidden" v-model="employeeData.id" class="form-control input-rounded">
                     </div>
                     <div class="form-group col-md-6">
                        <input  type="text" v-model="employeeData.last_name" v-on:blur="$v.employeeData.last_name.$touch" :class="status($v.employeeData.last_name)" class="form-control input-rounded" placeholder="Last name">
                     </div>
                  </div>
                  <div class="row">
                     <div class="form-group col-md-12">
                        <input type="text" v-model="employeeData.email" v-on:blur="$v.employeeData.email.$touch" :class="status($v.employeeData.email)" class="form-control input-rounded" placeholder="Email Address">
                        <div class="error" v-if="$v.employeeData.email.$error">
                           <div class="error" v-if="!$v.employeeData.email.email">Please Enter valid Email.</div>
                        </div>
                        <div class="error" v-if="addEmailerror==true">Email already Exist</div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="form-group col-md-12">
                        <input type="text" v-model="employeeData.profile_title" v-on:blur="$v.employeeData.profile_title.$touch" :class="status($v.employeeData.profile_title)" class="form-control input-rounded" placeholder="Position">
                     </div>
                  </div>
                  <div class="row">
                     <div class="form-group col-md-12">
                        <div class="select-wrapper csdropdown mb-20" id="roles"  >
                           <select class="form-control input-rounded" v-model="employeeData.role_id">
                              <option value="null">Permissions</option>
                              <option v-for="userRole in userRoles" :value="userRole.id" ><% userRole.name %></option>
                           </select>
                        </div>
                        <smal><i>info: After Completion on E-Mail with a refistration link will be sent to your coleague.</i></smal>
                     </div>
                  </div>
               </form>
            </div>
            <div class="modal-footer">
               <button v-if="modalAction=='add'" v-on:click="addCompanyUser()" :disabled="loading==true || $v.employeeData.first_name.$error  || $v.employeeData.last_name.$error || $v.employeeData.email.$error || $v.employeeData.profile_title.$error" type="button" class="btn btn-w4y middle-btn">SAVE</button>
               <button v-if="modalAction=='edit'" v-on:click="updateEmployee()" :disabled="loading==true || $v.employeeData.first_name.$error  || $v.employeeData.last_name.$error || $v.employeeData.email.$error || $v.employeeData.profile_title.$error" type="button" class="btn btn-w4y middle-btn">SAVE</button>
            </div>
         </div>
      </div>
   </div>
   <!-- add enployees end -->
   <!-- refereance modal start -->
   <div id="AddRefrence" class="modal fade AddRefrence" role="dialog">
      <div class="modal-dialog">
         <!-- Modal content-->
         <div class="modal-content curvedModal">
            <div class="modal-header">
               <div class="closeModal" data-dismiss="modal">
                  <i class="fa fa-times"></i>
               </div>
               <h3 v-if="modalAction == 'add'" class="title">Add Reference</h3>
               <h3 v-else class="title">Edit Reference</h3>
            </div>
            <div class="modal-body">
               <form autocomplete="off" class="">
                  <div class="row"  v-if="successMsgPortfolio==true">
                     <div class="form-group col-md-12">
                        <div id="successmsgUpdate" class="alert alert-success" >Added successfully!</div>
                     </div>
                  </div>
                  <div class="row" v-if="updateMsgPortfolio==true">
                     <div class="form-group col-md-12">
                        <div id="successmsgUpdate" class="alert alert-success"  >Updated successfully!</div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-12 profiledetail">
                        <div :style="{ backgroundImage: 'url(' + urlPrefix + 'uploads/portfolio/' +userPortfolios.portfolio_portfolioimg + ')' }"  id="reference_file_holder" class="img-custom-responsive"> </div>
                        <div class="btn-section">
                           <label type="button" for="reference_file" class="btn btn-w4y btn-rounded " >
                           <i class="fa fa-edit"></i>
                           </label>
                           <input type="file" class="op_0" id="reference_file" >
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="form-group col-md-12">
                        <input type="hidden"  class="form-control input-rounded"  v-model="userPortfolios.portfolio_portfolioimg" id="portfolio_file" style="margin-right: 20px;" >
                        <input type="text" class="form-control input-rounded" v-model="userPortfolios.portfolio_title" v-on:blur="$v.userPortfolios.portfolio_title.$touch()"  :class="status($v.userPortfolios.portfolio_title)" placeholder="Project Title">
                     </div>
                  </div>
                  <div class="row">
                     <div class="form-group col-md-12">
                        <input type="text" class="form-control input-rounded"  v-model="userPortfolios.portfolio_url" v-on:blur="$v.userPortfolios.portfolio_url.$touch()"  :class="status($v.userPortfolios.portfolio_url)" placeholder="Project URL">
                        <div class="error" v-if="$v.userPortfolios.portfolio_url.$error">
                          <div class="error" v-if="!$v.userPortfolios.portfolio_url.url">Please Enter valid url (Url starts with 'http://' ).</div>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="form-group col-md-12">
                        <textarea type="text" class="form-control input-rounded" v-model="userPortfolios.portfolio_description" v-on:blur="$v.userPortfolios.portfolio_description.$touch()"  :class="status($v.userPortfolios.portfolio_description)" placeholder="Reference Description"></textarea>
                     </div>
                  </div>
                  <div class="row">
                     <div class="form-group col-md-12">
                        <label class="displayFlex">
                           <input type="text" readonly v-model="userPortfolios.portfolio_start_date" v-on:blur="$v.userPortfolios.portfolio_start_date.$touch()"  :class="status($v.userPortfolios.portfolio_start_date)" class="form-control input-rounded FromDate" id="FromDate" placeholder="From Date">
                           <div><button type="button" class="btn btn-rounded b-color c-width FromDate">
                              <i class="fa fa-calendar"></i>
                              </button>
                           </div>
                        </label>
                     </div>
                  </div>
                  <div class="row">
                     <div class="form-group col-md-12">
                        <label class="displayFlex">
                           <input type="text" v-model="userPortfolios.portfolio_completiondate" class="form-control input-rounded untillDate" v-on:blur="$v.userPortfolios.portfolio_completiondate.$touch()"  :class="status($v.userPortfolios.portfolio_completiondate)" id="untillDate" placeholder="From Date">
                           <div><button type="button" class="btn btn-rounded b-color c-width untillDate">
                              <i class="fa fa-calendar"></i>
                              </button>
                           </div>
                        </label>
                     </div>
                  </div>
               </form>
            </div>
            <div class="modal-footer">
               <button v-if="modalAction == 'add'"  type="button"  v-on:click="saveportfolio()"  :disabled="$v.userPortfolios.portfolio_title.$error || $v.userPortfolios.portfolio_url.$error || $v.userPortfolios.portfolio_url.$error || $v.userPortfolios.portfolio_description.$error || $v.userPortfolios.portfolio_start_date.$error || $v.userPortfolios.portfolio_completiondate.$error" class="btn btn-w4y middle-btn">SAVE</button>
               <button v-else  type="button"  v-on:click="updatePortfolio()" :disabled="$v.userPortfolios.portfolio_title.$error || $v.userPortfolios.portfolio_url.$error || $v.userPortfolios.portfolio_description.$error || $v.userPortfolios.portfolio_start_date.$error || $v.userPortfolios.portfolio_completiondate.$error" class="btn btn-w4y middle-btn" >SAVE</button>
            </div>
         </div>
      </div>
   </div>
   <input type="hidden" v-bind:value="this.userData.company" id="hiddenIsCompany"> 
 
</div>
<script src="{!! asset('js/selectize.min.js') !!}"></script>
<script src="{!! asset('js/chats/validators.min.js') !!}"></script>
<script src="{!! asset('js/chats/vuelidate.min.js') !!}"></script>
<script src="{!! asset('js/chats/vue.js') !!}"></script>
<script src="{!! asset('js/chats/vue-resource.js') !!}"></script>
<script src="{!! asset('js/editProfileVue.js') !!}"></script>
<script type="text/javascript">
  $(function () {
    $('[data-toggle="tooltip"]').tooltip();
    
    $('.wrapbox').click(function () {
      $('#post_project').modal('show');
    });
  
    $('#skillInput').keypress(function() {
      $('.filterWraper').show();
    });
  
    // Profile pic upload 
    $('#profile_file').change(function(){
      var input = this;
      var url = $(this).val();
      var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
      if (input.files && input.files[0]&& (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) 
      {
          var reader = new FileReader();
          reader.onload = function (e) {
            $('#profile_file_holder').css("background-image", "url("+e.target.result+")");
          }
        reader.readAsDataURL(input.files[0]);
      }
      else
      {
        //$('#img').attr('src', '/assets/no_preview.png');
      }
    });
  
    $('#fileUpload').change(function(){
  
      var input = this;
      var url = $(this).val();
      var fileName = input.files[0].name;
      $('#certificateFile').val(fileName);
    });
  
    //  reference pic upload
    $('#reference_file').change(function(){
        var input = this;
        var url = $(this).val();
        var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
        var fileName = input.files[0].name;
        $('#portfolio_file').val();
        if (input.files && input.files[0]&& (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) 
        {
            var reader = new FileReader();
            reader.onload = function (e) {
              $('#reference_file_holder').css("background-image", "url("+e.target.result+")");
            }
          reader.readAsDataURL(input.files[0]);
        }
        else
        {
          //$('#img').attr('src', '/assets/no_preview.png');
        }
    });
  
  })
  
function go_to_page(page_num) {
    start_from = page_num * show_per_page;
    end_on = start_from + show_per_page;
    if(page_num==0){
        $('.w4uPagination .page-link.prev').addClass('disabled');
        $('.w4uPagination .page-link.next').removeClass('disabled');
    }else if(page_num == number_of_pages-1){
        $('.w4uPagination .page-link.next').addClass('disabled');
        $('.w4uPagination .page-link.prev').removeClass('disabled');
    }
    var show_per_page = parseInt($('#show_per_page').val(), 0);

    $('.startFrom').text(start_from+1);
    $('.endTo').text(end_on);
    $('#list').children().css('display', 'none').slice(start_from, end_on).css('display', 'block');
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
@endsection