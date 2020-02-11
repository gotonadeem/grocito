<?php
namespace App\Http\Controllers\Admin; 
use App\Http\Requests;
use App\Http\Controllers\Controller;   
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\GeneralSetting;
use App\PaymentGatwaySetting;
use App\Package;
use App\Delivery;
use DB;
use URL;
use File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
class DeliveryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }
	
    public function standard()
    {
	  $data= Delivery::where('type','standard')->first();
      return view('admin.delivery.standard',compact('data'));
    }
	
    public function store($id,Request $request)
    {

        $packageData = array(
            'type'          =>$request->input('type'),
            'min_order'     =>$request->input('min_order'),
            'radius'        =>$request->input('radius'),
            'radius_charge' =>$request->input('radius_charge'),
            'out_of_radius_charge'=>$request->input('out_of_radius_charge'),
        );


        $rules = array(
            'type'       =>'required',
            'min_order'       =>'required',
            'radius'          =>'required',
            'radius_charge'   =>'required',
            'out_of_radius_charge'   =>'required',
        );

        $validator = Validator::make($packageData,$rules);
        if ($validator->fails()) {
            return redirect('admin/delivery-charge/'.$id)->withInput()->withErrors($validator);
        }else{
			 $dta=Delivery::where('city_id',$id)->where('type',$packageData['type'])->first();
			 if($dta)
			 {
				 unset($packageData['type']);

				 DB::table('delivery_charges')->where('id',$dta->id)->update($packageData);
			 }
			 else
			 {
                 $packageData['city_id'] = $id;
              $coupon = new Delivery($packageData);
              $coupon->save();
			 }
        }

        // redirect
        Session::flash('success_message', 'Delivery Charge has been updated successfully');
        return redirect('admin/delivery-charge/'.$id);
    }
	
	function get_data(Request $request)
	{
		$dta=Delivery::where('city_id',$request->city_id)->where('type',$request->id)->first();
        
            echo json_encode(array('radius'=>$dta?$dta->radius:'','radius_charge'=>$dta?$dta->radius_charge:'','out_of_radius_charge'=>$dta?$dta->out_of_radius_charge:'','min_order'=>$dta?$dta->min_order:''));



	}
	


}