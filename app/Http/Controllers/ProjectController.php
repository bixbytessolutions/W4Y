<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\project;
use App\projectbid;
use App\masparameters;
use App\projectrequirement;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Auth;
use DB;
use App\Mail\SendMailable;
use Illuminate\Support\Collection;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = DB::table('projects as p')->leftJoin('projectbids as pb', 'p.id', '=', 'pb.projectid')
                ->select(DB::raw("(count(pb.projectid)) as 'bidcount'"),"p.title","p.description","p.deadline_date","p.budget","p.id")
                ->groupBy("p.title","p.description","p.deadline_date","p.budget")
                ->orderBy('p.id', 'desc')
                ->get();
        $projectrequirements = masparameters::where('paratype', '=', 'projectrequirement')->get();
        $budgetmax = (int)project::max('budget');
        $budgetmin = (int)project::min('budget');

        return view('projects.index', compact('projects','projectrequirements','budgetmax','budgetmin'));
    } 

  
    public function create()
    {
        $projectrequirements = masparameters::where('paratype', '=', 'projectrequirement')->get();
        $view = view('projects.create', [ 'projectrequirements' => $projectrequirements ])->render();
        return response()->json(['html'=>$view]);
    }

    public function store(Request $request)
    {
        $project = $this->validate(request(),
         [
            'title' => 'required',
            'description'=> 'required',
            'budget' => 'required | numeric | min:0',
            'deadline_date'=> 'required',
            'owner_id' => 'required',
            'requirements' => 'required'
        ], 
        [ 
            'title.required' => 'A Project title field is required.',
            'description.required' => 'The Project description field is required.', 
            'requirements.required' => 'Requirements attribute is required.',
            'budget.required' => 'The budget attribute is required.',
            'budget.numeric'=>'Budget Must be numeric.',
            'budget.min'=>'Budget Cannot be negative.' ,
            'deadline_date.required' => 'The Estimate project deadline is required.' 
        ]);

        $q1=implode(', ', $request->requirements);
        $project['isopen_forbid']=true;
        $project['requirements']=$q1;
        $project['deadline_date']=date('Y-m-d H:i:s', strtotime($request->deadline_date));
        $projectid =project::create($project)->id;
        foreach( $request->requirements as $reqid)
        {
            projectrequirement::create(['projectid'=>$projectid,'requirementid'=> $reqid]);
        }
            
        $data = new \stdClass();
        $data->name =  Auth::user()->first_name;
        $data->email = Auth::user()->email;
        $message="Hello ".$data->name.", Your Project has been Posted Successfully";
        $data->message = $message;
            
        Mail::to($data->email)->send(new SendMailable($data));

        if($projectid){
            return response()->json('Project has been Posted Successfully', 200);
        }else{
            return response()->json('Project Not Posted!', 404); 
        }
        
    }

    public function getprojdetail(Request $request)
    {
        $id=$request->projid;
        $projectsdetails = project::find($id);
        $projectsdetail=$projectsdetails->description;
        return response()->json(array('projectDescription'=> $projectsdetail), 200);
    }



    public function show($id)
    {
        $userid="";
        if(Auth::check())
        {
            $userid=Auth::user()->id;
        }
        
        $project = project::leftjoin('users_w4y as u','projects.owner_id','=','u.id')
                 ->select('u.first_name','u.last_name','u.country','u.city','projects.id','projects.title','projects.description','projects.budget','projects.deadline_date','projects.owner_id','projects.created_at')
                 ->find($id);
        $startDate = $project->created_at;
        $now = Carbon::now();
        $end = date('M d, Y H:i:s',strtotime($project->created_at->addDays(4)));
        $project->end=$end;
        $reviews = DB::table('reviews as r')
                ->join('projects  as p', 'p.id', '=', 'r.projectid')
                ->join('users_w4y  as u', 'u.id', '=', 'r.reviewedbyid')
                ->select(DB::raw("((r.rateskills + r.rateavailability + r.rateCommunication + r.ratequality + r.ratedeadlines + r.ratecooperation)/6) as 'avgrate'"))
                ->where('r.reviewedforid','=',$project->owner_id)->get()->toarray();
    
        $totalavg=totalAverageReviews($reviews);

        $bidders =  DB::select(DB::raw("SELECT avg(a.avgrate) as avgtotal ,a.city as city,a.country as country,a.created_at, a.projectfinishdate, a.profile_description as descriptionProfile,a.first_name as firstName,a.last_name as lastName,a.bidamt as bidAmt,a.photo as photo from
                                        (SELECT u.id,u.first_name,projectbids.created_at,projectfinishdate, u.photo,u.last_name,u.city,u.country,projectbids.projectid,projectbids.bidamt,reviews.reviewedforid ,((rateskills + rateavailability + rateCommunication + ratequality + ratedeadlines + ratecooperation)/6) as 'avgrate', profile_description FROM users_w4y u 
                                        LEFT JOIN projectbids ON projectbids.bidderid = u.id
                                        LEFT JOIN reviews ON reviews.reviewedforid = u.id 
                                        WHERE projectbids.projectid = ".$id.") a GROUP BY a.id"));
                                        $bidders = new Collection($bidders);
        $bidders->avgBidamt =$bidders->avg('bidAmt');
       
        foreach($bidders as $bidder) {
            $createdBid = Carbon::parse($bidder->created_at);
            $endBid=Carbon::parse($bidder->projectfinishdate);
            $length = $endBid->diffInDays($createdBid);
            $bidder->takendays=$length;
        }
 
        return view('projects.show' , compact('project','totalavg','reviews','bidders','userid') );
    }

    public function projectfilter(Request $request)
    {
        $start = (int)$request->budjetmin;
        $end = (int)$request->budjetmax;
        $reuirementsvalues =  $request->myCheckboxes;
        $project = DB::table('projects as p')->leftJoin('projectbids as pb', 'p.id', '=', 'pb.projectid')
                ->join('projectrequirements as pr','p.id', '=' ,'pr.projectid')
                ->select(DB::raw("(count(pb.projectid)) as 'bidcount'"),"p.title","p.description","p.deadline_date","p.budget","p.id")
                ->distinct();

            if ($request->has('myCheckboxes'))
            {
            $project->Where(function ($query) use($reuirementsvalues) {
                for ($i = 0; $i < count($reuirementsvalues ); $i++){
                        $query->orwhere('pr.requirementid', '=', $reuirementsvalues[$i]);
                }  
            });
            }
            
        $projects=$project->whereBetween('p.budget',[$start,$end])->groupBy("p.title","p.description","p.deadline_date","p.budget","pr.requirementid","pb.projectid")->orderBy('p.id', 'desc')->get();
        $view = view('projects.ajaxfilterview', [ 'projects' => $projects ])->render();
        return response()->json(['html'=>$view]);
    }

     

    
}
