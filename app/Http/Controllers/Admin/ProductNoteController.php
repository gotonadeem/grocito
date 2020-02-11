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
use App\ProductNote;
use App\Product;
use DB;
use URL;
use File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
class ProductNoteController extends Controller
{
   	
	 public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }
    public function index($id)
    {
        $product= Product::findOrFail($id);
        $product_notes= ProductNote::where('product_id',$id)->get();
		return view('admin.notes.product_note',compact('product','product_notes'));
    }
	
	public function product_note_store(Request $request)
    {
        $packageData = array(
            'heading'    => $request->input( 'heading'),
            'message'    => $request->input( 'message'),
            'product_id'    => $request->input('product_id'),
        );
        $rules = array(
            'heading'    =>   'required',
            'message'    =>   'required',
        )   ;
        $validator = Validator::make($packageData,$rules);
        if ($validator->fails()) {
            return redirect('admin/product-note/'.$request->input('product_id'))->withInput()->withErrors($validator);
        }else{
            $note = new ProductNote($packageData);
            $note->save();
        }
        // redirect
        Session::flash('success_message', 'Your Note has been added successfully');
        return redirect('admin/product-note/'.$request->input('product_id'));
    }
	
	public function destroy(Request $request)
	{
		$team = ProductNote::findOrFail($_POST['id']);
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