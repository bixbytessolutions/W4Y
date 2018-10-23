<?php

namespace App\Http\Controllers;
use App\project;
use App\projectbid;
use Illuminate\Http\Request;
use Auth;
use DB;

class projectbidController extends Controller
{
    //

    public function store(Request $request)
    {
        if(!(\Auth::check()))
        {
            $session = $request->session();
            $session->put('bid_redirect_url', '/projects/'.$request->projectid);
            return response()->json('User not authenticated', 401); 
        }
        $projectbid = $this->validate(request(),
                ['bidamt' => 'required | numeric | min:0',
                'projectfinishdate' => 'required',
                'biddermsg'=> 'required'],
                [ 'bidamt.required' => 'Bid amount is required', 
                'biddermsg.required' => 'Please Describe your skills', 
                'projectfinishdate.required' => 'Finish date is required',
                'bidamt.numeric'=>'Bid amount Must be Number',
                'bidamt.min'=>'Bid amount Cannot be negative']);

        $projectbid['projectid']=$request->projectid;
        $projectbid['bidderid']=Auth::user()->id;
        $projectbid['projectfinishdate']=date('Y-m-d H:i:s', strtotime($request->projectfinishdate));
        $checkDuplicate  =  projectbid::where('projectid','=',$request->projectid)->where('bidderid','=',Auth::user()->id)->count();
        $bidproject="";
        
        if($checkDuplicate>0){
            return response()->json('You can not bid again!', 404); 
        }

        $bidproject=projectbid::create($projectbid);

        if($bidproject){
            return response()->json('Project bidded Successfully', 200);
        }else{
            return response()->json('Project Not Bidded!', 404); 
        }

    }
}
