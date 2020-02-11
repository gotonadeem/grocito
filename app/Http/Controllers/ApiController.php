<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use App\User;
use App\UserKyc;
use App\Enquiry;
use App\Cms;
use App\UserAddress;
use App\UserLocation;
use DB;
use URL;
use Excel;
use Helper;
use Auth;
use File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
class ApiController extends Controller
{
     public function __construct()
      {
	   parent::__construct(); 
      }
      
        /*register user......... .......................................................*/
	  function api_customer_account(Request $request)
	 {
		   $mobile=$request->input('mobile');
		  $con= User::where('mobile', $mobile)->where('role_id',3)->where('is_otp_varified',1)->first();
		  if(count($con)>0)
		  {
			   return Response::json(array(
                'status_code' => 0,
                'message' => 'You have already registered',
                'error_message'=>"You have already registered",
            ), 200);
		  
		  }
		  else
		  {
				  $data= User::where('mobile', $mobile)->where('role_id',3)->where('is_otp_varified',0)->first();
				  $otp= rand(10,54).rand(55,99);
				  if(count($data)>0)
				  {
						DB::table('users')->where('id', $data->id)->update(['email' =>strtolower($request->input('email')),'otp' =>$otp]);
						DB::table('user_kyc')->where('user_id', $data->id)->update(['f_name' =>$request->input('f_name'),'l_name' =>$request->input('l_name')]);
						$mmsg="Use $otp as one time password(OTP) to verify your account.  Do not share this OTP to anyone for security reasons.";
						Helper::send_msg($mobile,$mmsg);	
						return Response::json(array(
						'status_code' => 1,
						'message' => 'successfully send',
						'otp_code' => $otp,
						'error_message'=>"send successfully",
						'user_id'=>$data->id
					), 200);
					
				  }
				  else
				 {
						$user = array(
							'email'    => strtolower($request->input('email')),
							'otp'    => $otp,
							'mobile'    => $request->input('mobile'),
							'role_id'    => 3,
							'password'    => Hash::make($request->input('password')),
							'simple_pass'    => $request->input('password'),
							'f_name'    => $request->input('f_name'),
							'device_token'    => $request->input('device_token'),
							//'contact_details' => $request->input('contact_details'),
							'l_name'    => $request->input('l_name'),
						);
						$rules = array(
							'email'     =>   'required|unique:users',
							'mobile'    =>   'required|unique:users',
							'f_name'    =>   'required',
							'l_name'    =>   'required',
							);
						$userProfile['f_name']= $request->input('f_name');
						$userProfile['l_name']= $request->input('l_name');
						$validator = Validator::make($user,$rules);
						if ($validator->fails()) {
							 return Response::json(array(
								'status_code' => 0,
								'message' => 'validation error',
								'error_message'=>$validator->errors()->first(),
							), 200);
						}else{
							 $data=Input::all();
							//$data['api_token'] = str_random(60);
							$user = new User($user);
							$user->save();
							$userProfile['user_id']=$user->id;
							$userprofile = new UserKyc($userProfile);
							$userprofile->save();
							DB::table('category_notifications')->insert(['customer_id' =>$user->id]);
							$mmsg=" Use $otp as one time password(OTP) to register your account.  Do not share this OTP to anyone for security reasons.";
							Helper::send_msg($user['mobile'],$mmsg);	
							return Response::json(array(
								'status_code' => 1,
								'message' => 'successfully saved',
								'otp_code' => $otp,
								'error_message'=>"saved successfully",
								'user_id'=>$user->id
							), 200);
						}
				 }
		  }
	 }

	 public function api_merchant_account(Request $request)
      {
          $mobile=$request->input('mobile');
		  $con= User::where('mobile', $mobile)->where('role_id',4)->where('is_otp_varified',1)->first();
		  if(count($con)>0)
		  {
			   return Response::json(array(
                'status_code' => 0,
                'message' => 'You have already registered',
                'error_message'=>"You have already registered",
            ), 200);
		  
		  }
		  else
		  {
			  $agentCheck= User::where('role_id',5)->where('unique_code', $request->input('agent_id'))->first();
			  if(count($agentCheck)>0)
				  {
					  
						  $data= User::where('mobile', $mobile)->where('role_id',4)->where('is_otp_varified',0)->first();
						  $otp= rand(13,55).rand(55,99);
						  if(count($data)>0)
						  {
								DB::table('users')->where('id', $data->id)->update(['email' =>strtolower($request->input('email')),'otp' =>$otp]);

								DB::table('user_kyc')->where('user_id', $data->id)->update(['f_name' =>$request->input('f_name'),'l_name' =>$request->input('l_name')]);
								$mmsg=" Use $otp as one time password(OTP) to verify your account.  Do not share this OTP to anyone for security reasons.";
								Helper::send_msg($mobile,$mmsg);	
								return Response::json(array(
								'status_code' => 1,
								'message' => 'successfully send',
								'otp_code' => $otp,
								'error_message'=>"send successfully",
								'user_id'=>$data->id
							), 200);
							
						  }
						  else
						 {
							
								$user = array(
									'email'    => strtolower($request->input('email')),
									'otp'    => $otp,
									'mobile'    => $request->input('mobile'),
									'role_id'    => 4,
									'f_name'    => $request->input('f_name'),
									'agent_id'    => $request->input('agent_id'),
									'device_token'    => $request->input('device_token'),
									'password'    => Hash::make($request->input('password')),
									'simple_pass'    => $request->input('password'),
									'banned'    =>1,
									'l_name'    => $request->input('l_name'),
								);
								$rules = array(
									'email'     =>   'required|unique:users',
									'mobile'    =>   'required|unique:users',
									'f_name'    =>   'required',
									'l_name'    =>   'required',
									);
								$userProfile['f_name']= $request->input('f_name');
								$userProfile['l_name']= $request->input('l_name');
								$userProfile['business_name']= $request->input('business_name');
								$userProfile['business_address']= $request->input('business_address');

								$validator = Validator::make($user,$rules);
								if ($validator->fails()) {
									 return Response::json(array(
										'status_code' => 0,
										'message' => 'validation error',
										'error_message'=>$validator->errors()->first(),
									), 200);
								}else{
									 $data=Input::all();
									//$data['api_token'] = str_random(60);
									$user = new User($user);
									$user->save();
									$userProfile['user_id']=$user->id;
									$userprofile = new UserKyc($userProfile);
									$userprofile->save();
									$mmsg=" Use $otp as one time password(OTP) to register your account.  Do not share this OTP to anyone for security reasons.";
									Helper::send_msg($user['mobile'],$mmsg);	
									return Response::json(array(
										'status_code' => 1,
										'message' => 'successfully saved',
										'otp_code' => $otp,
										'error_message'=>"saved successfully",
										'user_id'=>$user->id
									), 200);
								}
						 }
				 
				  }
				  else
				  {
					  return Response::json(array(
										'status_code' => 0,
										'message' => 'Agent does not exists',
										'error_message'=>"Agent does not exists",
									), 200);
				  }
		  }
    }

    
	
	//forgot Password...........................................................................
     public function change_password(Request $request)
      {
		$user = array(
            'user_id'    => $request->input('user_id'),
            'old_password'    => $request->input('old_password'),
            'new_password'    => $request->input('new_password'),
        );
        $rules = array(
            'user_id'    =>   'required',
            'old_password'    =>   'required',
            'new_password'    =>   'required',
            );
        $validator = Validator::make($user,$rules);
        if ($validator->fails()) {
             return Response::json(array(
                'status_code' => 0,
                'message' => 'validation error',
                'error_message'=>$validator->errors()->first(),
            ), 200);
        }else{
             if(User::where('id', $request->input('user_id'))->count()>0)
			 {   $usersData=array();
				 if(User::where('id', $request->input('user_id'))->where('simple_pass', $request->input('old_password'))->count()>0)
				 { 
     			 
					 $usersData['password'] =    Hash::make($user['new_password']);
					 $usersData['simple_pass'] =    $user['new_password'];
						$user = User::findOrFail($request->input('user_id'));
						$user->fill($usersData)->save();
						
						 return Response::json(array(
						'status_code' => 1,
						'message' => 'Password has been reset successfully',
						'error_message'=>"Password has been reset successfully",
					  ), 200);
                 }
                 else
				 {
						  return Response::json(array(
						'status_code' => 0,
						'message' => 'Old password does not matched',
						'error_message'=>"Old password does not matched",
					  ), 200);
				 }					 
			 }
             else
			 {
					 return Response::json(array(
					'status_code' => 0,
					'message' => 'User Does not exists',
					'error_message'=>"User Does not exists",
				    ), 200); 
			 }				 
			
        }
    }
	
    //resend otp...........................................................................
     public function resend_otp(Request $request)
      {
		$otp= rand(11,55).rand(55,99);
        $user = array(
            'otp'    => $otp,
            'mobile'    => $request->input('mobile'),
        );
        $rules = array(
            'mobile'    =>   'required',
            );
        $validator = Validator::make($user,$rules);
        if ($validator->fails()) {
             return Response::json(array(
                'status_code' => 0,
                'message' => 'validation error',
                'error_message'=>$validator->errors()->first(),
            ), 200);
        }else{
            DB::table('users')->where('id', $request->input('user_id'))->update(['otp' => $otp ]);  
			$mmsg=" Use $otp as one time password(OTP) .  Do not share this OTP to anyone for security reasons.";
			Helper::send_msg($user['mobile'],$mmsg);	
            return Response::json(array(
                'status_code' => 1,
                'message' => 'successfully resend',
                'otp_code' => $otp,
                'error_message'=>"Resend successfully",
            ), 200);
        }
    }
	
	//forgot Password...........................................................................
     public function forgot_password(Request $request)
      {
		$otp= rand(123,999).rand(12,99);
        $user = array(
            'otp'    => $otp,
            'mobile'    => $request->input('mobile'),
        );
        $rules = array(
            'mobile'    =>   'required',
            );
        $validator = Validator::make($user,$rules);
        if ($validator->fails()) {
             return Response::json(array(
                'status_code' => 0,
                'message' => 'validation error',
                'error_message'=>$validator->errors()->first(),
            ), 200);
        }else{
			  $data=User::where('mobile', $request->input('mobile'))->pluck('id')->toArray();
			  if(count($data)>0)
			  {
					DB::table('users')->where('mobile', $request->input('mobile'))->update(['otp' =>  $otp]);			
					$mmsg=" Use $otp as one time password(OTP) forgot password.  Do not share this OTP to anyone for security reasons.";
					Helper::send_msg($user['mobile'],$mmsg);	
					return Response::json(array(
						'status_code' => 1,
						'message' => 'successfully resend',
						'otp_code' => $otp,
						'user_id' => $data[0],
						'error_message'=>"Resend successfully",
					), 200);
			  }
			  else
			  {
					return Response::json(array(
						'status_code' => 0,
						'message' => 'Your account does not exists',
						'error_message'=>"Your account does not exists",
					), 200);  
			  }
        }
    }
	
	//forgot Password...........................................................................
     public function reset_password(Request $request)
      {
		$user = array(
            'otp'    => $request->input('otp'),
            'user_id'    => $request->input('user_id'),
            'password'    => $request->input('password'),
        );
        $rules = array(
            'user_id'    =>   'required',
            'password'    =>   'required',
            'otp'    =>   'required',
            );
        $validator = Validator::make($user,$rules);
        if ($validator->fails()) {
             return Response::json(array(
                'status_code' => 0,
                'message' => 'validation error',
                'error_message'=>$validator->errors()->first(),
            ), 200);
        }else{
            //DB::table('users')->where('mobile', $request->input('mobile'))->update(['otp' =>  $otp]);
             if(User::where('otp', $request->input('otp'))->where('id', $request->input('user_id'))->count()>0)
			 {   $usersData=array();
				 $usersData['password'] =    Hash::make($user['password']);
                 $usersData['simple_pass'] =    $user['password'];
			     //User::where('otp', $request->input('otp'))->where('id', $request->input('user_id'))->update($usersData);
				    $user = User::findOrFail($request->input('user_id'));
                    $user->fill($usersData)->save();
					
					 return Response::json(array(
					'status_code' => 1,
					'message' => 'Password has been reset successfully',
					'error_message'=>"Password has been reset successfully",
				  ), 200);				 
			 }
             else
			 {
					 return Response::json(array(
					'status_code' => 0,
					'message' => 'Either does not exists or otp unmatched',
					'error_message'=>"Either does not exists or otp unmatched",
				    ), 200); 
			 }				 
			
        }
    }
    
	//user otp check................................................................
	  public function check_otp(Request $request)
    { 
		$user_id=$request->input('user_id');
		$otp=$request->input('otp_code');
        $user = array(
            'otp_code'    =>$request->input('otp_code'),
            'password'    =>$request->input('password'),
           );
		  $data['password']=$request->input('password');
        $rules = array(
            'otp_code'    =>   'required',
            );
        $validator = Validator::make($user,$rules);
        if ($validator->fails()) {
            
             return Response::json(array(
                'status_code' => 0,
                'message' => 'validation error',
                'error_message'=>$validator->errors()->first(),

            ), 200);
            
        }else{
               $user_data= User::with('user_kyc')->where('id',$user_id)->where('otp',$otp)->get()->ToArray();
				 if(count($user_data)>0)
				 {
						if(!empty(trim($data['password'])))
						{
						$data['is_otp_varified']=1;
						$data['password']=Hash::make($data['password']);
						$data['simple_pass']=$request->input('password');
						$user = User::findOrFail($request->input('user_id'));
						$user->fill($data)->save();
						  //dd($user_data);
						  
						        $mmsg="Hi ".$user_data[0]['user_kyc']['f_name'].", \n  Welcome to Cartlay\n";
								$mmsg.="Your Account has been created successfully. Please login using below email and password \n";
								$mmsg.="Your mobile number is: ".strtolower($user_data[0]['mobile'])."\n\n";
								$mmsg.="Your Password is : ".$request->input('password')."\n\n";
								$mmsg.="\n\n Thanks Cartlay";
								 Helper::send_msg($user_data[0]['mobile'],$mmsg);
								$msg="Hi ".$user_data[0]['user_kyc']['f_name'].", <br><br>   Welcome to Cartlay<br><br>";
								$msg.="Your Account has been created successfully. Please login using below email and password<br>";
								$msg.="Your mobile number is: ".strtolower($user_data[0]['mobile'])."<br><br>";
								$msg.="Your Password is : ".$request->input('password')."<br><br>";
								$msg.="<br> <br>  Thanks Cartlay";
								
								$emailData = array(
									'to'        => array(strtolower($user_data[0]['email'])),
									'from'      => 'support@cartlay.com',
									'subject'   => 'Account Created',
									'view'      => 'email.verification-email',
									'content'=>$msg
								);
								Mail::send($emailData['view'], $emailData, function ($message) use ($emailData) {
									$message
										->to($emailData['to'])
										->from($emailData['from'])
										->subject($emailData['subject']);

								});
						}
						else
						{
						$data1['is_otp_varified']=1;
						$user = User::findOrFail($request->input('user_id'));
						$user->fill($data1)->save();
						  //dd($user_data);
						  
						        $mmsg="Hi ".$user_data[0]['user_kyc']['f_name'].", \n  Welcome to Saleplus\n";
								$mmsg.="Your Account has been created successfully. Please login using below email and password \n";
								$mmsg.="Your mobile number is: ".strtolower($user_data[0]['mobile'])."\n\n";
								$mmsg.="Your Password is : ".$request->input('password')."\n\n";
								$mmsg.="\n\n Thanks Saleplus";
								 Helper::send_msg($user_data[0]['mobile'],$mmsg);
								$msg="Hi ".$user_data[0]['user_kyc']['f_name'].", <br><br>   Welcome to Saleplus<br><br>";
								$msg.="Your Account has been created successfully. Please login using below email and password<br>";
								$msg.="Your mobile number is: ".strtolower($user_data[0]['mobile'])."<br><br>";
								$msg.="Your Password is : ".$request->input('password')."<br><br>";
								$msg.="<br> <br>  Thanks Saleplus";
								
								$emailData = array(
									'to'        => array(strtolower($user_data[0]['email'])),
									'from'      => 'support@saleplus.com',
									'subject'   => 'Account Created',
									'view'      => 'email.verification-email',
									'content'=>$msg
								);
								Mail::send($emailData['view'], $emailData, function ($message) use ($emailData) {
									$message
										->to($emailData['to'])
										->from($emailData['from'])
										->subject($emailData['subject']);

								});
							
						}
						
					return Response::json(array(
						'status_code' => 1,
						'message' => 'OTP has been matched successfully',
						'otp_code' => '12345',
						'error_message'=>"matched successfully",
						'data'=> User::with('user_kyc')->where('id',$user_id)->where('otp',$otp)->get()->ToArray(),
					), 200);
				}
				else
				{
					  return Response::json(array(
					   'status_code' => 0,
					   'message' => 'Invalid Otp',
					   'error_message'=>"Invalid OTP",
					  ), 200);
				}

        }
    }

	//user login....................................................................................
	    public function api_login(Request $request){
        $mobile = $request->mobile;
        $password = $request->password;
		$data=User::select('banned')->where('mobile',$mobile)->first();
		if($data['banned']==1)
		{
			 return Response::json(array(
                'status_code' => 0,
                'message' => 'Your account has been blocked or unverified',
                'error_message'=>'Your account has been blocked or unverified',
            ), 200);
		}
		else
		{
			if (Auth::attempt(array('mobile' => $mobile, 'password' => $password,'is_otp_varified'=>1))) {            
				User::where('mobile',$mobile)->update(['device_token'=>$request->device_token,'device_type'=>'android']);
				return Response::json(array(
					'status_code' => 1,
					'message' => 'successfully login',
					'error_message'=>'',
					'user_image_path' => url('/')."/public/admin/uploads/user/",
					'data'=>User::with('user_kyc')->where('id',Auth::user()->id)->get()->ToArray(),

				), 200);
			} else {
				return Response::json(array(
					'status_code' => 0,
					'message' => 'Invalid Mobile or Password',
					'error_message'=>'Invalid Mobile or Password',
					'data'=>null
				), 200);
			}
		}
    }
	
	function check_delivery_pincode($pincode)
	{
	    $curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://track.delhivery.com/c/api/pin-codes/json/?token=2713249514eb30ccbf6c3a5d8d9f423d8b5173a5&filter_codes=$pincode",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_POSTFIELDS => "",
			  CURLOPT_HTTPHEADER => array(
				"Cache-Control: no-cache",
				"Content-Type: application/x-www-form-urlencoded",
				"Postman-Token: f1d54a29-6439-489d-b585-9bb70896ec9e",
				"content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);
			curl_close($curl);
			if ($err) {
				 return 'No';
			  //echo "cURL Error #:" . $err;
			} else {
			  $data= json_decode($response);
			  $count=count($data->delivery_codes);
				  if($count>0)
				  {
					 return "Yes";
				  }
				  else
				  {
					 return 'No';
				  }			  
			}
	}
	
	//user add address.........................................................................
	 public function add_user_address(Request $request)
    {

            $status= json_decode(Helper::check_pincode_api($request->input('pincode')));
			 if(count($status))
			 {			 
						 $user = array(
					'name'    => $request->input('name'),
					'user_id'    =>$request->input('user_id'),
					'mobile'    => $request->input('mobile'),
					'role_id'    => 3,
					'house'    => $request->input('house'),
					'street'    => $request->input('street'),
					'is_default'    => 1,
					'city'    => $request->input('city'),
					'landmark'    => $request->input('landmark'),
					'state'    => $request->input('state'),
					'pincode'    => $request->input('pincode'),
				);
				
				
				$rules = array(
					'name'    =>     'required',
					'mobile'    =>   'required',
					'house'     =>   'required',
					'street'    =>   'required',
					'city'      =>   'required',
					'landmark'  =>   'required',
					'state'     =>   'required',
					'pincode'   =>   'required',
					);
				$validator = Validator::make($user,$rules);
				if ($validator->fails()) {
					
					 return Response::json(array(
						'status_code' => 0,
						'message' => 'validation error',
						'error_message'=>$validator->errors()->first(),
					   

					), 200);
					
				}else{
					
					  
					   if(!empty($request->input('address_id')))
					   {
							DB::table('user_addresses')->where('user_id', $request->input('user_id'))->update(['is_default' => 0]); 
							$update = UserAddress::findOrFail($request->input('address_id'));
							$update->fill($user)->save();
					   }
					   else{
							 DB::table('user_addresses')->where('user_id', $request->input('user_id'))->update(['is_default' => 0]);
							 $user = new UserAddress($user);
							 $user->save();
					   }
					   
					  return Response::json(array(
						'status_code' => 1,
						'message' => 'successfully saved',
						'error_message'=>"saved successfully",
					), 200);
				}
			
			 }
			 else
			 {
				 
				   if($this->check_delivery_pincode($request->input('pincode'))=="Yes")
				   {
					     $user1 = array(
					'name'    => $request->input('name'),
					'user_id'    =>$request->input('user_id'),
					'mobile'    => $request->input('mobile'),
					'role_id'    => 3,
					'house'    => $request->input('house'),
					'street'    => $request->input('street'),
					'is_default'    => 1,
					'city'    => $request->input('city'),
					'landmark'    => $request->input('landmark'),
					'state'    => $request->input('state'),
					'pincode'    => $request->input('pincode'),
				);
				
						  if(!empty($request->input('address_id')))
						   {
								DB::table('user_addresses')->where('user_id', $request->input('user_id'))->update(['is_default' => 0]); 
								$update = UserAddress::findOrFail($request->input('address_id'));
								$update->fill($user1)->save();
						   }
						   else{
								 DB::table('user_addresses')->where('user_id', $request->input('user_id'))->update(['is_default' => 0]);
								 $user = new UserAddress($user1);
								 $user->save();
						   }
						   
						  return Response::json(array(
							'status_code' => 1,
							'message' => 'successfully saved',
							'error_message'=>"saved successfully",
						), 200);
				   }
				   else
				   {
					   return Response::json(array(
						'status_code' => 0,
						'message' => 'Unable to deliver at this location',
					  ), 200);
				   }
				   
				 
				
			 }
        
    }
	
	//user add account details.........................................................................
	 public function add_user_account(Request $request)
    {

           			 $user = array(
					'account_number'    => $request->input('account_number'),
					'user_id'    =>$request->input('user_id'),
					'ifsc_code'    => $request->input('ifsc_code'),
					'account_holder_name'    => $request->input('account_holder_name'),
				);
				$rules = array(
					'account_number' =>'required',
					'user_id'        =>'required',
					'ifsc_code'      =>'required',
					'account_holder_name' =>'required',
					);
				$validator = Validator::make($user,$rules);
				if ($validator->fails()) {
					
					 return Response::json(array(
						'status_code' => 0,
						'message' => 'validation error',
						'error_message'=>$validator->errors()->first(),
					   

					), 200);
					
				}else{
						  DB::table('user_kyc')->where('user_id', $request->input('user_id'))->update(['account_number' => $request->input('account_number'),'ifsc_code'=>$request->input('ifsc_code'),'account_holder_name'=>$request->input('account_holder_name')]);
						  return Response::json(array(
							'status_code' => 1,
							'message' => 'successfully saved',
							'error_message'=>"saved successfully",
						), 200);
				}
			
    }
	
	//user get address list..................................................................
	 public function get_user_address(Request $request)
    {
        $user = array(
            'user_id'    =>$request->input('user_id'),
        );
        $rules = array(
            'user_id'    =>     'required',
            );
        $validator = Validator::make($user,$rules);
        if ($validator->fails()) {           
             return Response::json(array(
                'status_code' => 0,
                'message' => 'validation error',
                'error_message'=>$validator->errors()->first(),
               

            ), 200);
            
        }else{
			  $data=UserAddress::orderBy('updated_at', 'DESC')->where("user_id",$request->input('user_id'))->get();
			  $id= UserAddress::where('user_id', $request->input('user_id'))->where('is_default', 1)->pluck('id')->first();
              return Response::json(array(
                'status_code' => 1,
				'default_id'=>$id,
                'message' => 'List of Addresses',
                'data' => $data,
                'error_message'=>"List of Addresses",
            ), 200);
        }
    }
    //user set default address...............................................................
	public function default_address(Request $request)
	{
		 $user = array(
            'address_id'    =>$request->input('address_id'),
            'user_id'    =>$request->input('user_id'),
        );
        $rules = array(
            'user_id'    =>     'required',
            'address_id'    =>     'required',
            );
        $validator = Validator::make($user,$rules);
        if ($validator->fails()) {           
             return Response::json(array(
                'status_code' => 0,
                'message' => 'validation error',
                'error_message'=>$validator->errors()->first(),
            ), 200);
            
        }else{
			  DB::table('user_addresses')->where('user_id', $request->input('user_id'))->update(['is_default' => 0]);  
			  DB::table('user_addresses')->where('user_id', $request->input('user_id'))->where('id', $request->input('address_id'))->update(['is_default' => 1]);
              return Response::json(array(
                'status_code' => 1,
                'message' => 'updated successfully',
                'error_message'=>"Updated successfully",
            ), 200);
        }
	}
	
   //activate user.......................................................................................
	
  public function activate_user(Request $request)
    {
        $user = array(
            'id'    =>$request->input('id'),
           );
        $rules = array(
            'id'    =>   'required',
            );
        $validator = Validator::make($user,$rules);
        if ($validator->fails()) {
            
             return Response::json(array(
                'status_code' => 0,
                'message' => 'validation error',
                'error_message'=>$validator->errors()->first(),

            ), 200);
            
        }else{
             $data=$request->all(); 
             $user = User::findOrFail($request->input('id'));
             if($user->fill($data)->save())
             {
            return Response::json(array(
                'status_code' => 1,
                'message' => 'successfully saved',
                'otp_code' => '12345',
                'error_message'=>"saved successfully",
                'user_image_path' => url('/')."/public/admin/uploads/user/",
                'data'=>User::with('user_profile')->find($user->id)
            ), 200);
            }

        }
    }

	//enquiry.....................................................................................
    public function get_enquiry_count(Request $request)
    {
            $user_id=$request->input('user_id');
            $user = Enquiry::where('user_id',$user_id)->get()->count();
                return Response::json(array(
                    'status_code' => 1,
                    'error_message'=>"Total Enquiry Count",
                    'count'=>$user
                ), 200);
    }
    
 
    //enquiry. list..................................................................................
    public function get_enquiry_list(Request $request){
        $count= Enquiry::count();
        $data =  Enquiry::where('user_id',$request->input('user_id'))->get();
        if($count>0) {
            return Response::json(array('status' => 1, 'data' => $data), 200);
        }
        else
        {
            return Response::json(array('status' => 0, 'data' =>array()), 200);
        }
    }
    //send enquiry...................................................................................
     public function send_query(Request $request)
     {
        $enquiry = array(
            'property'     => $request->input('property'),
            'user_id'     => $request->input('user_id'),
            'property_type'     => $request->input('property_type'),
            'accommodation'    => $request->input('accommodation'),
            'minBudget'    => $request->input('minBudget'),
            'maxBudget'    => $request->input('maxBudget'),
            'sizeMin'    => $request->input('sizeMin'),
            'sizeType'    => $request->input('sizeType'),
            'sizeMax'    => $request->input('sizeMax'),
            'projectStatus'    => $request->input('projectStatus'),
            'finance'    => $request->input('finance'),
            'locationCity'    => $request->input('locationCity'),
            'specialRequirement'    => $request->input('specialRequirement'),
        );
        $rules = array(
            'property'     =>   'required',
            'minBudget'    =>   'required',
            'maxBudget'    =>   'required',
            'sizeMin'    =>   'required',
            'sizeType'    =>   'required',
            'sizeMax'    =>   'required',
            'projectStatus'    =>   'required',
            'locationCity'    =>   'required',
        );
        $validator = Validator::make($enquiry,$rules);
        if ($validator->fails()) {
            return Response::json(array(
                'status_code' => 0,
                'message' => 'validation error',
                'error_message'=>$validator->errors()->first(),
            ), 200);
        }else{
            $data=Input::all();
            $enquiry = new Enquiry($data);
            $enquiry->save();
            return Response::json(array(
                'status_code' => 1,
                'message' => 'successfully saved',
                'otp_code' => '12345',
                'error_message'=>"saved successfully",
                'data'=>Enquiry::find($enquiry->id)->toArray(),
            ), 200);
        }
    }
    //update enquiry.......................................................................................
    public function update_query(Request $request)
    {
        $enquiry1 = array(
            'property'     => $request->input('property'),
            'id'     => $request->input('id'),
            'property_type'     => $request->input('property_type'),
            'accommodation'    => $request->input('accommodation'),
            'minBudget'    => $request->input('minBudget'),
            'maxBudget'    => $request->input('maxBudget'),
            'sizeMin'    => $request->input('sizeMin'),
            'sizeType'    => $request->input('sizeType'),
            'sizeMax'    => $request->input('sizeMax'),
            'projectStatus'    => $request->input('projectStatus'),
            'finance'    => $request->input('finance'),
            'locationCity'    => $request->input('locationCity'),
            'specialRequirement'    => $request->input('specialRequirement'),
        );
        $rules = array(
            'property'     =>   'required',
            'minBudget'    =>   'required',
            'maxBudget'    =>   'required',
            'sizeMin'    =>   'required',
            'sizeType'    =>   'required',
            'sizeMax'    =>   'required',
            'projectStatus'    =>   'required',
            'locationCity'    =>   'required',
        );
        $validator = Validator::make($enquiry1,$rules);
        if ($validator->fails()) {
            return Response::json(array(
                'status_code' => 0,
                'message' => 'validation error',
                'error_message'=>$validator->errors()->first(),
            ), 200);
        }else{
             $data=$request->all(); 
             $enquiry = Enquiry::findOrFail($request->input('id'));
            $enquiry->fill($data)->save();
            return Response::json(array(
                'status_code' => 1,
                'message' => 'successfully Updated',
                'error_message'=>"saved successfully",
                'data'=>Enquiry::find($request->input('id'))->toArray(),
            ), 200);
        }
    }

    //update profile....................................................................................
    public function update_profile(Request $request)
    {
         $profile = array(
            'username' => $request->input('name'),
            'city' => $request->input('city'),
            'id' => $request->input('user_id'),
         );
        $rules = array();
        $validator = Validator::make($profile, $rules);
        if ($validator->fails()) {
            return Response::json(array(
                'status_code' => 0,
                'message' => 'validation error',
                'error_message' => $validator->errors()->first(),
            ), 200);
        } else {

            $data = $request->all();
            $profile = User::findOrFail($request->input('user_id'));
            $profile->fill($data)->save();
            if(!empty($_FILES['user_image']['name'])){
            $image = $request->file('user_image');
                $path_original = public_path() . '/admin/uploads/user';
                $file = $request->user_image;
                $photo_name = time(). '-' .$file->getClientOriginalName();
                $file->move($path_original, $photo_name);
                $userProfile['profile_image'] = $photo_name;
            }
            $userProfile['city']=$request->input('city');
            DB::table('user_profiles')->where('user_id', $request->input('user_id'))->update($userProfile);

            return Response::json(array(
                'status_code' => 1,
                'message' => 'Successfully Updated',
                'error_message' => "Saved successfully",
                'user_image_path' => url('/')."/public/admin/uploads/user/",
                'data' => User::with('user_profile')->find($request->input('user_id')),
            ), 200);
        }
    }
	
	
	//update profile....................................................................................
    public function update_profile_image(Request $request)
    {
         $profile = array(
            'user_id' => $request->input('user_id'),
         );
        $rules = array();
        $validator = Validator::make($profile, $rules);
        if ($validator->fails()) {
            return Response::json(array(
                'status_code' => 0,
                'message' => 'validation error',
                'error_message' => $validator->errors()->first(),
            ), 200);
        } else {

            $data = $request->all();
            $profile = User::findOrFail($request->input('user_id'));
            $profile->fill($data)->save();
            if(!empty($_FILES['user_image']['name'])){
            $image = $request->file('user_image');
                $path_original = public_path() . '/admin/uploads/user';
                $file = $request->user_image;
                $photo_name = time(). '-' .$file->getClientOriginalName();
                $file->move($path_original, $photo_name);
                $userProfile['profile_image'] = $photo_name;
            }
            DB::table('user_kyc')->where('user_id', $request->input('user_id'))->update($userProfile);
            return Response::json(array(
                'status_code' => 1,
                'message' => 'Successfully Updated',
                'error_message' => "Saved successfully",
                'user_image_path' => url('/')."/public/admin/uploads/user/",
                'data' => User::with('user_kyc')->find($request->input('user_id')),
            ), 200);
        }
    }
	
	
	
	//user add locations.........................................................................
	 public function add_user_location(Request $request)
    {

            		 $user = array(
					'user_id'    =>$request->input('user_id'),
					'location'    => $request->input('location'),
					'lattitude'    => $request->input('lattitude'),
					'longitude'    => $request->input('longitude'),
				);
				$rules = array(
				
					);
				$validator = Validator::make($user,$rules);
				if ($validator->fails()) {
					
					 return Response::json(array(
						'status_code' => 0,
						'message' => 'validation error',
						'error_message'=>$validator->errors()->first(),
					   

					), 200);
					
				}else{
					  $obj= new UserLocation($user);
					  $obj->save(); 
					  return Response::json(array(
						'status_code' => 1,
						'message' => 'successfully saved',
						'error_message'=>"saved successfully",
					), 200);
				}
			
			
        
    }
	
	//user Transaction update.........................................................................
	 public function update_merchant_payment(Request $request)
    {

            		 $user = array(
					'user_id'    =>$request->input('user_id'),
					'transaction_id'    => $request->input('transaction_id'),
					'order_id'    => $request->input('order_id'),
				);
				$rules = array(
				   'user_id'=>'required',
				   'transaction_id'=>'required',
				   'order_id'=>'required',
				   
					);
				$validator = Validator::make($user,$rules);
				if ($validator->fails()) {
					
					 return Response::json(array(
						'status_code' => 0,
						'message' => 'validation error',
						'error_message'=>$validator->errors()->first(),
					   

					), 200);
					
				}else{
					  DB::table('users')->where('id',$user['user_id'])->update(['banned'=>0,'transaction_id'=>$user['transaction_id'],'order_id'=>$user['order_id']]);
					  
					  return Response::json(array(
						'status_code' => 1,
						'message' => 'successfully saved',
						'error_message'=>"saved successfully",
					), 200);
				}
			
			
        
    }
	
	public function open_source()
    {
        $content= Cms::find(2);
       return Response::json(array('status' => 1, 'data'=>$content), 200);
    }
	
    public function term_condition()
    {
        $content= Cms::find(2);
       return Response::json(array('status' => 1, 'data'=>$content), 200);
    }
	
    public function privacy_policy()
    {
         $content= Cms::find(6);
         return Response::json(array('status' => 1, 'data'=>$content), 200);
    }
	public function about_us()
    {
         $content= Cms::find(7);
         return Response::json(array('status' => 1, 'data'=>$content), 200);
    }
	
	public function guarantee()
    {
         $content= Cms::find(3);
         return Response::json(array('status' => 1, 'data'=>$content), 200);
    }
}
?>