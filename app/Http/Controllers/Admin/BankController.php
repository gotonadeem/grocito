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
use App\BankDetails;
use DB;
use URL;
use File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class BankController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }


    public function bank_details()
    {
        $setting = BankDetails::first();
        return view("admin.bank_details.bank")->with(['setting' => $setting]);
    }
    
    public function update($id,Request $request)
    {
        //print_r($data = $request->all());die;
        // validate
        $bank = BankDetails::find($id);
       // print_r($bank);die;
        $validator = Validator::make($request->all(),
            [
                'account_no' => '',
                'bank_name' => '',
                'ifsc' => '',
                'account_holder_name' => ''

            ] );

        if ($validator->fails())
        {
            return redirect('admin/bank/bank-details/'.$id)->withInput()->withErrors($validator);
        }
        else
        {
            //$data=Input::all();
            //$bank->fill($data)->save();


            $data=array();
            $obj=new BankDetails();
            $obj->account_no=$request->input('account_no');
            $obj->bank_name=$request->input('bank_name');
            $obj->ifsc=$request->input('ifsc');
            $obj->account_holder_name=$request->input('account_holder_name');
            $setting = BankDetails::first();
            if($bank) {
                $data = $request->all();
               // $update_data = BankDetails::find($bank->id)->fill($data);
               // $update_data->update();
                $update_data = BankDetails::where('id',$bank->id)->update(['account_no'=>$obj->account_no,'bank_name'=>$obj->bank_name,'ifsc'=>$obj->ifsc,'account_holder_name'=>$obj->account_holder_name]);
                // redirect
                Session::flash('success_message', 'Bank Details Successfully updated');
                return redirect('admin/bank/bank-details/'.$id);
            }


        }
    }

}
?>