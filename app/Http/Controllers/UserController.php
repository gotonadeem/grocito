<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Auth;
use App\User;
use App\UserKyc;
use App\ReferralSetting;
use Validator;
use Response;
use Session;
use Hash;
use Helper;
use URL;
use DB;
use Keygen;
class UserController extends Controller
{
    public function __construct()
    {
       parent::__construct();
    }
     function mobileViewChat(Request $request){
         return view('front.mobile_view_chat');
     }
    function register()
    {
       return view('front.user.register');
    }

    function login()
    {
       return view('front.user.login');  
    }

    function register_user(Request $request)
    {

        $userData = array(
            'f_name'      => $request->input('fname'),
            'l_name'      => $request->input('lname'),
            'email'       => $request->input('email'),
            'username'    => $request->input('fname')." ".$request->input('lname'),
            'mobile'      => $request->input('mobile'),
            'login_type'  =>'email',
            'role_id'     => 3,

        );
        $rules = array(
            'f_name'      =>  'required|max:20|regex:/^[a-zA-Z .\']+$/',
            'l_name'     =>   'required|max:20|regex:/^[a-zA-Z .\']+$/',
            'email'     =>    'required|email|unique:users',
            'mobile'    =>    'required|numeric|digits_between:8,10|unique:users,mobile',

        );
        $validator = Validator::make($userData,$rules);
        if($validator->fails())
            echo json_encode(array(
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
            ));
        else {
            //check referral code
            $reffCode = $request->input('reff_code');
            if($reffCode){
                $ifRefExists = User::where('reff_code',$reffCode)->where('is_otp_varified','1')->first();
                if($ifRefExists){
                    $userData['ref_by']= $ifRefExists->reff_code;
                    $userData['referrer_id']= $ifRefExists->id;
                }else{
                    echo json_encode(array(
                        'ref_errors' => true,
                    ));
                    exit();
                }

            }
            $userData['otp']= rand(12,66).rand(67,89);
            Session::set('user_data',$userData);
            Session::set('user_mobile',$userData['mobile']);
            $mmsg=" Use ".$userData['otp']." as one time password(OTP) to verify your account.  Do not share this OTP to anyone for security reasons.";
            Helper::send_msg($userData['mobile'],$mmsg);
            /*Mail::send($emailData['view'], $emailData, function ($message) use ($emailData) {
                $message
                    ->to($emailData['to'])
                    ->from($emailData['from'])
                    ->subject($emailData['subject']);

            });*/
            /* Mail Code End */

            echo json_encode(array(
                'success' => true,
                'otp' => $userData['otp'],
            ));
        }
    }


    function verify_otp(Request $request)
    {
        //echo Helper::getReferralSetting($coloumKey='referrer_amount');die('sdasda');
        $data=Session::get('user_data');
        if($data['otp']==$request->input('otp'))
        {
            $referrerId = 0;
            if(isset($data['referrer_id'])){
                $referrerId = $data['referrer_id'];
            }

           /* $firstName = substr($data['f_name'], 0, 2);
            $data['reff_code'] = $firstName.Helper::unique_code(4);*/
            $uid = Keygen::numeric(4)->generate();
            $data['reff_code'] = strtoupper("GRO".rand(11,99).rand(99,11).$uid);
            $data['is_otp_varified'] = '1';
            $obj= new User($data);
            $obj->save();
            if ($obj->id) {
                $data['user_id']=$obj->id;
                $objKyc= new UserKyc($data);
                $objKyc->save();
                $data= User::where('id',$obj->id)->first();

            /*    if($referrerId){
                    //update referrer amount wallet balance.....
                    $referreWallet['user_id'] =$referrerId;
                    $referreWallet['ref_id'] =$obj->id;
                    $referreWallet['amount'] = ReferralSetting::first()->referrer_amount;
                    $referreWallet['type'] = 'deposit';
                    $referreWallet['payment_type'] = 'refer_and_earn';
                    DB::table('wallets')->insert($referreWallet);
                    //update referral amount wallet balance.....
                    $referreWallet['user_id'] = $obj->id;
                    $referreWallet['ref_id'] = $referrerId;
                    $referreWallet['amount'] = ReferralSetting::first()->referral_amount;
                    $referreWallet['type'] = 'deposit';
                    $referreWallet['payment_type'] = 'refer_and_earn';
                    DB::table('wallets')->insert($referreWallet);
                }*/

                Auth::login($data);
                echo json_encode(array(
                    'success' => true,
                    'message'=>"Login Successfully"
                ));
            }
        }
        else
        {
            echo json_encode(array(
                'fail' => true,
                'message'=>"Invalid OTP"
            ));
        }
    }

    function resend_otp()
    {
        $mobile=Session::get('user_mobile');
        $userData['otp']= rand(12,66).rand(67,89);
        Session::set('user_data',$userData);
        $mmsg=" Use ".$userData['otp']." as one time password(OTP) to verify your account.  Do not share this OTP to anyone for security reasons.";
        Helper::send_msg($mobile,$mmsg);
        echo json_encode(array(
            'success' => true,
            'otp' =>  $userData['otp'],
            'message'=>"Otp send Successfully"
        ));
    }

    function login_user(Request $request)
    {
        $mobile = $request->input('mobile');
        $user= User::where('mobile',$mobile)->where('role_id','3')->get()->count();
        if($user>0) {
            //
            $userData['otp']= rand(12,66).rand(67,89);
            Session::set('user_data',$userData);
            Session::set('user_mobile',$mobile);
            $mmsg=" Use ".$userData['otp']." as one time password(OTP) to verify your account.  Do not share this OTP to anyone for security reasons.";
            Helper::send_msg($mobile,$mmsg);

            echo json_encode(
                array("success" => true,'otp'=>$userData['otp']));
        } else {
            echo json_encode(
                array("error" => true,'message'=>"User does not exists"));
        }
    }


    function verify_login_otp(Request $request)
    {
        $data=Session::get('user_data');
        if($data['otp']==$request->input('otp'))
        {
            $mobile=Session::get('user_mobile');
            $data= User::where('mobile',$mobile)->first();
            Auth::login($data);
            echo json_encode(array(
                'success' => true,
                'message'=>"Login Successfully"
            ));

        }
        else
        {
            echo json_encode(array(
                'fail' => true,
                'message'=>"Invalid OTP"
            ));
        }
    }
   
   function change_password()
   {
	   return view('front.user.change_password'); 
   }
   
  public function update_password(Request $request)
    {
		//print_r($request->all()); die;
		
        $validator = Validator::make($request->all(),
            [
                'new_password' => 'required|min:6|max:20',
                'password_confirmation' => 'required|max:20|min:6',
            ]);
        if ($validator->fails())
        {
            return redirect('change-password')->withInput()->withErrors($validator);
        }
        else
        {
            $user= new User();
			$user->simple_pass =$request->new_password;
			$user->password = Hash::make($request->new_password);
			$user->save();
			Session::flash('success_message', 'Successfully updated password!');
			return redirect('change-password');
        }
    }


   function logout()
   {
      Auth::logout();
      return redirect(URL::to('/'));
   }
   
        public function sendVerifyLink(Request $request) {
        $validator = Validator::make($request->all(), [
                    'email' => 'required|max:255|email',
        ]);

        //check user validations
        if ($validator->fails()) {
              echo json_encode(
                    array("error" => true));
					
        } else {
            $user = User::where('email', $request->email)->first();
            
            if (!empty($user)) {
                
                $id = $user->id;
                $link = url('forgot-changepassword/' . encrypt($id));

                /* Mail Code Start */
                $emailData = array(
                    'to' => $request->email,
                    'from' => 'support@saleplus.in',
                    'subject' => 'Reset Password',
                    'view' => 'user.forgot',
                    'content' => "Please click on below URL or paste into your browser to reset your Password \n\n " . $link . "\n" . "\n\nThanks\nAdmin Team"
                );

                Mail::send($emailData['view'], $emailData, function ($message) use ($emailData) {
                    $message
                            ->to($emailData['to'])
                            ->from($emailData['from'])
                            ->subject($emailData['subject']);
                });
                /* Mail Code End */
                 echo json_encode(
                    array("success" => true));
                //Session::flash('success_message', 'An email with the reset password link has been sent to you. Please check your inbox or spam folder');
                //return redirect('admin/forgot-password');
            } else {
				 echo json_encode(
                    array("error" => true));
                //Session::flash('error_message', 'This email id is not registered');
               // return redirect('admin/forgot-password');
            }
        }
    }


public function forgot_changepassword($token) {
	
	   
   
        $id = decrypt($token);
        $user = User::find($id);
  
        if (!empty($user)) {
            Session::set('reset-password', $user->id);
            return view('front.user.forgot_password');
        } else {
            Session::flash('error_message', 'Your link is not verify. Please correct your link..!!');
            return redirect('/');
        }
    }
	
	


 public function resetPassword(Request $request) {
        $data = $request->all();
        $rules = array(
            'password' => 'required|min:3|confirmed',
            'password_confirmation' => 'required|min:3'
        );

        // Create a new validator instance.
        $validator = Validator::make($data, $rules, [
                    'password.confirmed' => 'Passwords does not matched.',
        ]);
        $id=Session::get('reset-password');
        if ($validator->passes()) {
            $user = User::find($id);
            $user->password = Hash::make($data['password']);
            $user->simple_pass =$data['password'];
            $user->save();
            // print_r($user_found);exit();
            Session::set('reset-password', '');
            Session::flash('success_message', "Password successfully reset");
           return  redirect()->back();
        }
        Session::flash('error_message', "Please Try Again");
        return redirect()->back()->withErrors($validator);
    }


}
?>