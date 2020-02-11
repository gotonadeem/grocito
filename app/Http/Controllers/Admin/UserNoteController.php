<?php
namespace App\Http\Controllers\Admin; //admin add
use App\Http\Requests;
use App\Http\Controllers\Controller;   // using controller class
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\GeneralSetting;
use App\PaymentGatwaySetting;
use App\UserNote;
use App\UserKyc;
use App\User;
use DB;
use URL;
use File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
class UserNoteController extends Controller
{
   	
	 public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }
    public function index($id)
    {
        $user= User::findOrFail($id);
        $user_kyc= UserKyc::where('user_id',$id)->first();
        $user_notes= UserNote::where('user_id',$id)->get();
		return view('admin.notes.user_note',compact('user','user_kyc','user_notes'));
    }
	
	public function user_note_store(Request $request)
    {
        $packageData = array(
            'heading'    => $request->input( 'heading'),
            'message'    => $request->input( 'message'),
            'user_id'    => $request->input('seller_id'),
        );
        $rules = array(
            'heading'    =>   'required',
            'message'    =>   'required',
        )   ;
        $validator = Validator::make($packageData,$rules);
        if ($validator->fails()) {
            return redirect('admin/user-note/'.$request->input('seller_id'))->withInput()->withErrors($validator);
        }else{
            $note = new UserNote($packageData);
            $note->save();
        }
        // redirect
        Session::flash('success_message', 'Your Note has been added successfully');
        return redirect('admin/user-note/'.$request->input('seller_id'));
    }
	
	public function destroy(Request $request)
	{
		$team = UserNote::findOrFail($_POST['id']);
        if(!empty($team->delete()))
        {
            Session::flash('success_message', 'Note has been deleted successfully!');
        }
        else {
            Session::flash('error_message', 'Unable to delete the Note');
        }
	}
 
}

?>