<?php
namespace App\Http\Controllers\Admin; //admin add
use App\Http\Requests;
use App\Http\Controllers\Controller;   // using controller class
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\User;
use App\UserKyc;
use App\AddCoin;
use App\LevelIncome;
use App\RewardBonus;
use App\ActivationWallet;
use App\WorkingWallet;
use App\Transfer;
use App\UserNetwork;
use DB;
use URL;
use Excel;
use File;
use Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
class ApiController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }
    
    public function api_user_account(Request $request)
    {
      print_r($request); die;

        $user = array(
            'name'     => $request->input( 'name'),
            'email'    => $request->input('email'),
            'mobile'    => $request->input('mobile'),
            'password'    => $request->input('password'),
            'city'    => $request->input('operating_city'),
            
        );
        $rules = array(
            'name'     =>   'required',
            'email'    =>   'required',
            'mobile'    =>   'required',
            'password'    =>   'required',
            'city'    =>   'required',
            );
        $validator = Validator::make($user,$rules);
        if ($validator->fails()) {
            
             return Response::json(array(
                'status_code' => 0,
                'message' => 'validation error',
                'error_message'=>$validator->errors()->first(),
                'data'=>array()

            ), 200);
            
        }else{
             $data=Input::all();
            $data['password']=Hash::make($data['password']);
            $data['api_token'] = str_random(60);
            $user = new User($data);
            $user->save();

            return Response::json(array(
                'status_code' => 1,
                'message' => 'successfully saved',
                'error_message'=>array(),
                'data'=>User::find($user->id)

            ), 200);
        }
    }
            
}
?>