<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\User;
use App\masparameters;
use App\userskills;
use App\userseducertposrtfolio;
use Illuminate\Support\Facades\Mail;
use App\Mail\employeeRegister;
use App\Mail\inviteemployee;


class editProfileController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
     return view('profile.editprofile');
    }

    public function fetchUserDetail(){
        $userid=Auth::user()->id;
        $profileDetils=User::findorfail($userid);
        if($profileDetils->dob != null){
            $profileDetils->dob = date('d.m.Y',strtotime($profileDetils->dob));
        }else{
            $profileDetils->dob = null;
        }
      
        return response()->json($profileDetils, 200);
    }

    public function fetchCompanyUserdata(Request $request) {
        if($request->companyId){
            $userid=Auth::user()->id;
            $employees = User::leftjoin('masparameters','masparameters.id','=','role_id')->where('companyid','=',$request->companyId)->where('company','=',false)
            ->where('isactive','=',true)
            ->where('users_w4y.id','<>',$userid)
            ->select('users_w4y.id as empId','users_w4y.*','masparameters.*')
            ->get();
            return response()->json($employees,200);
        }else{
            return response()->json("error",401);
        }
    }

    public function loadUserRoles(){
        $roles=masparameters::where('paratype','role')->select('id','name')->get();
        return response()->json($roles,200);
    }

    public function addEmpolyee(Request $request)
    {
        if($request->employeeData){
            $employeeData = $request->employeeData;
            $data= $this->validate(request(),[
                'employeeData.*first_name'=>'required',
                'employeeData.*last_name'=>'required',
                'employeeData.*email'=>'required | email',
                'employeeData.*profile_title'=>'required',
            ],[
                'employeeData.*first_name.required'=>'Please Enter first name',
                'employeeData.*last_name.required'=>'Please Enter last name',
                'employeeData.*email.required'=>'Email Cannot be Empty',
                'employeeData.*email.email'=>'Enter Valid Email',
                'employeeData.*profile_title.required'=>'Please Enter Position',
            ]
            );
            $data['first_name'] =   $employeeData['first_name'];
            $data['last_name'] =   $employeeData['last_name'];
            $data['email'] =   $employeeData['email'];
            $data['profile_title'] =   $employeeData['profile_title'];
            $data['photo']='userdefault.jpg';
            if($employeeData['role_id']!=null){
                $data['role_id'] =   $employeeData['role_id'];
            }
            else{
                $data['role_id'] =  15;
            }
            $data['companyid'] =   $employeeData['companyid'];
            $data['isactive'] =  true;
            $data['reg_token'] =  str_random(25);

            $resp = User::create($data);
            $emaildata = new \stdClass();
            $emaildata->toname =  $data['first_name']." " .$data['last_name'] ;
            $emaildata->email = $data['email'];
            $emaildata->regtoken = $data['reg_token'];
            $emaildata->fromname = $request->cmpName;
            Mail::to($emaildata->email)->send(new employeeRegister($emaildata));

            return response()->json("Added Successfully",200);
        }else{
            return response()->json("error",401);
        }
    
    }

    public function updateEmployee(Request $request){
        $employeeData = $request->employeeData;
        $data['id'] =   $employeeData['id'];
        $data['first_name'] =   $employeeData['first_name'];
        $data['last_name'] =   $employeeData['last_name'];
        $data['email'] =   $employeeData['email'];
        $data['profile_title'] =   $employeeData['profile_title'];
        $data['role_id'] =   $employeeData['role_id'];
        $data['companyid'] =   $employeeData['companyid'];
        if($data['email']){
            $emailFiled= User::select('email')->where('email','=',$data['email'])->where('id','<>', $data['id']);
            $user = User::select('email')->where('id','=', $data['id']);
            $result = $user->first();
            $emailcount= $emailFiled->count();
            if(($emailcount == 0))
            {
                if($result['email']!=$data['email']){
                    $data['reg_token'] =  str_random(25);
                    $data['isactive'] = true;
                }
                if(User::whereId($data['id'])->update($data))
                {
                    if( $result['email']!=$data['email']){
                        $emaildata = new \stdClass();
                        $emaildata->toname =  $data['first_name']." " .$data['last_name'] ;
                        $emaildata->email = $data['email'];
                        $emaildata->regtoken = $data['reg_token'];
                        $emaildata->fromname = $request->cmpName;
                        Mail::to($emaildata->email)->send(new employeeRegister($emaildata));
                    }
                    return response()->json("Updated Successfully",200);
                }
            }
    
        }
        return response()->json("Failed",400);
    }

    public function deletEemployee( Request $request){
        if($request->empId){
        $data['isactive']=false;
        if(User::whereId($request->empId)->update($data))
            return response()->json("Deleted Successfully",200);
        }
    }

    
   public function inviteEmployee(Request $request)
    {
       if($request->has('employeeEmail')){
        $email=$request->employeeEmail;
        $cmpName=$request->cmpName;
        $emailFiled=user::where('email','=',$email)->where('password','<>',null)->where('isactive','=',1)->where('companyid','=',0)->select('email','first_name','last_name','id');
        $data= $emailFiled->first();
        $emailcount= $emailFiled->count();
        if(($emailcount == 1)){
            $tokenUpdate['reg_token']=str_random(25);
            User::whereId($data->id)->update($tokenUpdate);
            $emaildata = new \stdClass();
            $emaildata->toname =  $data->first_name." " .$data->last_name ;
            $emaildata->email = $data->email;
            $emaildata->regtoken = $tokenUpdate['reg_token'];
            $emaildata->cmpId = encrypt($request->cmpId); 
            $emaildata->fromname = $request->cmpName;
            $success=Mail::to($emaildata->email)->send(new inviteemployee($emaildata));
            if( $success){
                return response()->json("Sent Successfully",200);
            }else{
                return response()->json("Not sent",400); 
            }
           
        }else{
            return response()->json("Not sent",400);  
        }
       }
    }

    
    public function updateUserData( Request $request){
        $userid=Auth::user()->id;
        $userData = $request->user; 
        $data['title'] =   $userData['title'];
        $data['first_name'] =   $userData['first_name'];
        $data['last_name'] =   $userData['last_name'];
        $data['profile_description'] =   $userData['description'];
        $data['street'] =   $userData['street'];
        $data['zip'] =   $userData['zip'];
        $data['city'] =   $userData['city'];
        $data['country'] =   $userData['country'];
        if($userData['dob']){
            $data['dob'] =  date('Y-m-d', strtotime($userData['dob']));
        }else{
            $data['dob'] = null;
        }
          
        $data['phone_no'] =   $userData['phone'];
        $data['email'] =   $userData['email'];
        if($data['email']){ 
            $emailFiled= User::select('email')->where('email','=',$data['email'])->where('id','<>', $userid);
            $mailcount= $emailFiled->count();
            if(($mailcount == 0)){
                if(User::whereId($userid)->update($data))
                return response()->json("Updated Successfully",200);
            }
        }
        return response()->json("Failed",400);
    }


    public function updateCompanyUserData( Request $request){
        $userid=Auth::user()->id;
        $userData = $request->user; 
        $data['first_name'] =   $userData['first_name'];
        $data['last_name'] =   $userData['last_name'];
        $data['profile_description'] =   $userData['description'];
        $data['zip'] =   $userData['zip'];
        $data['city'] =   $userData['city'];
        $data['country'] =   $userData['country'];
        $data['phone_no'] =   $userData['phone'];
        $data['email'] =   $userData['email'];
       
        if($data['email']){
            $emailFiled= User::select('email')->where('email','=',$data['email'])->where('id','<>', $userid);
            $emailcount= $emailFiled->count();
            if(($emailcount == 0)) {
                if(User::whereId($userid)->update($data)) {
                    return response()->json("Updated Successfully",200);
                }
            }

        }
        return response()->json("Failed",400);

    }

    public function updateprofilePhoto(Request $request) {
        $userid=Auth::user()->id;
        $file=$request->file('uploadFile');
        $imageName = uniqid().time().'.'.$file->getClientOriginalExtension();
        $destinationPath = public_path('/uploads/avatar');
        $file->move($destinationPath, $imageName);
        $data['photo'] = $imageName;
        if(User::whereId($userid)->update($data))
            return response()->json("file uploaded",200); 
    }

    public function fetchAllSkills(){
        $allSkills=masparameters::where('paratype','=','skill')->select('id','name')->get();
        return response()->json($allSkills, 200);
    }

    public function addToSkills(Request $request){
        $userid=Auth::user()->id;
        foreach ($request->skillArr as $key => $value) {
           userskills::firstOrCreate(['skillid'=>$value, 'userid'=>$userid]);
        }
        return response()->json("Skills Added Successfully",200); 
    }

    public function fetchUserSkills(){
        $userid=Auth::user()->id;
        $allSkills=masparameters::leftjoin('userskills as us','skillid','=','masparameters.id')->where('paratype','=','skill')
                                 ->where('us.userid','=',$userid)->select('us.id','name')->get();
        return response()->json($allSkills, 200);
    }

    public function deleteSkill(Request $request) {
        userskills::whereId($request->skillId)->delete();
        return response()->json("Deleted",200); 
    }

    public function fetchUserCertificates() {
        $userid=Auth::user()->id;
        $usercertifiactes=userseducertposrtfolio::where('rectype','=','C')->where('userid','=', $userid)->orderby('id','desc')->get();
        return response()->json($usercertifiactes, 200);
    }

    public function adduserCertificates(Request $request) {
        $userid=Auth::user()->id;
        if($request->hasfile('uploadFile')) {
            $file=$request->file('uploadFile');
            $imageName = uniqid().time().'.'.$file->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/certificates');
            $file->move($destinationPath, $imageName);
            $data['portfolioimg'] = $imageName;
        } else {
            $data['portfolioimg'] = "";  
        }
       
        $data['title'] =   $request->certification_name;
        $data['description'] =   $request->certification_desc; 
        $data['userid'] =$userid;
        $data['rectype'] =   "C";
        userseducertposrtfolio::create($data);
        return response()->json("success",200);
    }

    public function updateUserCertificates( Request $request){
        $userid=Auth::user()->id;
        $id= $request->certification_id;
        if($request->file('uploadFile')) {
            $file=$request->file('uploadFile');
            $imageName = uniqid().time().'.'.$file->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/certificates');
            $file->move($destinationPath, $imageName);
            $data['portfolioimg'] = $imageName;
        } else {
            $data['portfolioimg'] =   $request->uploadFile;
        }
    
        $data['title'] =   $request->certification_name;
        $data['description'] =   $request->certification_desc; 
        if($data['title'] !="" ||  $data['description']!="") {
            if(userseducertposrtfolio::whereId($id)->update($data))
                return response()->json("Updated Successfully",200);
        }
    }

    public function deleteUserCertificate(Request $request)
    {
        userseducertposrtfolio::whereId($request->certId)->delete();
        return response()->json("Deleted",200); 
    }

    public function fetchUserEducations() {
        $userid=Auth::user()->id;
        $userEducations= userseducertposrtfolio::where('rectype','=','E')->where('userid','=', $userid)->select('*')->orderby('id','desc')->get()->toArray();
      
       foreach ($userEducations as $key => $value) {
            $userEducations[$key]['view_start_date'] = date('M Y',strtotime($userEducations[$key]['start_date']));
            $userEducations[$key]['view_completiondate']=  date('M Y',strtotime($userEducations[$key]['completiondate']));
            $userEducations[$key]['edit_start_date'] = date('d.m.Y',strtotime($userEducations[$key]['start_date']));
            $userEducations[$key]['edit_completiondate'] = date('d.m.Y',strtotime($userEducations[$key]['completiondate']));
       }
    
        return response()->json($userEducations, 200);
    }

    public function addUserEducations(Request $request) {
       if($request->userEducations){
            $userid=Auth::user()->id;
            $educationData = $request->userEducations;
            $data['title'] =   $educationData['education_title'];
            $data['sub_description'] =   $educationData['education_schoolname'];
            $data['description'] =   $educationData['education_desc'];
            $data['start_date'] = date('Y-m-d', strtotime( $educationData['education_start_date']));
            $data['completiondate'] = date('Y-m-d', strtotime( $educationData['education_completiondate']));
            $data['userid'] =$userid;
            $data['rectype'] =   "E";
            userseducertposrtfolio::create($data);
            return response()->json("Added Successfully",200);
        }

    }

    public function updateEducation( Request $request){
        $userid=Auth::user()->id;
        $educationData = $request->userEducation; 
        $id=$educationData['education_id'];
        $data['title'] =   $educationData['education_title'];
        $data['sub_description'] =   $educationData['education_schoolname'];
        $data['description'] =   $educationData['education_desc'];
        $data['start_date'] = date('Y-m-d', strtotime( $educationData['education_start_date']));
        $data['completiondate'] = date('Y-m-d', strtotime( $educationData['education_completiondate']));

        if(userseducertposrtfolio::whereId($id)->update($data))
            return response()->json("Updated Successfully",200);

    }

    public function deleteEducation(Request $request)
    {
        userseducertposrtfolio::whereId($request->eduId)->delete();
        return response()->json("Deleted",200); 
    }

    public function fetchUserPortfolios(Request $request)
    {
        $userid=Auth::user()->id;
        $userPortfolios= userseducertposrtfolio::where('rectype','=','P')->where('userid','=', $userid)->select('*')->orderby('id','desc')->paginate(9);
        foreach ($userPortfolios as $key => $value) {
            $userPortfolios[$key]['view_start_date'] = date('M Y',strtotime($userPortfolios[$key]['start_date']));
            $userPortfolios[$key]['view_completiondate']=  date('M Y',strtotime($userPortfolios[$key]['completiondate']));
            $userPortfolios[$key]['edit_start_date'] = date('d.m.Y',strtotime($userPortfolios[$key]['start_date']));
            $userPortfolios[$key]['edit_completiondate'] = date('d.m.Y',strtotime($userPortfolios[$key]['completiondate']));
       }
     
        return response()->json($userPortfolios, 200);
    }

    public function addPortfolio(Request $request) {
        $userid=Auth::user()->id;

        if($request->hasFile('uploadFile')){
            $file=$request->file('uploadFile');
            $imageName = uniqid().time().'.'.$file->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/portfolio');
            $file->move($destinationPath, $imageName);
            $data['portfolioimg'] = $imageName;
        }
        else{
            $data['portfolioimg'] ='portfolio.jpg';
        }

        $data['title'] =   $request->portfolio_title;
        $data['description'] =   $request->portfolio_description; 
        $data['portfoliourl'] =   $request->portfolio_url; 
        $data['start_date'] = date('Y-m-d', strtotime($request->portfolio_start_date));
        $data['completiondate'] = date('Y-m-d', strtotime($request->portfolio_completiondate));
        $data['userid'] =$userid;
        $data['rectype'] =   "P";
        userseducertposrtfolio::create($data);
        return response()->json("Added Successfully",200);
    }

    public function updatePortfolio(Request $request) {
        $userid=Auth::user()->id;
        $id= $request->portfolio_id;
        if($request->hasFile('uploadFile')){
            $file=$request->file('uploadFile');
            $imageName = uniqid().time().'.'.$file->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/portfolio');
            $file->move($destinationPath, $imageName);
            $data['portfolioimg'] = $imageName;
        }
        else{
            $data['portfolioimg'] =$request->uploadFile;
        }
        $data['title'] =   $request->portfolio_title;
        $data['description'] =   $request->portfolio_description; 
        $data['start_date'] = date('Y-m-d', strtotime($request->portfolio_start_date));
        $data['completiondate'] = date('Y-m-d', strtotime($request->portfolio_completiondate));
        $data['portfoliourl'] =   $request->portfolio_url; 
     
        if(userseducertposrtfolio::whereId($id)->update($data))
            return response()->json("Updated Successfully",200);
    }

    public function deletePortfolio(Request $request)
    {
        userseducertposrtfolio::whereId($request->portfolioId)->delete();
        return response()->json("Deleted",200); 
    }

    public function changePassword(Request $request){
       
      if($request->passwordNew){
        $userid=Auth::user()->id;
        $data['password'] = Hash::make($request->passwordNew);

        if(User::whereId($userid)->update($data))
            return response()->json("Updated Successfully",200);
      }
      return response()->json("Failed",500);
    }
    
    

    

}
