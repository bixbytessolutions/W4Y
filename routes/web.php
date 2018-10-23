<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->middleware('home_page');

Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'admin'], function () {
  Route::get('/login', 'AdminAuth\LoginController@showLoginForm')->name('admin.login');
  Route::post('/login', 'AdminAuth\LoginController@login');
  Route::post('/logout', 'AdminAuth\LoginController@logout')->name('admin.logout');
/*
  Route::get('/register', 'AdminAuth\RegisterController@showRegistrationForm')->name('register');
  Route::post('/register', 'AdminAuth\RegisterController@register');*/

  Route::post('/password/email', 'AdminAuth\ForgotPasswordController@sendResetLinkEmail')->name('admin.password.request');
  Route::post('/password/reset', 'AdminAuth\ResetPasswordController@reset')->name('admin.password.email');
  Route::get('/password/reset', 'AdminAuth\ForgotPasswordController@showLinkRequestForm')->name('admin.password.reset');
  Route::get('/password/reset/{token}', 'AdminAuth\ResetPasswordController@showResetForm');
});

/*projects*/
Route::resource('projects','ProjectController');
Route::post('projects/store','ProjectController@store');
Route::post('/getprojdetail', 'ProjectController@getprojdetail');
Route::post('/projectfilter', 'ProjectController@projectfilter');

/*bid project*/

Route::post('/projects/bidproject', 'projectbidController@store');



/*profile*/
Route::get('/profile/{id}', 'profileController@show')->where('id', '[0-9]+');
Route::post('/hireUsEmail', 'profileController@hireUsEmail')->middleware('home_page');
Route::post('/getreviewdetail', 'profileController@getreviewdetail');
Route::get('/my-projects', 'myprojectController@myProjects');
Route::post('/fetchOpenProjects', 'myprojectController@fetchOpenProjects');
Route::post('/fetchWorkinProgressprojects', 'myprojectController@fetchWorkinProgressprojects');
Route::post('/fetchpastProjects', 'myprojectController@fetchpastProjects');
Route::post('/fetchactivebids', 'myprojectController@fetchactivebids');
Route::post('/workProgressFreelancer', 'myprojectController@workProgressFreelancer');
Route::post('/pastProjectsFreelancer', 'myprojectController@pastProjectsFreelancer');

/*edit profile */

Route::get('/edit-profile', 'editProfileController@index');
Route::get('/fetchUserDetail', 'editProfileController@fetchUserDetail');
Route::post('/updateUserData', 'editProfileController@updateUserData');
Route::post('/updateprofilePhoto', 'editProfileController@updateprofilePhoto');
Route::get('/fetchAllSkills', 'editProfileController@fetchAllSkills');
Route::get('/fetchuserskill', 'editProfileController@fetchUserSkills');
Route::post('/addtoskill', 'editProfileController@addToSkills');
Route::post('/deleteskill', 'editProfileController@deleteSkill');
Route::get('/fetchusercertificates', 'editProfileController@fetchUserCertificates');
Route::post('/addusercertificates', 'editProfileController@addUserCertificates');
Route::post('/updatecertificates', 'editProfileController@updateUserCertificates');
Route::post('/deletescertificate', 'editProfileController@deleteUserCertificate');
Route::get('/fetchusereducations', 'editProfileController@fetchUserEducations');
Route::post('/addusereducations', 'editProfileController@addUserEducations');
Route::post('/updateeducation', 'editProfileController@updateEducation');
Route::post('/deleteeducation', 'editProfileController@deleteEducation');
Route::get('/fetchuserportfolios', 'editProfileController@fetchUserPortfolios');
Route::post('/addportfolio', 'editProfileController@addPortfolio');
Route::post('/updateportfolio', 'editProfileController@updatePortfolio');
Route::post('/deleteeportfolio', 'editProfileController@deletePortfolio');
Route::post('/changepassword', 'editProfileController@changePassword');

/*company edit profile*/

Route::post('/updatecompanyuserdata', 'editProfileController@updateCompanyUserData');
Route::post('/fetchcompanyuserdata', 'editProfileController@fetchCompanyUserdata');
Route::get('/loaduserroles', 'editProfileController@loadUserRoles');
Route::post('/addempolyee', 'editProfileController@addEmpolyee');
Route::get('/employeeuserregister/{regtoken}', 'Auth\RegisterController@completeRegister');
Route::post('/updateregister', 'Auth\RegisterController@updateRegister');
Route::post('/updateemployee', 'editProfileController@updateEmployee');
Route::post('/deleteemployee', 'editProfileController@deletEemployee');
Route::post('/inviteemployee', 'editProfileController@inviteEmployee');
Route::get('/innviteupdate/{regtoken}/{cmpId}', 'Auth\RegisterController@innviteUpdate');



/*for chat */

Route::get('/chat', 'ChatController@index')->name('chat_control');

Route::post('/storemsg', 'ChatController@storeMsg');
Route::get('/chat/users', 'ChatController@getOtherUsers');
Route::post('/chat/send-message', 'ChatController@sendMessage');
Route::post('/chat/send-file', 'ChatController@uploadFile');
Route::get('/chat/init-message', 'ChatController@initMessage');
Route::post('/chat/typing-event', 'ChatController@typingEvent');
Route::get('/chat/message/{id}', 'ChatController@fetchMessages');
Route::post('/chat/delete-message', 'ChatController@deleteMessage');
Route::get('/chat/menulist-messages', 'ChatController@fetchmenulisMessages');









