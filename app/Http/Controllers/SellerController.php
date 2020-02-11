<?php
namespace App\Http\Controllers;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Laravel\Socialite\Facades\Socialite;
use Auth;
use App\Helpers\Helper;
use Session;
use DB;
use App\OrderMeta;
use App\Order;
use Validator;
use App\User;
use App\UserKyc;
use App\Agreement;
use App\Role;
use App\Payment;
use App\Country;
use App\GeneralSetting;
use App\SellerCommission;
use App\State;
use App\City;
use App\Pincode;
use Hash;
use Mail;
use App\Enquiry;
use App\UserRequestPlan;
use App\PropertyVisitCount;
use Carbon\Carbon;
session_start();

class SellerController extends Controller
{
    public function __construct()
    {
		parent::__construct();
    }

    public function seller_dashboard(){
         
                if (Auth::check())
                {	
                   $user = auth()->user();
                    if($user){
						
						
                        if($user->verify_status=="verified")
						{
							$cancellation_risk_orders = OrderMeta::with('order')->select(DB::raw("(COUNT(*)) as total_order"))->where('seller_id',Auth::user()->id)->where('status','pending')->WhereHas('order', function ($query)
            {
				$date = \Carbon\Carbon::today()->subDays(5);
				$query->where('orders.created_at', '<', $date);
				
            })->groupBy('order_id')->orderBy('created_at','desc')->get();
			  $cancellation_risk_orders=count($cancellation_risk_orders);

						  $not_shipped_orders = Order::with('order_meta_data')->where('seller_id',Auth::user()->id)->where('status','pending')->get()->count();
						  //$not_shipped_orders = OrderMeta::with('order')->where('seller_id',Auth::user()->id)->where('status','pending')->groupBy('order_id')->orderBy('created_at','desc')->get()->count();
						  $total_sales = OrderMeta::with('order')->where('seller_id',Auth::user()->id)->where('status','delivered')->groupBy('order_id')->get()->sum();
                          $monthsData=DB::table("orders")
							  ->Join('order_metas', 'orders.id', '=', 'order_metas.order_id')
							  ->where('order_metas.seller_id',Auth::user()->id)
							  ->select(DB::raw("(COUNT(*)) as total_order"),DB::raw("MONTH(orders.created_at) as month_name"))
							  ->orderBy('orders.created_at')
							  ->groupBy(DB::raw("MONTH(orders.created_at)"))
							  ->get();

                          $total_sale=Payment::where('type','deposit')->where('user_id',Auth::user()->id)->sum('amount');
                            $todayDate = date('Y-m-d');
                            $seller_id= Auth::user()->id;
                            $todayPaymentData = DB::select("SELECT orders.shipped_date as shippedDate, 
                                          SUM(order_metas.price * order_metas.qty) AS total,
                                          SUM(order_metas.product_commission) AS total_admin_commission,
                                          SUM(order_metas.net_amount) AS net_amount
                                    FROM 
                                        orders inner join order_metas on orders.id= order_metas.order_id
                                    WHERE 
                                        orders.shipped_date!='0000-00-00' and orders.shipped_date = '$todayDate' and order_metas.seller_id='$seller_id' and (order_metas.status='delivered' or order_metas.status='return' or order_metas.status='exchange')
                                    GROUP BY
                                        (orders.shipped_date) ");
                            $total_today_payable_amount = 0;
                            if($todayPaymentData){
                                foreach($todayPaymentData as $td){
                                    $totalTodayAmount = $td->total;
                                    $totalTodayNetAmount = $td->net_amount;
                                    $totalTodayCommission = $td->total_admin_commission;
                                    $gstAmount =  ($totalTodayCommission * 18)/100;
                                    $totalAdminCmsn = $totalTodayCommission + $gstAmount;
                                    $tcsTax = 	($totalTodayNetAmount * 1)/100;
                                    $total_today_payable_amount += ($totalTodayAmount - $totalAdminCmsn - $tcsTax);
                                }
                            }
						 return view("seller.seller_dashboard",compact('not_shipped_orders','monthsData','total_sales','cancellation_risk_orders','total_sale','total_today_payable_amount'));
						}
						else
						{
                        return view("seller.waiting_seller_dashboard");
						}
                    }
                }else{
                    return redirect("/login");
        }
    }
     public function seller_profile(){
        if (Auth::check())
        {
			    $country_list= Country::get();
                return view("seller.seller_profile",compact('country_list'));
       }else{
            return redirect("/login");
        }

    }
	
	public function store_profile(Request $request)
	{
			 if (Auth::check())
			 {
				$userData = array(
					'address_1' =>$request->input('address_1'),
				);
				$rules = array(
					'address_1' => 'required',

				);
				$validator = Validator::make($userData, $rules);
				if ($validator->fails()) {
				   return redirect('seller/profile')->withInput()->withErrors($validator);
				} else {
					 DB::table('user_kyc')
					->where('user_id', Auth::user()->id)
					->update(['address_1' =>$request->input('address_1')]);
					Session::flash('success_message', 'Pickup address has been updated successfully');
					return redirect("seller/profile");
				}
			}
			else
			{
				return redirect("/login");
			}
	}
	
    public function change_image(Request $request)
    {

        $userData = array(
            'image' => $request['profile_image'],
        );
        $rules = array(
            'image' => 'mimes:jpeg,jpg,png|max:1000',

        );
		
		
        $validator = Validator::make($userData, $rules);
        if ($validator->fails()) {
            echo json_encode(array(
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
            ));
        } else {
            $input = $request->all();
            $user = $this->model->findOrFail($request->id);
            $file =  $userData['image'];
            if ($request->hasFile('profile_image')) {
                $name = time() . '-' . $file->getClientOriginalName();
                $input['image'] = $name;
                $path_original = config('image.path.profile_image.original');
                $destinationPath = config('image.path.profile_image.original');
                $file->move($destinationPath, $name);
                if($request->old_img!='')
                    try {
                        unlink($path_original . $request->old_img);
                    } catch (\Exception $e) {
                    }
            }
            $url=env('APP_URL');
            if ($user->fill($input)->save()) {
                echo json_encode(array(
                    'success' => true,
                    'profile_image'=>"<img src=".$url."/uploads/profile_image/original/".$name." class='preview'>",
                ));
            } else {
                return response('fail', 500);
            }
        }
    }

    public function complete_profile(){
            if (Auth::check())
            {
                $user_info = User::with('user_kyc')->where('id',Auth::user()->id)->first();
		        $countries =Country::where('id','101')->get();
                $state= City::with('state')->where('status',1)->groupBy('state_id')->get();

                $agreement =Agreement::first();
		        $general_setting =GeneralSetting::select('saleplus_commission')->first();
                $deliveryPincode = Pincode::where('city_id',$user_info->user_kyc->city_id)->get();
                return view("seller.verify.complete_profile",compact('user_info','countries','agreement','general_setting','state','deliveryPincode'));
			}
			else
			{
				return redirect('/login');
			}
    }
	
	
	function complete_user_profile_1(Request $request)
	{
		  if (Auth::check())
         {
             $input = $request->all();
	         foreach($input as $key=>$vs)
				{
					if($vs == '')
					{
						unset($input[$key]);
					}
				}
			   
     		$user = User::find(Auth::user()->id);
			$user->fill($input)->save();
			unset($input['email']);
			unset($input['mobile']);
			unset($input['username']);
		     $id=Auth::user()->id;
            $validator = Validator::make($request->all(),
                [
                    'username' => 'required|max:20|regex:/^[a-zA-Z .\']+$/',
                    //'email' => 'required|max:100|email|unique:users,email'.($id != '' ? ','.$id:'').'',
                    'mobile' => 'required|numeric|digits_between:8,10|unique:users,mobile'.($id != '' ? ','.$id:'').'',
                ]);
            if ($validator->fails())
            {
                  echo json_encode(array(
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
                  ));
            }
            else
            {	
				if(!empty($_FILES['profile_image']['name']))
				{
					$profile_image= time()."_".$_FILES['profile_image']['name'];
					move_uploaded_file($_FILES['profile_image']['tmp_name'],public_path() . '/admin/uploads/seller/'.$profile_image);
                    $input['profile_image']=$profile_image;
				     
				} else{ unset($input['profile_image']);}
				
				if(!empty($_FILES['seller_image']['name']))
				{
					$seller_image= time()."_".$_FILES['seller_image']['name'];
					move_uploaded_file($_FILES['seller_image']['tmp_name'],public_path() . '/admin/uploads/seller/'.$seller_image);
                    $input['seller_image']=$seller_image;
				     
				} else{
                unset($input['seller_image']);
                }
                UserKyc::where('user_id', $id)->update($input);
                Session::flash('success_message', 'Successfully updated profile!');
                 echo json_encode(array(
                'success' => true,
                  ));
            }
         }
		 else{
            Session::flash('error_message', 'Sorry You have to login first');
              echo json_encode(array(
                'not_login' => true,
                  ));
            }
			
	}
	
	function complete_user_profile_2(Request $request)
	{
		  if (Auth::check())
         {
             $input = $request->all();
	         foreach($input as $key=>$vs)
				{
					if($vs == '')
					{
						unset($input[$key]);
					}
				}
		     $id=Auth::user()->id;
            $validator = Validator::make($request->all(),
                []);
            if ($validator->fails())
            {
                  echo json_encode(array(
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
                  ));
            }
            else
            {	
				if(!empty($_FILES['pan_image']['name']))
				{
					   $pan_image= time()."_".$_FILES['pan_image']['name'];
					   move_uploaded_file($_FILES['pan_image']['tmp_name'],public_path() . '/admin/uploads/seller/'.$pan_image);
			           $input['pan_image']=$pan_image;
	            
				}
				 else{ unset($input['pan_image']);}
				if(!empty($_FILES['cancel_cheque']['name']))
				{
					//echo $_FILES['cancel_cheque']['name']; die;
					
					 $cancel_cheque= time()."_".$_FILES['cancel_cheque']['name'];
					 move_uploaded_file($_FILES['cancel_cheque']['tmp_name'],public_path() . '/admin/uploads/seller/'.$cancel_cheque);
                     $input['cancel_cheque']=$cancel_cheque;
				     
				} else{ unset($input['cancel_cheque']);}
			
				
				
				if(!empty($_FILES['cin_image']['name']))
				{
					$cin_image= time()."_".$_FILES['cin_image']['name'];
					move_uploaded_file($_FILES['cin_image']['tmp_name'],public_path() . '/admin/uploads/seller/'.$cin_image);
                    $input['cin_image']=$cin_image;
				     
				} else{ unset($input['cin_image']);}
                UserKyc::where('user_id', $id)->update($input);
                Session::flash('success_message', 'Successfully updated profile!');
                 echo json_encode(array(
                'success' => true,
                  ));
            }
         }
		 else{
            Session::flash('error_message', 'Sorry You have to login first');
              echo json_encode(array(
                'not_login' => true,
                  ));
            }
			
	}
	
	function complete_user_profile_3(Request $request)
	{
	  	  if (Auth::check())
         {
            /* $input = $request->all();
	         foreach($input as $key=>$vs)
				{
					if($vs == '')
					{
						unset($input[$key]);
					}
				}*/
			$user = User::find(Auth::user()->id);
			$user->fill(['verify_status'=>"kyc_completed"])->save();
		    $id=Auth::user()->id;
            $validator = Validator::make($request->all(),
                [
                 ]);
            if ($validator->fails())
            {
                  echo json_encode(array(
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
                  ));
            }
            else
            {	
			
			/*	if(!empty($_FILES['signature']['name']))
				{
					$signature= time()."_".$_FILES['signature']['name'];
					move_uploaded_file($_FILES['signature']['tmp_name'],public_path() . '/admin/uploads/seller/'.$signature);
                    $input['signature']=$signature;
				     
				} 
                else{ 
                    unset($input['signature']);
                }
                UserKyc::where('user_id', $id)->update($input);*/
				
                //$general_setting =GeneralSetting::select('cartlay_commission')->first();
				//$commission['cartlay_commission']= $general_setting['cartlay_commission'];
				//$commission['seller_id']= $id;
                //UserKyc::where('user_id', $id)->update($commission);
				
				Session::flash('success_message', 'Successfully updated profile!');
                 echo json_encode(array(
                'success' => true,
                  ));
            }
         }
		 else{
              Session::flash('error_message', 'Sorry You have to login first');
              echo json_encode(array(
                'not_login' => true,
                  ));
            }
	}
	
    public function updateProfile(Request $request)
    {
      
        if (Auth::check())
        {
            $input = $request->all();
            $reqmobile=$input['mobile'];
            $reqemail=$input['email'];
            $user = User::find(Auth::user()->id);
            $usermob=$user->mobile;
            $useremail=$user->email;
            if($reqmobile!=$usermob){
                $input['is_email_verified']=0;
            }
            if($reqmobile!=$usermob){
                $input['is_mobile_verified']=0;
            }
            $id=$user->id;

            $validator = Validator::make($request->all(),
                [
                    'fname' => 'required|max:20|regex:/^[a-zA-Z .\']+$/',
                    'lname' => 'required|max:20|regex:/^[a-zA-Z .\']+$/',
                    'email' => 'required|max:100|email|unique:users,email'.($id != '' ? ','.$id:'').'',
                    'mobile' => 'required|numeric|digits_between:8,10|unique:users,mobile'.($id != '' ? ','.$id:'').'',
                    'address'=> 'required',
                    'gender'=>'required',
                    'dob'=>  'required',
                ]);
            if ($validator->fails())
            {
                return redirect('seller/edit-profile')->withInput()->withErrors($validator);
            }
            else
            {
              //print_r($input);die;
                $user->fill($input)->save();
                Session::flash('success_message', 'Successfully updated profile!');
                return redirect('seller/profile');
            }
        }else{
            Session::flash('error_message', 'Sorry You have to login first');
            return redirect('login');

        }
    }

    public function get_state(Request $request)
    {
        $id=$request->input('id');
        $state_list= State::where('country_id',$id)->get();
        return view('seller.verify.state_ajax')->with('state_list',$state_list);
    }

    public function get_city(Request $request)
    {
        $id=$request->input('id');
        $city_list= City::where('state_id',$id)->get();
        return view('seller.verify.state_ajax')->with('state_list',$city_list);
    }
	
    
    public function change_assword(){
		if(Auth::user())
		{
           return view("seller.change_password");
		}
		else
		{
			redirect('/');
		}
    }
	
	public function aggreement(){
		if(Auth::user())
		{
			$agreement= Agreement::first();
           return view("seller.aggreement",compact('agreement'));
		}
		else
		{
			redirect('/');
		}
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'password' => 'required|min:6|max:20|confirmed',
                'password_confirmation' => 'required|max:20|min:6',
                'current_password' => 'required|max:20|min:6',
            ]);
        if ($validator->fails())
        {
            return redirect('seller/change-password')->withInput()->withErrors($validator);
        }
        else
        {
            $user = User::find(Auth::user()->id);
            if (Hash::check($request->current_password, $user->password))
            {
                $user->password = Hash::make($request->password);
                $user->simple_pass = $request->password;
                $user->save();
                Session::flash('success_message', 'Successfully updated password!');
            }
            else
            {
                Session::flash('error_message', 'Current password is incorrect');
            }
            return redirect('seller/change-password');
        }
    }
    /**
     * enquires()
     * looged in user can get enquires
     * @return $enquires
     * Developer : jyotsna
     */
	 
    
    function update_setting(Request $request)
	{
		     if($request->is_available==0)
			 {
				$validator = Validator::make($request->all(),
                [
                'from_date' => 'required',
                'to_date' => 'required',
                 ]);
				if ($validator->fails())
				{
					return redirect('seller/setting')->withInput()->withErrors($validator);
				}
				else
				{
				$user = User::find(Auth::user()->id);
                $user->is_available = $request->is_available;
                $user->from_date = date('Y-m-d',strtotime($request->from_date));
                $user->to_date =  date('Y-m-d',strtotime($request->to_date));
                $user->save();
                Session::flash('success_message', 'Successfully updated!');
                //return redirect('seller/setting');
				echo json_encode(array('status'=>true));
				} 
		
			 }
			 else
			 {
	            $user = User::find(Auth::user()->id);
                $user->is_available = $request->is_available;
				$user->from_date ="";
                $user->to_date =  "";
                $user->save();
                Session::flash('success_message', 'Successfully updated!');
				echo json_encode(array('status'=>true));
                //return redirect('seller/setting');
			 }
	}
}
