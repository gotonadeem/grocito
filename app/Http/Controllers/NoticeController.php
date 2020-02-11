<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Auth;
use App\Notice;
use App\UserNotice;
use DB;
class NoticeController extends Controller
{
    public function __construct()
    {		parent::__construct();
	
    }
    public function index($slug='')
    {		
	 if($slug=="count")
	 {
		$data=Notice::select(DB::raw('group_concat(id) as notice_id'))->first();
        $check=UserNotice::where('user_id',Auth::user()->id)->get()->count();
		if($check>0)
		{
			 $obj = UserNotice::where('user_id',Auth::user()->id)->first();
			 $data1['notice_id']=$data->notice_id;
             $obj->fill($data1)->save();
		}
		else
		{ 
			 $data1['notice_id']=$data->notice_id;
			 $data1['user_id']= Auth::user()->id;
			 $obj = new UserNotice($data1);
             $obj->save();
		}
	 }
	  $read_count= UserNotice::where('user_id',Auth::user()	->id)->first(); 
	  $data=Notice::orderBy('id', 'DESC')->where('status', 1)->get();
        return view('seller.notice.index',compact('data','read_count'));
		
    }
}