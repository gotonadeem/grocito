<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Auth;
use URL;
use App\Order;
use App\OrderMeta;
use App\UserSender;
use Response;
use App\Payment;
use App\Cart;
use Helper;
use DB;
use DNS1D;
use DNS2D;
use PDF;
use Session;
use App\User;
use App\Manifest;
class ManifestController extends Controller
{
    public function __construct()
    {     
	parent::__construct();
	}

    public function index()
    {
		 if(Auth::user())
		 {
		 }
	}
	
	  function download_manifest(Request $request)
		{
			if(Auth::user())
		 {
		    if(count($request->input('order_id'))>0)
			{				
			$data['order_ids']=$request->input('order_id');
			$manifest['seller_id']= Auth::user()->id;
			$manifest['order_id']= json_encode($data['order_ids']);
			$data1=Helper::get_product_for_manifest($data['order_ids'][0]);
			$manifest['service']=$data1['shipped_by'];
			$obj= new Manifest($manifest);
			$obj->save();
			$pdf = PDF::loadView('seller.order.pdf.manifest', $data);
			$label= date("d-m-Y")."_".time();
		     return $pdf->download($label.'.pdf');
			}
			else
			{
				 Session::flash('error_message', 'Please Select Order');
				return redirect("seller/ready-to-ship");
			}
		 }
		 else
		 {
			 return redirect('/');
		 }
		}
}