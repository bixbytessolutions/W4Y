<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\masparameters;
use App\userseducertposrtfolio;
use Carbon\Carbon;
use App\reviews;
use App\userskills;
use Illuminate\Support\Facades\Mail;
use DB;
use Auth;
use App\Mail\hirerequest;

class profileController extends Controller
{
    //
    public function show($id)
    {
        $dt = Carbon::now('Asia/Kolkata');
        $profile = User::leftjoin('company_w4y','company_w4y.id','=','users_w4y.companyid')->select('company_w4y.id','company_w4y.name','users_w4y.*')->findorfail($id);
        $employees = [];
      
        if($profile->company == true)
        {
            $employees = User::where('companyid','=',$profile->companyid)->where('company','=',false)->get();
            $employees->avghourlyrate= $employees->avg('hourly_rate');

            foreach ($employees as $employee) {
                $employeeIds[] = $employee->id;
            }

            $empIds =implode(',',$employeeIds);
            $employeeIds = array_prepend($employeeIds,$profile->id);  
            $cerficateEmpIds = implode(',',$employeeIds);
            $skills = masparameters::leftjoin('userskills', 'masparameters.id', '=', 'userskills.skillid')->leftjoin('users_w4y', 'userskills.userid', '=', 'users_w4y.id')->whereIn('users_w4y.id',[$empIds])->groupby('masparameters.name')->get();
            $certificates = userseducertposrtfolio::leftjoin('users_w4y', 'userseducertposrtfolios.userid', '=', 'users_w4y.id')->select('userseducertposrtfolios.*', 'users_w4y.id')->whereRaw('userseducertposrtfolios.userid IN('.$cerficateEmpIds.')')->where('userseducertposrtfolios.rectype','=','C')->orderby('userseducertposrtfolios.id','desc')->get();
            $educations = userseducertposrtfolio::join('users_w4y', 'userseducertposrtfolios.userid', '=', 'users_w4y.id')->select('userseducertposrtfolios.*', 'users_w4y.id')->whereRaw('userseducertposrtfolios.userid IN('.$cerficateEmpIds.')')->where('userseducertposrtfolios.rectype','=','E')->orderby('userseducertposrtfolios.id','desc')->get();
            $portfolios = userseducertposrtfolio::join('users_w4y', 'userseducertposrtfolios.userid', '=', 'users_w4y.id')->select('userseducertposrtfolios.*', 'users_w4y.id')->whereRaw('userseducertposrtfolios.userid IN('.$cerficateEmpIds.')')->where('userseducertposrtfolios.rectype','=','P')->orderby('userseducertposrtfolios.id','desc')->get();
            $reviews = DB::table('reviews as r')->join('projects  as p', 'p.id', '=', 'r.projectid')->join('users_w4y  as u', 'u.id', '=', 'r.reviewedbyid')->select(DB::raw("((r.rateskills + r.rateavailability + r.rateCommunication + r.ratequality + r.ratedeadlines + r.ratecooperation)/6) as 'avgrate'"),"r.reviewedbyid","r.projectid","p.title","u.first_name","p.budget","r.reviewedon","r.reviewedforid","r.id","r.rateskills","r.rateavailability","r.rateCommunication","r.ratequality","r.ratedeadlines","r.ratecooperation")->whereRaw('r.reviewedforid IN('.$empIds.')')->get()->toarray();
            $totalavg=totalAverageReviews($reviews);
          
        } else {
            $skills = masparameters::join('userskills', 'masparameters.id', '=', 'userskills.skillid')->join('users_w4y', 'userskills.userid', '=', 'users_w4y.id')->where('userskills.userid','=',$profile->id)->get();
            $certificates = userseducertposrtfolio::join('users_w4y', 'userseducertposrtfolios.userid', '=', 'users_w4y.id')->select('userseducertposrtfolios.*', 'users_w4y.id')->where('userseducertposrtfolios.userid','=',$profile->id)->where('userseducertposrtfolios.rectype','=','C')->orderby('userseducertposrtfolios.id','desc')->get();
            $educations = userseducertposrtfolio::join('users_w4y', 'userseducertposrtfolios.userid', '=', 'users_w4y.id')->select('userseducertposrtfolios.*', 'users_w4y.id')->where('userseducertposrtfolios.userid','=',$profile->id)->where('userseducertposrtfolios.rectype','=','E')->orderby('userseducertposrtfolios.id','desc')->get();
            $portfolios = userseducertposrtfolio::join('users_w4y', 'userseducertposrtfolios.userid', '=', 'users_w4y.id')->select('userseducertposrtfolios.*', 'users_w4y.id')->where('userseducertposrtfolios.userid','=',$profile->id)->where('userseducertposrtfolios.rectype','=','P')->orderby('userseducertposrtfolios.id','desc')->get();
            $reviews = DB::table('reviews as r')->join('projects  as p', 'p.id', '=', 'r.projectid')->join('users_w4y  as u', 'u.id', '=', 'r.reviewedbyid')->select(DB::raw("((r.rateskills + r.rateavailability + r.rateCommunication + r.ratequality + r.ratedeadlines + r.ratecooperation)/6) as 'avgrate'"),"r.reviewedbyid","r.projectid","p.title","u.first_name","p.budget","r.reviewedon","r.reviewedforid","r.id","r.rateskills","r.rateavailability","r.rateCommunication","r.ratequality","r.ratedeadlines","r.ratecooperation")->where('r.reviewedforid','=',$profile->id)->get()->toarray();
            $totalavg=totalAverageReviews($reviews);
        }

        return view('profile.show',compact('profile','skills','certificates','educations','portfolios','dt','reviews','totalavg','employees'));
    }


    public function getreviewdetail(Request $request)
    {
        $id=$request->reviewid;
        $reviewdetails = reviews::find($id);
        return response()->json(array('reviewdetails'=> $reviewdetails), 200);
    }

    public function hireUsEmail(Request $request)
     {
        if(!(Auth::check())) {
            $session = $request->session();
            $session->put('bid_redirect_url', '/profile/'.$request->toID);
            return response()->json('User not authenticated', 401); 
        }

        $data = new \stdClass();
        $profile = User::leftjoin('company_w4y','company_w4y.id','=','users_w4y.companyid')->select('company_w4y.id','company_w4y.name','users_w4y.*')->findorfail($request->toID);
        $employees = [];
        $data->toname='';
        if($profile->company == true)
        {
            $employees = User::where('companyid','=',$profile->companyid)->where('company','=',false)->get();
            foreach ($employees as $employee) {
                $employeeEmailId[] = $employee->email;
            }
            $message="Your Company has been hired";
        } else {
            $employeeEmailId[]=$profile->email;
            $data->toname=$profile->first_name;
            $message="You Have been hired";
        }
        $data->fromname=Auth::user()->first_name;
        $data->email= $employeeEmailId;
        $data->message = $message;
        Mail::to($data->email)->send(new hirerequest($data));
        return response()->json('sent details', 200);      
     }

}
