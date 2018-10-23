<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\chat;
use App\User;
use Carbon\Carbon;
use Pusher\Pusher;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function initMessage(Request $request){
        $date = Carbon::now('Asia/Kolkata');
        $user=Auth::user();
        $toId =  $request->to_user_id;
        if($user->id == $toId)
            return  redirect()->route('/chat'); 
        $dataList = chat::whereRaw('(toId='.$user->id.' AND fromId='.$toId.') OR (fromId='.$user->id.' AND toId='.$toId.')')->get()->toArray();
        if(sizeof($dataList) <= 0){
            $data['fromid'] =  $user->id;
            $data['message'] = "staticspy";
            $data['msgon'] = $date;
            $data['msgtype'] = "text";
            $data['toid'] =  $toId;
            $data['time_zone'] = $date;
            $msgId = chat::create($data);
            if(!($msgId instanceof chat))
                return back()->withInput(); 
        }
           
        $myMsg = DB::table('chats as c')
                    ->whereRaw('(c.toId='.$user->id.' AND c.fromId='.$toId.') AND c.seen = 0')
                    ->update(['c.seen' => true]);

        return redirect()->route('chat_control', ['to-id' => $toId]);       
    }

    public function index(){
        $id =   Auth::user()->id;
        $users =  User::where('id','<>',$id)->get();
        $profile = User::find($id);
        return view('chat.chat',compact('users'));
    }

     public function getOtherUsers(){
        $id =   Auth::user()->id;
        $users =  DB::select(DB::raw('SELECT tid, u.id, ch.deleteforid, u.photo,ses.last_activity, ch.fromid, ch.toid,u.first_name, u.last_name,ch.message, 
                ch.created_at ChatDate,b.NewMsg newmsgcount FROM 
                ( SELECT MAX(a.ID) AS tid,ChatID, MAX(a.created_at) ChatDate,a.seen NewMsg FROM 
                ( SELECT MAX(s.ID) ID,s.toID UID, s.fromID ChatID, Max(s.created_at)created_at,
                (select count( s1.seen) from chats s1 where s1.fromID=s.fromID and s1.seen=0) seen FROM chats s WHERE toid='.$id.' 
                GROUP BY ChatID UNION SELECT MAX(ID) ID, fromid UID, toID ChatID, max(created_at)created_at,0 seen 
                FROM chats WHERE fromid='.$id.' GROUP BY ChatID ) a 
                GROUP BY a.ChatID ORDER BY ChatDate DESC ) b
                LEFT JOIN users_w4y u ON b.ChatId = u.id 
                INNER JOIN chats ch ON ch.id = b.tid left join sessions ses on u.id=ses.user_id order by ChatDate DESC'));

        return response()->json($users, 200);
     }

     public function sendMessage(Request $request){
         $date = Carbon::now('Asia/Kolkata');
         $user=Auth::user();
         $message= $request->message_text;
         $toId =  $request->to_user_id;

         $messageType =  $request->message_type;
         $data['fromid'] =  $user->id;
         $data['message'] = $message;
         $data['msgon'] = $date;
         $data['msgtype'] = $messageType;
         $data['toid'] =  $toId;
         $data['time_zone'] = $date;
         $msgId = chat::create($data);
         $latestMessage = DB::table('chats as c')
                        ->leftJoin('users_w4y as u', 'c.fromid', '=', 'u.id')
                        ->where('c.id',$msgId->id)
                        ->select("c.id","c.fromid","c.toid","c.message","u.photo","c.msgon","c.msgtype","c.file_url","c.seen","c.deleteforid","c.created_at","c.time_zone")
                        ->groupBy('c.created_at')
                        ->get()->toArray();
        $chanel='notify_'.$data['fromid'].'_to_'.$toId;
        $value = config('broadcasting.connections.pusher');
        $pusher = new Pusher( $value['key'], $value['secret'],$value['app_id'], array('cluster' => $value['options']['cluster']) );
        $test = $pusher->trigger('reload_dom_'.$toId, 'reload-event', ["success"]);
        if($pusher->trigger($chanel, 'notify-event', $latestMessage[0]))
            return response()->json(array('chat'=> $latestMessage[0]), 200);
        return response()->json("Message sending failed",404); 
     }

     public function uploadFile(Request $request){
        $date = Carbon::now('Asia/Kolkata');
        $user=Auth::user();
        $toId =  $request->to_user_id;
        $file=$request->file('uploadFile');
        $imageName = uniqid().time().'.'.$file->getClientOriginalExtension();
        $destinationPath = public_path('/files');
        $file->move($destinationPath, $imageName);
        $data['fromid'] =  $user->id;
        $data['message'] =$file->getClientOriginalName();
        $data['msgon'] = $date;
        $data['msgtype'] = "file";
        $data['file_url'] = "files/".$imageName;
        $data['file_size'] = $file->getClientSize();
        $data['toid'] =  $toId;
        $data['time_zone'] = $date;
        $msgId = chat::create($data);
        $latestMessage = DB::table('chats as c')
                       ->leftJoin('users_w4y as u', 'c.fromid', '=', 'u.id')
                       ->where('c.id',$msgId->id)
                       ->select("c.id","c.fromid","c.toid","c.message","u.photo","c.msgon","c.msgtype","c.file_url","c.file_size","c.seen","c.deleteforid","c.created_at","c.time_zone")
                       ->groupBy('c.created_at')
                       ->get()->toArray();
        $chanel='notify_'.$data['fromid'].'_to_'.$toId;
     
        $value = config('broadcasting.connections.pusher');
        $pusher = new Pusher( $value['key'], $value['secret'],$value['app_id'], array('cluster' => $value['options']['cluster']) );
        if($pusher->trigger($chanel, 'notify-event', $latestMessage[0]))
           return response()->json(array('chat'=> $latestMessage[0]), 200);
        return response()->json("Message sending failed",404); 
     }

     public function typingEvent(Request $request){
        $user=Auth::user();
        $toId =  $request->to_user_id;

        $chanel='notify_'.$user->id.'_to_'.$toId;

        $value = config('broadcasting.connections.pusher');
        $pusher = new Pusher( $value['key'], $value['secret'],$value['app_id'], array('cluster' => $value['options']['cluster']) );
        if($pusher->trigger($chanel, 'typing-event', ['status'=>$request->status, 'chanel'=>$chanel]))
           return response()->json(array('chat'=> $request->status), 200);
       // return $this->pusher->get_channel_info($chanel );
       return response()->json($this->pusher,404); 
     }

 

     public function fetchMessages(Request $request, $id)
      {
        $toId = $id;
        $idUser = Auth::user()->id;
        $currentPage=$request->page_no;
        if($currentPage == null)
            $currentPage=1;
        $currentScroll=$currentPage * 12;
        $myMsg = DB::table('chats as c')
                    ->whereRaw('(c.toId='.$idUser.' AND c.fromId='.$toId.') AND c.seen = 0')
                    ->update(['c.seen' => true]);
         $chats = DB::table('chats as c')
                     ->leftJoin('users_w4y as u', 'c.fromid', '=', 'u.id')
                     ->whereRaw('(deleteforid IS NULL OR deleteforid!='.$idUser.') AND ((c.toId='.$idUser.' AND c.fromId='.$toId.') OR (c.fromId='.$idUser.' AND c.toId='.$toId.'))')
                     ->select("u.first_name", "u.last_name","c.id","u.photo","c.fromid","c.toid","c.message","c.file_size","c.msgon","c.msgtype","c.file_url","c.seen","c.deleteforid",DB::raw("DATE_FORMAT(c.msgon,'%d-%m-%Y' ) as datecroll"), 'c.created_at' ,"c.time_zone")
                     ->groupBy('c.created_at')
                     ->orderBy('c.id','DESC')
                     ->paginate($currentScroll)->toArray();
        return response()->json($chats, 200);
      }

      public function deleteMessage(Request $request){
        $user = Auth::user();
        $toId  =  $request->to_user_id;
        DB::table('chats') 
            ->whereRaw('((toId='.$toId.' AND fromid= '.$user->id.') OR (toId='.$user->id.' AND fromid= '.$toId.')) AND  deleteforid IS NULL') 
            ->update(['deleteforid' => $user->id]);

        DB::table('chats') 
            ->whereRaw('((toId='.$toId.' AND fromid= '.$user->id.') OR (toId='.$user->id.' AND fromid= '.$toId.')) AND deleteforid ='.$toId)
            ->delete(); 
        $data['success']  = true;
        return response()->json($data, 200);
      }

      
      public function fetchmenulisMessages(){
        $id = Auth::user()->id;
        $userdata['firstname'] = Auth::user()->first_name;
        $userdata['lastname'] = Auth::user()->last_name;
        $usersmessages =  DB::select(DB::raw('SELECT u.id, ch.deleteforid, u.photo,ses.last_activity, ch.fromid, ch.toid,u.first_name, u.last_name,ch.message, 
                                    ch.created_at ChatDate FROM 
                                    ( SELECT MAX(s.ID) ID,s.toID UID, s.fromID ChatID, Max(s.created_at)created_at FROM chats s WHERE toid='.$id.' and seen=0
                                    GROUP BY ChatID ) a 
                                    LEFT JOIN users_w4y u ON a.ChatId = u.id 
                                    INNER JOIN chats ch ON ch.id =a.id left join sessions ses on u.id=ses.user_id order by ChatDate DESC limit 3'));
        if($usersmessages)
            return response()->json($usersmessages, 200);
        return response()->json($userdata, 401);
     }
 
}
