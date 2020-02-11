<?php
namespace App\Http\Controllers\Admin; //admin add
use App\Http\Requests;
use App\Http\Controllers\Controller;   // using controller class
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\OrderNote;
use App\Order;
use DB;
use URL;
use File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
class OrderNoteController extends Controller
{
   	
	 public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }
    public function index($id)
    {
        $order= Order::findOrFail($id);
        $order_notes= OrderNote::where('order_id',$id)->get();
		return view('admin.notes.order_note',compact('order','order_notes'));
    }
	
	public function order_note_store(Request $request)
    {
        $packageData = array(
            'heading'    => $request->input('heading'),
            'message'    => $request->input('message'),
            'order_id'    => $request->input('order_id'),
        );
        $rules = array(
            'heading'    =>   'required',
            'message'    =>   'required',
        )   ;
        $validator = Validator::make($packageData,$rules);
        if ($validator->fails()) {
            return redirect('admin/order-note/'.$request->input('order_id'))->withInput()->withErrors($validator);
        }else{
            $note = new OrderNote($packageData);
            $note->save();
        }
        // redirect
        Session::flash('success_message', 'Your Note has been added successfully');
        return redirect('admin/order-note/'.$request->input('order_id'));
    }
	
	public function destroy(Request $request)
	{
		$team = OrderNote::findOrFail($_POST['id']);
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