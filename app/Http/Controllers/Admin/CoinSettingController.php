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
use App\CoinRate;
use DB;
use URL;
use File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class CoinSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }


    public function coin_rate()
    {
        $setting = CoinRate::first();
        return view("admin.coin_setting.coin_rate")->with(['setting' => $setting]);
    }
    
    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(),
            [
                'coin' => 'required|integer',
            ], [
                'coin' => 'Coin Price required',
            ]);

        if ($validator->fails())
        {
            return redirect('/admin/coin-setting/coin-rate')->withInput()->withErrors($validator);
        }
        else
        {
            $data=array();
            $obj=new CoinRate();
            $obj->coin=$request->input('coin');
            $setting = CoinRate::first();
            if($setting) {
                $data =$request->all();
                $update_data = CoinRate::where('id',$setting->id)->update(['coin'=>$obj->coin]);
            }
            else
            {
                $obj->save();
            }
            Session::flash('success_message', 'Coin Price has been updated successfully');
            return redirect('/admin/coin-setting/coin-rate');
        }
        
    }

}
?>