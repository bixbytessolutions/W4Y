<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\user;
use App\projectbid;
use Illuminate\Support\Collection;
use DB;
use Carbon;

class myprojectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
   
    public function myProjects()
    {
       return view('profile.myprojects',compact('openprojects'));
    }

    public function fetchOpenProjects(Request $request)
    {
        $columns = array( 
            0 =>'title', 
            1 =>'bidcount',
            2=> 'avgbidamt',
            3=> 'created_at',
            4=> 'id',
        );
        $searchTerm = $request->searchTermEmp;
        $totalRows =  projectbid::rightjoin('projects as p','p.id','=','projectid')->select('p.id', 'p.created_at')
                                  ->where('owner_id','=',Auth::user()->id)->where('p.hired_freelancerid','=',NULL)
                                  ->where('p.isopen_forbid','=',true);
        if($searchTerm){
            $totalRows= $totalRows->where('p.title','LIKE', '%'.$searchTerm.'%');
        }
        $totalRows=$totalRows->groupby('p.id')->get();
        $totalData = $totalRows ->count();
        $totalFiltered = $totalData; 
        $limit = $request->input('length');
        $start = $request->input('start');
        $openprojects = projectbid::rightjoin('projects as p','p.id','=','projectid')->select(DB::raw('count(projectid) as "bidcount"'),
        DB::raw('avg(bidamt) as "avgbidamt"'),'p.title','p.id', 'p.created_at')->where('owner_id','=',Auth::user()->id)->where('p.hired_freelancerid','=',NULL)->where('p.isopen_forbid','=',true)->offset($start);
        
        if($searchTerm){
            $openprojects=$openprojects->where('p.title','LIKE', '%'.$searchTerm.'%');
        }
        $openprojects= $openprojects->groupby('p.id')->limit($limit)->orderBy('p.created_at', 'DESC')->get();

        $data = array();
        if(!empty($openprojects))
        {
            foreach ($openprojects as $openproject)
            {
                $show =   $openproject->id;
                $edit =   $openproject->id;
                $bidenddate = strtotime($openproject->created_at->addDays(4));
                $bidenddate=  date('j.m.Y',$bidenddate); 
                $nestedData['title'] = $openproject->title;
                $nestedData['bidcount'] = $openproject->bidcount;
                $nestedData['avgbidamt'] = $openproject->avgbidamt?$openproject->avgbidamt.' &dollar;':'0 &dollar;';
                $nestedData['created_at'] = $bidenddate;
                $nestedData['options'] = "<div class='csdropdown'><select><option value='volvo'>Select</option><option value='saab'>Edit</option><option value='opel'>Delete</option><option value='audi'>Upgrade</option></select></div>";
                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
            );

        return json_encode($json_data); 

    }

    public function fetchWorkinProgressprojects(Request $request)
    {
        $columns = array( 
            0 =>'title', 
            1 =>'first_name',
            2=> 'bidamt',
            3=> 'deadline_date',
            4=> 'id',
        );
        $searchTerm = $request->searchTermEmp;
        $totalRows =   projectbid::rightjoin('projects as p','p.id','=','projectid')->leftjoin('users_w4y as u','u.id','=','p.hired_freelancerid')->select('p.id', 'p.created_at')
        ->where('owner_id','=',Auth::user()->id)->where('p.isopen_forbid','=',false)->where('p.status','=',NULL);
        if($searchTerm){
            $totalRows= $totalRows->whereRaw("(p.title LIKE '%".$searchTerm."%' OR u.first_name LIKE '%".$searchTerm."%' OR u.last_name LIKE '%".$searchTerm."%' )");
        }
        $totalRows = $totalRows->groupby('p.id')->get();
        $totalData = $totalRows ->count();
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
     
        $workpressprojects =  projectbid::rightjoin('projects as p','p.id','=','projectid')->leftjoin('users_w4y as u','u.id','=','p.hired_freelancerid')->select('p.created_at','u.first_name','u.last_name','p.title','p.deadline_date','bidamt','p.id', 'p.created_at')
        ->where('owner_id','=',Auth::user()->id)->where('p.isopen_forbid','=',false)->where('p.status','=',NULL)->offset($start);
        if($searchTerm){
            $workpressprojects = $workpressprojects->whereRaw("(p.title LIKE '%".$searchTerm."%' OR u.first_name LIKE '%".$searchTerm."%' OR u.last_name LIKE '%".$searchTerm."%' )");
        }
        $workpressprojects = $workpressprojects
                            ->groupby('p.id')
                            ->limit($limit)
                            ->orderBy('p.created_at', 'DESC')
                            ->get();
        $data = array();
        if(!empty($workpressprojects))
        {
            foreach ($workpressprojects as $workpressproject)
            {
                $show =   $workpressproject->id;
                $edit =   $workpressproject->id;
                $bidenddate = strtotime($workpressproject->deadline_date);
                $bidenddate=  date('j.m.Y',$bidenddate); 
                $nestedData['title'] = $workpressproject->title;
                $nestedData['first_name'] = $workpressproject->first_name.' '.$workpressproject->last_name;
                $nestedData['bidamt'] = $workpressproject->bidamt?$workpressproject->bidamt.' &dollar;':'0 &dollar;';
                $nestedData['deadline_date'] = $bidenddate;
                $nestedData['options'] = "<div class='csdropdown'><select><option value='volvo'>Select</option><option value='saab'>Edit</option><option value='opel'>Delete</option><option value='audi'>Upgrade</option><option >Add Milestone</option></select></div>";
                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
            );

        return json_encode($json_data); 

    }

    public function fetchpastProjects(Request $request)
    {
        $columns = array( 
            0 =>'title', 
            1 =>'first_name',
            2=> 'bidamt',
            3=> 'projectfinishdate',
            4=> 'id',
        );
        $searchTerm = $request->searchTermEmp;
        $totalRows =  projectbid::rightjoin('projects as p','p.id','=','projectid')->leftjoin('users_w4y as u','u.id','=','p.hired_freelancerid')
                                  ->select('p.id', 'p.created_at')
                                  ->where('owner_id','=',Auth::user()->id)->where('p.isopen_forbid','=',false)
                                  ->where('p.status','<>',NULL);
        if($searchTerm){
            $totalRows= $totalRows->whereRaw("(p.title LIKE '%".$searchTerm."%' OR u.first_name LIKE '%".$searchTerm."%' OR u.last_name LIKE '%".$searchTerm."%' )");
        }
        $totalRows= $totalRows->groupby('p.id')->get();
        $totalData = $totalRows ->count();
        $totalFiltered = $totalData; 
        $limit = $request->input('length');
        $start = $request->input('start');
        $pastprojects =  projectbid::rightjoin('projects as p','p.id','=','projectid')->leftjoin('users_w4y as u','u.id','=','p.hired_freelancerid')->select('p.created_at','u.first_name','u.last_name','p.title','p.deadline_date','bidamt','projectfinishdate','p.status','p.id', 'p.created_at')
                                    ->where('owner_id','=',Auth::user()->id)->where('p.isopen_forbid','=',false)->where('p.status','<>',NULL)->offset($start);
        
        if($searchTerm){
            $pastprojects= $pastprojects->whereRaw("(p.title LIKE '%".$searchTerm."%' OR u.first_name LIKE '%".$searchTerm."%' OR u.last_name LIKE '%".$searchTerm."%' )");
        }
        $pastprojects= $pastprojects->groupby('p.id')
                                    ->limit($limit)
                                    ->orderBy('p.created_at', 'DESC')
                                    ->get();
        
        $data = array();
        if(!empty($pastprojects))
        {
            foreach ($pastprojects as $pastproject)
            {
                $show =   $pastproject->id;
                $edit =   $pastproject->id;
            
                $bidenddate = strtotime($pastproject->projectfinishdate);
            
                $bidenddate=  date('j.m.Y',$bidenddate); 
                $nestedData['title'] = $pastproject->title;
                $nestedData['first_name'] = $pastproject->first_name.' '.$pastproject->last_name;
                $nestedData['bidamt'] = $pastproject->bidamt?$pastproject->bidamt.' &dollar;':'0 &dollar;';
                $nestedData['projectfinishdate'] = $bidenddate;
                $nestedData['options'] = $pastproject->status? $pastproject->status:'';
                $data[] = $nestedData;

            }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
            );

        return json_encode($json_data);
    }

    public function fetchactivebids(Request $request)
    {
        $userid=Auth::user()->id;
        $columns = array( 
            0 =>'title', 
            1 =>'bidcnt',
            2=> 'bidamt',
            3=> 'avgamt',
            4=> 'created_at',
            5=> 'id',
        );
        $searchTerm = $request->searchTermFlncr;
        $addQry = '';
        if($searchTerm){
            $addQry= " AND p.title LIKE '%".$searchTerm."%' ";
        }
        $totalRows =   DB::select(DB::raw("SELECT pr.*, a.projectid,a.cnt,a.avgamt FROM 
                        ( SELECT projectid, avg(bidamt) as avgamt ,count(projectid)as cnt FROM `projectbids` GROUP BY projectid ) a 
                         LEFT JOIN projectbids pr ON a.projectid = pr.projectid  left join projects as p on a.projectid=p.id  
                         WHERE bidderid =".$userid." and p.isopen_forbid =1".$addQry.""));

        $totalRows = new Collection($totalRows);
        
        $totalData = $totalRows ->count();
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
     
        $activebids =   DB::select(DB::raw("SELECT pr.*, p.owner_id, a.projectid,a.cnt as bidcnt,p.title, a.avgamt avgamt FROM 
                        ( SELECT projectid, avg(bidamt) as avgamt ,count(projectid)as cnt FROM `projectbids` GROUP BY projectid ) a 
                         LEFT JOIN projectbids pr ON a.projectid = pr.projectid  left join projects as p on a.projectid=p.id  
                         WHERE bidderid =".$userid." and p.isopen_forbid =1 " .$addQry." group by p.id order by p.created_at desc limit ".$limit." offset ".$start."" ));
         
        $activebids = new Collection($activebids);
      
        $data = array();
        if(!empty($activebids))
        {
            foreach ($activebids as $activebid)
            {
                $show =   $activebid->id;
                $edit =   $activebid->id;
                $ownerId= $activebid->owner_id;
                $path=url('/chat/init-message?to_user_id='.$ownerId);
                $sendMsgUrl = "window.location.href='".$path."'";
                $bidenddate = strtotime($activebid->created_at);
                $bidenddate = strtotime("+4 day", $bidenddate);
                $bidenddate=  date('j.m.Y',$bidenddate); 
                $nestedData['title'] = $activebid->title;
                $nestedData['bidcnt'] = $activebid->bidcnt;
                $nestedData['bidamt'] = $activebid->bidamt?$activebid->bidamt.' &dollar;':'0 &dollar;';
                $nestedData['avgamt'] = $activebid->avgamt?$activebid->avgamt.' &dollar;':'0 &dollar;';
                $nestedData['created_at'] = $bidenddate;
                $nestedData['options'] = "<div class='csdropdown'><select><option value='volvo'>Select</option> <option value='saab'>Retract Bid</option><option value='opel'>Edit Bid</option><option value='audi' onclick=".$sendMsgUrl." >Send Message</option></select></div>";
                $data[] = $nestedData;

            }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
            );

        return json_encode($json_data); 
    }

    public function workProgressFreelancer(Request $request)
    {
 
        $userid=Auth::user()->id;
        $columns = array( 
            0 =>'title', 
            1 =>'first_name',
            2=> 'bidamt',
            3=> 'deadline_date',
            4=> 'id',
        );
        $searchTerm = $request->searchTermFlncr;
        $addQry = '';
        if($searchTerm){
            $addQry= "(p.title LIKE '%".$searchTerm."%' OR u.first_name LIKE '%".$searchTerm."%' OR u.last_name LIKE '%".$searchTerm."%' )";
        }
        $totalRows =   projectbid::rightjoin('projects as p','p.id','=','projectid')->leftjoin('users_w4y as u','u.id','=','p.owner_id')->select('p.id', 'p.created_at')
            ->where('hired_freelancerid','=',Auth::user()->id)->where('p.isopen_forbid','=',false)->where('p.status','=',NULL);
        if($addQry){
        $totalRows=$totalRows->whereRaw($addQry);
        }

        $totalRows=$totalRows->groupby('p.id')->get();
        $totalData = $totalRows ->count();
        $totalFiltered = $totalData; 
        $limit = $request->input('length');
        $start = $request->input('start');
    
        $workpressprojectsFreelancers =  projectbid::rightjoin('projects as p','p.id','=','projectid')->leftjoin('users_w4y as u','u.id','=','p.owner_id')
                                                    ->select('p.created_at','u.first_name','u.last_name','p.title','p.deadline_date','p.owner_id','bidamt','p.id', 'p.created_at')
                                                    ->where('hired_freelancerid','=',Auth::user()->id)
                                                    ->where('p.isopen_forbid','=',false)->where('p.status','=',NULL)->offset($start);
   
        if($addQry){
             $workpressprojectsFreelancers=$workpressprojectsFreelancers->whereRaw($addQry);
        }

        $workpressprojectsFreelancers=$workpressprojectsFreelancers->groupby('p.id')
            ->limit($limit)
            ->orderBy('p.created_at', 'DESC')
            ->get();

  
        $data = array();
        if(!empty($workpressprojectsFreelancers))
        {
            foreach ($workpressprojectsFreelancers as $workpressprojectsFreelancer)
            {
                $show =   $workpressprojectsFreelancer->id;
                $edit =   $workpressprojectsFreelancer->id;
                $ownerId= $workpressprojectsFreelancer->owner_id;  
                $path=url('/chat/init-message?to_user_id='.$ownerId);
                $sendMsgUrl = "window.location.href='".$path."'";
                $bidenddate = strtotime($workpressprojectsFreelancer->deadline_date);
            
                $bidenddate=  date('j.m.Y',$bidenddate); 
                $nestedData['title'] = $workpressprojectsFreelancer->title;
                $nestedData['first_name'] = $workpressprojectsFreelancer->first_name.' '.$workpressprojectsFreelancer->last_name;
                $nestedData['bidamt'] = $workpressprojectsFreelancer->bidamt?$workpressprojectsFreelancer->bidamt.' &dollar;':'0 &dollar;';
            
                $nestedData['deadline_date'] = $bidenddate;
                $nestedData['options'] = "<div class='csdropdown'><select><option value='volvo'>Select</option><option value='saab' onclick=".$sendMsgUrl." >Send Message</option></select></div>";
                $data[] = $nestedData;

            }
        }

   
    $json_data = array(
        "draw"            => intval($request->input('draw')),  
        "recordsTotal"    => intval($totalData),  
        "recordsFiltered" => intval($totalFiltered), 
        "data"            => $data   
        );
    return json_encode($json_data); 

    }

    public function pastProjectsFreelancer(Request $request)
    {
        $userid=Auth::user()->id;
        $columns = array( 
            0 =>'title', 
            1 =>'first_name',
            2=> 'bidamt',
            3=> 'projectfinishdate',
            4=> 'id',
        );
        $searchTerm = $request->searchTermFlncr;
        $addQry = '';
        if($searchTerm){
            $addQry= "(p.title LIKE '%".$searchTerm."%' OR u.first_name LIKE '%".$searchTerm."%' OR u.last_name LIKE '%".$searchTerm."%' )";
        }
        $totalRows =   projectbid::rightjoin('projects as p','p.id','=','projectid')->leftjoin('users_w4y as u','u.id','=','p.owner_id')->select('p.id', 'p.created_at')
                        ->where('hired_freelancerid','=',Auth::user()->id)
                        ->where('p.isopen_forbid','=',false)->where('p.status','<>',NULL);
                    
        if($addQry){
            $totalRows=$totalRows->whereRaw($addQry); 
        }
        $totalRows = $totalRows->groupby('p.id')->get();
        $totalData = $totalRows ->count();
        $totalFiltered = $totalData; 
        $limit = $request->input('length');
        $start = $request->input('start');
        $pastprojectsfreelancers =  projectbid::rightjoin('projects as p','p.id','=','projectid')->leftjoin('users_w4y as u','u.id','=','p.owner_id')->select('p.hiredon','p.updated_at','projectfinishdate','p.status','p.created_at','u.first_name','u.last_name','p.title','p.deadline_date','p.owner_id','bidamt','p.id', 'p.created_at')
                                    ->where('hired_freelancerid','=',Auth::user()->id)->where('p.isopen_forbid','=',false)->where('p.status','<>',NULL)->groupby('p.id')->offset($start);
                                
        if($addQry){
            $pastprojectsfreelancers=$pastprojectsfreelancers->whereRaw($addQry); 
        }
    
        $pastprojectsfreelancers=$pastprojectsfreelancers->groupby('p.id')
        ->limit($limit)
        ->orderBy('p.created_at', 'DESC')
        ->get();
        $data = array();
        if(!empty($pastprojectsfreelancers))
            {
                foreach ($pastprojectsfreelancers as $pastprojectsfreelancer)
                {
                    $show =   $pastprojectsfreelancer->id;
                    $edit =   $pastprojectsfreelancer->id;
                    $t1 =strtotime($pastprojectsfreelancer->hiredon);
                    $t2 = strtotime($pastprojectsfreelancer->updated_at);
                    $diff_seconds  = $t2 - $t1;
                    $diff_weeks    = floor($diff_seconds/604800);
                    $diff_days     = floor($diff_seconds/86400);
                        if($diff_weeks < 1)
                        {
                            $days= $diff_days.' days';
                        }
                        else if($diff_weeks == 1){
                            $days= $diff_weeks.' week';
                        }
                        else{
                            $days= $diff_weeks.' weeks';
                        }
                    $nestedData['title'] = $pastprojectsfreelancer->title;
                    $nestedData['first_name'] = $pastprojectsfreelancer->first_name.' '.$pastprojectsfreelancer->last_name;
                    $nestedData['bidamt'] = $pastprojectsfreelancer->bidamt?$pastprojectsfreelancer->bidamt.' &dollar;':'0 &dollar;';
                    $nestedData['projectfinishdate'] = $days;
                    $nestedData['options'] = $pastprojectsfreelancer->status? $pastprojectsfreelancer->status:'';
                    $data[] = $nestedData;

                }
            }

        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
            );

        return json_encode($json_data); 
    }


}
