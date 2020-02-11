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
use App\BtcAddress;
use DB;
use URL;
use File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class BtcAddressController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }


    public function btc_address()
    {
        $btc_address = BtcAddress::first();
        return view("admin.btc_address.btc_address")->with(['btc_address' => $btc_address]);
    }
    
    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(),
            [
                'btc_address' => 'required',
            ], [
                'btc_address' => 'Btc Address Required',
            ]);

        if ($validator->fails())
        {
            return redirect('/admin/btc-address')->withInput()->withErrors($validator);
        }
        else
        {
            $data=array();
            $obj=new BtcAddress();
            $obj->btc_address=$request->input('btc_address');
            $btc_address = BtcAddress::first();
            if($btc_address) {
                $data =$request->all();
                $update_data = BtcAddress::where('id',$btc_address->id)->update(['btc_address'=>$obj->btc_address]);
            }
            else
            {
                $obj->save();
            }
            Session::flash('success_message', 'BTC Address has been updated successfully');
            return redirect('/admin/btc-address');
        }
        
    }

}
?>