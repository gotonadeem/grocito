<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Auth;
use App\Order;
use App\UserAddress;
use App\Cart;
use DB;
use App\User;
use PaytmWallet;
use Validator;
use App\OrderMeta;
use App\Warehouse;
use App\ProductItem;
use App\SellerNotification;
use App\AdminNotification;
use App\Cashback;
use App\Pincode;
use App\DeliveryTime;
use Response;
use Session;
use Helper;
use Hash;
use URL;
use Razorpay\Api\Api;

class CheckoutController extends Controller
{
    protected $encrypt;
    public function __construct()
    {
        parent::__construct();
        $this->encrypt=md5($_SERVER['REMOTE_ADDR'].$_SERVER['SERVER_ADDR'].$_SERVER['SERVER_PORT'].$_SERVER['HTTP_USER_AGENT']);
    }

    public function check_login()
    {
        if(Auth::check())
        {
            if(Auth::user()->role_id==3)
            {
                return redirect('checkout');
            }
            else
            {
                return redirect('checkout-login');
            }
        }
        else
        {
            return redirect('checkout-login');
        }
    }

    public function login(Request $request)
    {
        return view('front.checkout.login');
    }

    public function authenticate(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        if (Auth::attempt(array('email' => $email, 'password' => $password,'role_id'=>3)))
        {
            $data=User::where('email',$request->email)->first();
            return redirect('checkout');
        }
        else
        {
            $request->flash();
            Session::flash('success_message', 'Invalid username or password');
            return redirect('checkout-login');
        }

    }

    public function register(Request $request)
    {
        return view('front.checkout.register');
    }

    public function register_user(Request $request)
    {
        // dd($request->all());

        $userData = array(
            'f_name'      => $request->input('fname'),
            'l_name'      => $request->input('lname'),
            'email'       => $request->input('user_email'),
            'password'    => $request->input('password'),
            'simple_pass' => $request->input('password'),
            'mobile'      => $request->input('mobile'),
            'login_type'  =>'email',
            'role_id'     => 3,
            'password_confirmation'=>$request->input('password_confirmation'),
        );
        $rules = array(
            'f_name'      =>  'required|max:20|regex:/^[a-zA-Z .\']+$/',
            'l_name'     =>   'required|max:20|regex:/^[a-zA-Z .\']+$/',
            'email'     =>    'required|email|unique:users',
            'mobile'    =>    'required|numeric|digits_between:8,10|unique:users,mobile',
            'password'  =>    'required|min:6|confirmed',
            'password_confirmation'=>'required|between:6,20',
        );
        $validator = Validator::make($userData,$rules);
        if($validator->fails())
            return redirect('checkout-register')->withInput()->withErrors($validator);
        else {
            $userData['password'] =  Hash::make($userData['password']);
            unset($userData['password_confirmation']);
            
            $userData['otp']= rand(12,66).rand(67,89);
            Session::set('user_data',$userData);
            
            $mmsg=" Use ".$userData['otp']." as one time password(OTP) to verify your account.  Do not share this OTP to anyone for security reasons.";
            Helper::send_msg($userData['mobile'],$mmsg);
/*
            $msg="Hi ".$userData['f_name'].", <br><br>  Thanx for Register on Grocito<br><br>";
            //$msg.="Here is your order number $order_number. \n";
            //$msg.="It will be dispatched soon. \n";
            $msg.="\n\n Thanks Grocito";

            $emailData = array(
                'to'        => array(strtolower($userData['email'])),
                'from'      => 'support@Grocito.com',
                'subject'   => 'Register',
                'view'      => 'email.order-email',
                'content'=>$msg
            );


            Mail::send($emailData['view'], $emailData, function ($message) use ($emailData) {
                $message
                    ->to($emailData['to'])
                    ->from($emailData['from'])
                    ->subject($emailData['subject']);

            });*/
            /* Mail Code End */
            return redirect('verify-checkout-otp');
        }
    }

    function verify_checkout_otp(Request $request)
    {
        $userData = array(
            'otp'      => $request->input('otp'),
        );
        $rules = array(
            'otp'      =>  'required',
        );
        $validator = Validator::make($userData,$rules);
        if($validator->fails())
            return redirect('verify-checkout-otp')->withInput()->withErrors($validator);
        else {
            $data=Session::get('user_data');
            if($data['otp']==$request->input('otp'))
            {
                $obj= new User($data);
                $obj->save();
                if ($obj->id) {
                    $data['user_id']=$obj->id;
                    $objKyc= new UserKyc($data);
                    $objKyc->save();

                    /* Mail Code Start */
                    $msg="Hi ".$request->fname.", <br><br>   Welcome to Grocito<br>";
                    $msg.="Your email id is ".$request->input('email')."<br>";
                    $msg.="Your Password is ".$request->input('password')."<br>";
                    $msg.="<br> <br>  Thanks Groctio";
                   
                   /* $emailData = array(
                        'to'        => $request->input( 'email'),
                        'from'      => 'support@Gracito.com',
                        'subject'   => 'Verify_OTP', 
                        'view'      => 'user.welcome-email',
                        'content'=>$msg
                    );
                     Mail::send($emailData['view'], $emailData, function ($message) use ($emailData) {
                    $message
                    ->to($emailData['to'])
                    ->from($emailData['from'])
                    ->subject($emailData['subject']);

                    });*/


                }
                return redirect('checkout-login');
            }

        }
    }



    public function index()
    {

        $default_address=array();
        $address_list=array();
        $pincode = session('pincode');
        $cityId = 0;
        if($pincode){
            $pincodeData = Pincode::where('pincode',$pincode)->first();
			$cityId = $pincodeData->city_id;
            $deliveryTime = DeliveryTime::where('city_id',$pincodeData->city_id)->first();
            
        }

        $my_wallet_amount=0;
        if(Auth::check())
        {
            $my_wallet_amount=Helper::get_wallet(Auth::user()->id);
            $address_list= UserAddress::where('pincode',session('pincode'))->where('user_id',Auth::user()->id)->get();
        }

        return view('front.checkout.index',compact('cityId','address_list','my_wallet_amount','deliveryTime'));

    }


    public function getNextOrderNumber()
    {
        // Get the last created order
        $lastOrder = Order::orderBy('id', 'desc')->first();
        if ( ! $lastOrder )
        {

            $number = 0;
        }
        else
        {
            $number = $lastOrder->id;
            return 'CORD' . sprintf('%06d', intval($number));
        }
    }
	
	public function getNextSubOrderNumber()
    {
            return '#SUBORD'.rand(12,34).rand(11,99).rand(1,9);
    }


    public function add_address(Request $request)
    {
        $userData = array(
            'name'        => $request->input('name'),
            'street'      => $request->input('street'),
            'house'       => $request->input('house'),
            'user_id'     => Auth::user()->id,
            'address'     => $request->input('address'),
            'lattitude'   => $request->input('lattitude'),
            'longitude'   => $request->input('longitude'),
            'city'        => $request->input('city'),
            'state'       => $request->input('state'),
            'pincode'     => session('pincode'),
            'type'        => $request->input('type'),
            'mobile'        => $request->input('mobile'),
        );

        $rules = array(
            'name'      =>  'required|max:100',
            'state'       =>   'required',
            'city'        =>   'required',
            'house'      =>   'required',
            'type'      =>   'required',
        );
        $validator = Validator::make($userData,$rules);
        if($validator->fails())
        {
            echo json_encode(array(
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
            ));
        }
        else {
            $objAddress= new UserAddress($userData);
            $objAddress->save();
            return Response::json(array(
                'status' => 1,
                'message' =>"Address has been added successfully",
            ), 200);
        }

    }

    public function edit_address(Request $request)
    {

        $userData = array(
            'name'      => $request->input('name'),
            'street'     => $request->input('street'),
            'house'     => $request->input('house'),
			'address'     => $request->input('address'),
            'type'        => $request->input('type'),
            'mobile'        => $request->input('mobile'),
        );
        $rules = array(
            'name'      =>  'required|max:100|regex:/^[a-zA-Z .\']+$/',
            'house'      =>    'required',
            'street'      =>   'required',
        );
        $validator = Validator::make($userData,$rules);
        if($validator->fails())
        {
            echo json_encode(array(
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
            ));
        }
        else {
            DB::table('user_addresses')->where('id',$request->id)->update($userData);
            return Response::json(array(
                'status' => 1,
                'message' =>"Address has been updated successfully",
            ), 200);
        }

    }
	
	function delete_address(Request $request)
	{
		 $id= $request->id;
         $data= DB::table('user_addresses')->where('id',$id)->delete();
		  return Response::json(array(
                'status' => 1,
                'message' =>"Address has been delete successfully",
            ), 200);
	}

    function get_address()
    {
		$pincode = session('pincode');
        $cityId = 0;
		$pincodeData = Pincode::where('pincode',$pincode)->first();
		$cityId = $pincodeData->city_id;
        $user_id= Auth::user()->id;
        $data= UserAddress::where('pincode',session('pincode'))->where('user_id',$user_id)->get();
        return view('front.checkout.get_address_ajax',compact('data','cityId'));
    }

    function get_address_by_id(Request $request)
    {
        $id= $request->id;
        $data= UserAddress::where('pincode',session('pincode'))->where('id',$id)->first();
        return view('front.checkout.edit_address_ajax',compact('data'));
    }


    public function checkout(Request $request)
    {

        $my_wallet_amount=0;
        $wallet_withdraw=0;
        $userData = array(
            'payment_mode'     => $request->input('payment_mode'),
            'delivery_date'     => $request->input('delivery_date'),
            'delivery_time'     => $request->input('delivery_time'),
        );

        $rules = array(
            'payment_mode'      =>  'required',
           // 'delivery_date'      =>  'required',
           // 'delivery_time'      =>  'required',
        );

        $validator = Validator::make($userData,$rules);

        if($validator->fails())
        {
            echo json_encode(array(
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
            ));
        }
        else {

            $orders['address_id']= Session::get('address_id');
            $pincode = Session::get('pincode');
            //to get warehouses id by pincode.
            $warehouses = \DB::table("warehouses")
                ->whereRaw("find_in_set($pincode,pincode)")
                ->first();
            if($warehouses){
                $warehousesId = $warehouses->id;
            }else{
                $warehousesId = 0;
            }
            DB::beginTransaction();
            try {
                $system_address=  $this->encrypt;
                $orderData=Cart::with('cart_product')->where('system_address',$system_address)->get();
                $sellerData=Cart::where('system_address',$system_address)->first();
                $orderCountData=Cart::with('cart_product')->where('system_address',$system_address)->get()->count();
                if($orderCountData>0)
                {
                    if (Auth::check()) {
                        $orders['user_id']= Auth::user()->id;
                    }
                    $orders['net_amount']=$request->net_amount;
                    $orders['sgst_amount']=$request->sgst_amount;
                    $orders['payment_mode']=$request->payment_mode;
                    $orders['delivery_date']=$request->delivery_date;
                    $orders['delivery_time']=$request->delivery_time;
                    $orders['delivery_type']=$request->delivery_type;
                    $orders['express_time']=$request->express_time;
                    $orders['wallet_amount']=$request->withdraw_wallet_amount;
                    $orders['status']='incomplete';
                    $orders['payment_status']='faild';
                    $orders['shipping_charge']=Session::get('delivery_charge');
                    $user = new Order($orders);
                    $user->save();
                    $order_number="#".$this->getNextOrderNumber();

                    $use_wallet=0;
                    $orderArray=array();
                    $payment_amount=0;
                    $sum=0;
                    $ssum=0;
                    $commission=0;
                    $admin_amount=0;
                    $admin_comm=0;
                    foreach($orderData as $vs):
					       $gstCal = $vs->gst_percentage + 100;
							$netAmount = round((((($vs->sprice>0)?$vs->sprice:$vs->price)*$vs->qty) *100) / $gstCal,2);
                        $ordData=array(
                            'order_id'=>$user->id,
                            'seller_id'=>$vs->seller_id,
                            'sub_order_id'=>$this->getNextSubOrderNumber(),
                            'net_amount'=>$netAmount,
                            'product_id'=>$vs->product_id,
                            'price'=>(($vs->sprice>0)?$vs->sprice:$vs->price),
                            'qty'=>$vs->qty,
                            'weight'=>$vs->weight,
                            'item_id'=>$vs->item_id,
                            'product_image'=>$vs->product_image,
                            'product_name'=>$vs->product_name,
                            'product_commission'=> ($vs->sprice * $vs->admin_commission)/100,
                            'is_return'=> $vs->is_return,
                            'is_exchange'=>$vs->is_exchange,
                            'attributes'=>$vs->attributes,

                        );

                        //$sell_price=$vs->cart_product->sell_price;
                        $sell_price=$vs->sprice;
                        $sum= $sum + $sell_price * $vs->qty;
                        $commission= $commission +  ($vs->sprice * $vs->admin_commission)/100;
                        $s=$vs->price;
                        $ssum= $ssum + $s*$vs->qty;
                        $SellerId = $vs->seller_id;
                        $orderArray[]=$ordData;
                       //get item qty for stock
                        $itemData = ProductItem::where('id',$vs->item_id)->first();
                        $itemQty = $itemData->qty;

                    //update stock qty in item table.....
                        if($itemQty >= $vs->qty){
                            $updateQtyData['qty'] = $itemQty - $vs->qty;
                            DB::table('product_items')->where('id',$vs->item_id)->update($updateQtyData);
                        }
                    endforeach;
                    if($request->payment_mode == 'razorpay') {
                        $api = new Api(config('custom.razor_key'), config('custom.razor_secret'));
                        $order = $api->order->create([
                            'receipt' => 'grocito_rcptid_11',
                            'amount' => ($sum *100), // amount in the smallest currency unit
                            'currency' => 'INR',// <a href="https://razorpay.freshdesk.com/support/solutions/articles/11000065530-what-currencies-does-razorpay-support" target="_blank">See the list of supported currencies</a>.)
                            'payment_capture' => '0'
                        ]);
                        $ord_payment_id = $order->id;
                    }else{
                        $ord_payment_id=uniqid().$user->id;
                    }

                    DB::table('orders')->where('id',$user->id)->update(['order_id' =>$order_number,'ord_payment_id'=>$ord_payment_id]);
                    OrderMeta::insert($orderArray);
                    DB::commit();
                    DB::table('order_metas')->where('order_id',$user->id)->update(['status' =>'pending']);
                    $orders['payment_amount']=$payment_amount;
                    $orders['total_amount']=$sum;
                    $orders['admin_commission']=$commission;
                    $orders['seller_id']=$SellerId;
                    $orders['warehouse_id']=$warehousesId;
                    DB::table('orders')->where('id',$user->id)->update($orders);
                    //update user wallet
                    if($request->withdraw_wallet_amount > 0) {
                        $walletDatap['amount'] = $request->withdraw_wallet_amount;
                        $walletDatap['type'] = 'withdraw';
                        $walletDatap['payment_type'] = 'placed_order';
                        $walletDatap['user_id'] = Auth::user()->id;
                        DB::table('wallets')->insert($walletDatap);
                    }
                    //add welcome cashback to user wallet if user placed first order
                    $userOrderCount = Order::where('user_id',Auth::user()->id)->count();
                    if($userOrderCount < 2){
                        $cashbackSetting = Cashback::first();
                        if($sum >= $cashbackSetting->welcome_min_order_value){
                            if($cashbackSetting->welcome_cashback_per > 0){
                                $cashbackAmount = ($sum * $cashbackSetting->welcome_cashback_per) / 100;
                                if($cashbackAmount > $cashbackSetting->upto_cashback){
                                    $cashbackAmount = $cashbackSetting->upto_cashback;
                                }
                                $walletDatap['amount'] = $cashbackAmount;
                                $walletDatap['type'] = 'deposit';
                                $walletDatap['payment_type'] = 'first_order_cashback';
                                $walletDatap['user_id'] = Auth::user()->id;
                                //DB::table('wallets')->insert($walletDatap);
                            }
                        }


                    }
                    //update order sataus....
                    Helper::updateOrderStatus($user->id,'pending','order placed');
                    //add notification for admin ..........
                    $adminnotifyObj = new AdminNotification;
                    $adminnotifyObj->int_val = $user->id;//order id
                    $adminnotifyObj->type = 'order_placed';
                    $adminnotifyObj->message = 'New Order Received';
                    $adminnotifyObj->save();
                    //add seller notification..........
                    $notifyObj = new SellerNotification;
                    $notifyObj->seller_id = $SellerId;
                    $notifyObj->int_val = $user->id;//order id
                    $notifyObj->type = 'order_placed';
                    $notifyObj->message = 'New Order Received';
                    $notifyObj->save();
                    Session::set('order_id',$ord_payment_id);
                    Session::set('amount',round($sum,2));
                    Session::set('mobile',Auth::user()->mobile);
                }
            }
            catch (\Exception $e) {
                DB::rollBack();
                return Response::json(array(
                    'status_code' => 0,
                    'message' => $e->getMessage(),

                ), 500);
            }
            echo json_encode(array(
                'success' => true,
                'success_code' => $request->payment_mode,
                'order_id' => $ord_payment_id,
                'amount' => $sum,
            ));
        }
    }

function distance($lat1, $lon1, $lat2, $lon2, $unit) {
  if (($lat1 == $lat2) && ($lon1 == $lon2)) {
    return 0;
  }
  else {
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);

    if ($unit == "K") {
      return ($miles * 1.609344);
    } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
      return $miles;
    }
  }
}
         function curl_get($url,  array $options = array())
			{
				$defaults = array(
					CURLOPT_URL => $url,
					CURLOPT_HEADER => 0,
					CURLOPT_RETURNTRANSFER => TRUE,
					CURLOPT_TIMEOUT => 4
				);

				$ch = curl_init();
				curl_setopt_array($ch, ($options + $defaults));
				if( ! $result = curl_exec($ch))
				{
					trigger_error(curl_error($ch));
				}
				curl_close($ch);
				return $result;
			}

		 function detect(Request $request)
		 {
				if(!empty($_POST['latitude']) && !empty($_POST['longitude'])){
					
					$lat= $_POST['latitude'];
					$long= $_POST['longitude'];
					$url="https://maps.google.com/maps/api/geocode/json?latlng=$lat,$long&key=AIzaSyBXeEpNyvOxirxB38hoys2_U7lTvQllS9g";
					$curl_return=$this->curl_get($url);
					$obj=json_decode($curl_return);
					$address=$obj->results[0]->formatted_address;
					 return Response::json(array(
							'status_code' =>1,
							'msg' =>$address,
							'message' =>"deliver Here",
						), 200);
				} 
		 }
 
    public function deliver_here(Request $request)
    {

        $id=$request->input('id');
        $city_id=$request->input('city_id');
        $delivery_type=$request->input('delivery_type');
        $totalAmount=$request->input('totalAmount');
		$delivery_type=  (($delivery_type!="")? $delivery_type:'standard');
		$data= UserAddress::where('id',$id)->first();
		$pincode= $data->pincode;
		$check = DB::table("warehouses")->select("lattitude",'longitude')->whereRaw("find_in_set($pincode,pincode)")->first();
		$delivery_charge = DB::table("delivery_charges")->where('type',$delivery_type)->where('city_id',$city_id)->first();
		//print_r($check);die;
		$lattitude= $check->lattitude;
		$longitude= $check->longitude;
		$distance=round($this->distance($lattitude, $longitude, $data->lattitude, $data->longitude, "K"),2);
        $radius_charge = $delivery_charge ?$delivery_charge->radius_charge :'20';
        $out_of_radius_charge = $delivery_charge ?$delivery_charge->out_of_radius_charge :'120';
        $radius = $delivery_charge ?$delivery_charge->radius :'10';
        if($totalAmount<=$delivery_charge->min_order) {

            if ($distance <= $radius) {
                $delivery_charge = $radius_charge;
            } elseif ($distance > $radius) {
                $delivery_charge = $distance * $out_of_radius_charge;
            }
        }else{
            $delivery_charge=0;
        }
		if($id)
        {
            Session::set('address_id',$id);
            Session::set('delivery_charge',$delivery_charge);
            return Response::json(array(
                'status_code' =>1,
                'delivery_charge' =>round($delivery_charge,2),
                'message' =>"deliver Here",
            ), 200);
        }
    }
	
	function deliver_type(Request $request)
	{
		$d_type=$request->input('id');
		$id=$request->input('d_address');
		$city_id=Session::get('city_id');
		$totalAmount=$request->input('totalAmount');
		if($id!="")
		{
			$data= UserAddress::where('id',$id)->first();
			$pincode= $data->pincode;
			$check = DB::table("warehouses")->select("lattitude",'longitude')->whereRaw("find_in_set($pincode,pincode)")->first();
			$delivery_charge = DB::table("delivery_charges")->where('type',$d_type)->where('city_id',$city_id)->first();
			$lattitude= $check->lattitude;
			$longitude= $check->longitude;
			$distance=round($this->distance($lattitude, $longitude, $data->lattitude, $data->longitude, "K"),2);
            if($totalAmount<=$delivery_charge->min_order) {
                if ($distance <= $delivery_charge->radius) {
                    $delivery_charge = $delivery_charge->radius_charge;
                } elseif ($distance > $delivery_charge->radius) {
                    $delivery_charge = $distance * $delivery_charge->out_of_radius_charge;
                }
            }else{
                $delivery_charge=0;
            }
			if($id)
			{
				Session::set('address_id',$id);
				Session::set('delivery_charge',$delivery_charge);
				return Response::json(array(
					'status_code' =>1,
					'delivery_charge' =>round($delivery_charge,2),
					'message' =>"deliver Here",
				), 200);
			}
		}
		else
		{
				return Response::json(array(
					'status_code' =>1,
					'delivery_charge' =>0,
					'message' =>"deliver Here",
				), 200);
		}
	}
	
    public function timeslot(Request $request)
    {
        Session::set('is_dated',true);
        Session::set('date',$request->date);
        Session::set('timelsot',$request->timeslot);
        return Response::json(array(
            'status_code' =>1,
            'message' =>"Time Slot",
        ), 200);
    }

    public function checkout_total(Request $request)
    {
        $my_wallet_amount=0;
        if(Auth::check()):
            $my_wallet_amount=Helper::get_wallet(Auth::user()->id);
        endif;

        $cart_id=$request->cart_id;
        $qty=$request->qty;
        $system_address=  $this->encrypt;
        $cart_data= Cart::with('cart_product','cart_image')->where('system_address',$system_address)->get();
        $check_exists= Cart::select('id','qty')->where('system_address',$system_address)->where('id',$cart_id)->first();
        if($check_exists)
        {
            $cartQty=array();
            $cartQty['qty']= $check_exists->qty+1;
            $cartObj= Cart::findOrFail($check_exists->id);
            if($cartObj->fill($cartQty)->save())
            {
                $cart_data= Cart::with('cart_product','cart_image')->where('system_address',$system_address)->get();
                return view('front.checkout.order_price_ajax',compact('cart_data','my_wallet_amount'));
            }
        }
    }
    public function checkout_total_minus(Request $request)
    {
        $my_wallet_amount=0;
        if(Auth::check()):
            $my_wallet_amount=Helper::get_wallet(Auth::user()->id);
        endif;

        $cart_id=$request->cart_id;
        $qty=$request->qty;
        $system_address=  $this->encrypt;
        $cart_data= Cart::with('cart_product','cart_image')->where('system_address',$system_address)->get();
        $check_exists= Cart::select('id','qty')->where('system_address',$system_address)->where('id',$cart_id)->first();
        if($check_exists)
        {
            $cartQty=array();
            $cartQty['qty']= $check_exists->qty-1;
            $cartObj= Cart::findOrFail($check_exists->id);
            if($cartObj->fill($cartQty)->save())
            {
                $cart_data= Cart::with('cart_product','cart_image')->where('system_address',$system_address)->get();
                return view('front.checkout.order_price_ajax',compact('cart_data','my_wallet_amount'));
            }
        }
    }

    public function order(Request $request)
    {
        $input['order_id'] =Session::get('order_id');
        $input['fee'] = Session::get('amount');
        $payment = PaytmWallet::with('receive');
        $payment->prepare([
            'order' => $input['order_id'],
            'user' => Auth::user()->id,
            'mobile_number' => Session::get('mobile'),
            'email' =>Auth::user()->email,
            'amount' => $input['fee'],
            'callback_url' => url('payment/status')
        ]);
        return $payment->receive();
    }


    public function paymentCallback()
    {
        $transaction = PaytmWallet::with('receive');
        $response = $transaction->response();
        //print_r($transaction); die;
        //print_r($response); die;
        $order_id = $transaction->getOrderId();
        //echo $order_id."<br>";
        //echo $transaction->getTransactionId()."<br>";
        // die;
        if($transaction->isSuccessful()){

            DB::table('orders')->where('id',$order_id)->update(['transaction_id'=>$transaction->getTransactionId(),'payment_status'=>'success','status'=>'pending']);

            $order_details=Order::where('id',$order_id)->select('order_id')->first();

            $system_address=  $this->encrypt;

            $cartCount = Cart::where('system_address',$system_address)->get()->count();
            if($cartCount>0)
            {

                //send sms....
                $order_number=$order_details->order_id;
                $usersInfo=User::where('id',Auth::user()->id)->first();
                
               /* $mmsg="Hi ".$usersInfo['username'].", \n thanks for placing your order with Grocito. \n";
                $mmsg.="Here if you order number $order_number. \n";
                $mmsg.=" It will be dispatched soon. \n";
                $mmsg.="\n\n Thanks Grocito";
                Helper::send_msg(Session::get('mobile'),$mmsg);*/

                //send mail....
                $msg="Hi ".$usersInfo['username'].", <br><br>  Thanx for placing order on Grocito<br><br>";
                $msg.="Here if you order number $order_number. \n";
                $msg.="It will be dispatched soon. \n";
                $msg.="\n\n Thanks Groctio";

                $emailData = array(
                    'to'        => array(strtolower($usersInfo['email'])),
                    'from'      => 'support@Grocito.com',
                    'subject'   => 'Order Placed',
                    'view'      => 'email.order-email',
                    'content'=>$msg
                );
                Mail::send($emailData['view'], $emailData, function ($message) use ($emailData) {
                    $message
                        ->to($emailData['to'])
                        ->from($emailData['from'])
                        ->subject($emailData['subject']);

                });
                Session::set('order_id','');
                Session::set('amount','');
                Session::set('mobile','');
                $cartRemove = Cart::where('system_address',$system_address);
                $cartRemove->delete();
            }
            else
            {
                return redirect('/');
            }
            return view('front.checkout.success',compact('order_details'));
        }else if($transaction->isFailed()){
            DB::table('orders')->where('id',$order_id)->update(['transaction_id'=>$transaction->getTransactionId(),'payment_status'=>'faild','status'=>'pending']);
            $order_details=Order::where('id',$order_id)->select('order_id')->first();
            return view('front.checkout.faild',compact('order_details'));
        }
    }

    public function cod_success()
    {
        $order_id= Session::get('order_id');
        DB::table('orders')->where('ord_payment_id',$order_id)->update(['payment_status'=>'cod','status'=>'pending']);
        $order_details=Order::where('ord_payment_id',$order_id)->select('order_id')->first();
        $system_address=  $this->encrypt;
        $cartCount = Cart::where('system_address',$system_address)->get()->count();
        if($cartCount>0)
        {

            //send sms....
            $order_number=$order_details->order_id;
            $usersInfo=User::where('id',Auth::user()->id)->first();
            $mmsg="Hi ".$usersInfo['username'].", \n thanks for placing your order with Grocito. \n";
            $mmsg.="Here is your order number $order_number. \n";
            $mmsg.=" It will be dispatched soon. \n";
            $mmsg.="\n\n Thanks Grocito";
            Helper::send_msg(Session::get('mobile'),$mmsg);
            //send sms to seller
            $orderData = Cart::where('system_address', $system_address)->groupBy('seller_id')->get();
            foreach ($orderData as $meta){
                $order_number = $order_details->order_id;
                $sellerInfo = User::where('id', $meta->seller_id)->first();
                $mmsg = "Congratulations! " . $sellerInfo['username'] . ", \n you have received new order. \n";
                $mmsg .= "Order id: $order_number. \n";
                $mmsg .= "\n\n Thanks Grocito";
                Helper::send_msg($sellerInfo['mobile'], $mmsg);

            }
            //send mail....
            $msg="Hi ".$usersInfo['username'].", <br><br>  Thanx for placing order on Grocito<br><br>";
            $msg.="Here is your order number $order_number. \n";
            $msg.="It will be dispatched soon. \n";
            $msg.="\n\n Thanks Grocito";

            $emailData = array(
                'to'        => array(strtolower($usersInfo['email'])),
                'from'      => 'support@Grocito.com',
                'subject'   => 'Order Successful',
                'view'      => 'email.order-email',
                'content'=>$msg
            );
            Mail::send($emailData['view'], $emailData, function ($message) use ($emailData) {
                $message
                    ->to($emailData['to'])
                    ->from($emailData['from'])
                    ->subject($emailData['subject']);

            });
            Session::set('order_id','');
            Session::set('amount','');
            Session::set('mobile','');
            Session::set('code','');
            Session::set('discount','');
            $cartRemove = Cart::where('system_address',$system_address);
            $cartRemove->delete();
        }
        else
        {

            return redirect('/');
        }
        return view('front.checkout.success',compact('order_details'));
    }
    public function razorpay_success(Request $request)
    {
        $order_id= $request->order_id;
        $razorpay_payment_id= $request->razorpay_payment_id;
        if($razorpay_payment_id) {
            DB::table('orders')->where('ord_payment_id', $order_id)->update(['payment_status' => 'success', 'status' => 'pending']);
            $order_details = Order::where('ord_payment_id', $order_id)->select('order_id')->first();
            $system_address = $this->encrypt;
            $cartCount = Cart::where('system_address', $system_address)->get()->count();
            if ($cartCount > 0) {

                //send sms....
                $order_number = $order_details->order_id;
                $usersInfo = User::where('id', Auth::user()->id)->first();
                $mmsg = "Hi " . $usersInfo['username'] . ", \n thanks for placing your order with Grocito. \n";
                $mmsg .= "Here is your order number $order_number. \n";
                $mmsg .= " It will be dispatched soon. \n";
                $mmsg .= "\n\n Thanks Grocito";
                Helper::send_msg(Session::get('mobile'), $mmsg);
                //send sms to seller
                $orderData = Cart::where('system_address', $system_address)->groupBy('seller_id')->get();
                foreach ($orderData as $meta){
                    $order_number = $order_details->order_id;
                    $sellerInfo = User::where('id', $meta->seller_id)->first();
                    $mmsg = "Congratulations! " . $sellerInfo['username'] . ", \n you have received new order. \n";
                    $mmsg .= "Order id: $order_number. \n";
                    $mmsg .= "\n\n Thanks Grocito";
                    Helper::send_msg($sellerInfo['mobile'], $mmsg);

                }
                //send mail....
                $msg = "Hi " . $usersInfo['username'] . ", <br><br>  Thanx for placing order on Grocito<br><br>";
                $msg .= "Here is your order number $order_number. \n";
                $msg .= "It will be dispatched soon. \n";
                $msg .= "\n\n Thanks Grocito";

                $emailData = array(
                    'to'        => array(strtolower($usersInfo['email'])),
                    'from'      => 'support@Grocito.com',
                    'subject'   => 'Order Successful',
                    'view'      => 'email.order-email',
                    'content'=>$msg
                );
                Mail::send($emailData['view'], $emailData, function ($message) use ($emailData) {
                    $message
                        ->to($emailData['to'])
                        ->from($emailData['from'])
                        ->subject($emailData['subject']);

                });
                Session::set('order_id', '');
                Session::set('amount', '');
                Session::set('mobile', '');
                Session::set('code', '');
                Session::set('discount', '');
                $cartRemove = Cart::where('system_address', $system_address);
                $cartRemove->delete();
            } else {

                return redirect('/');
            }
            echo json_encode(array(
                'success' => true,
                'success_code' => 1,
                'order_id' => $order_details->order_id,
            ));
        }else{
            DB::table('orders')->where('ord_payment_id',$order_id)->update(['payment_status'=>'faild','status'=>'incomplete']);
            $order_details=Order::where('ord_payment_id',$order_id)->select('order_id')->first();
            Session::set('order_id', '');
            Session::set('amount', '');
            Session::set('mobile', '');
            Session::set('code', '');
            Session::set('discount', '');
            $system_address = $this->encrypt;
            $cartRemove = Cart::where('system_address', $system_address);
            $cartRemove->delete();
            echo json_encode(array(
                'success' => false,
                'success_code' => 0,
                'order_id' => $order_details->id,
            ));
        }

    }
    public function raozarpayFaild(Request $request){
        $orderId=  $request->input('orderId');
        $order_details=Order::where('ord_payment_id',$orderId)->select('id','order_id')->first();
        DB::table('orders')->where('ord_payment_id',$orderId)->update(['payment_status'=>'faild','status'=>'incomplete']);
        DB::table('order_metas')->where('order_id',$order_details->id)->update(['status'=>'incomplete']);

        Session::set('order_id', '');
        Session::set('amount', '');
        Session::set('mobile', '');
        Session::set('code', '');
        Session::set('discount', '');
        $system_address = $this->encrypt;
        $cartRemove = Cart::where('system_address', $system_address);
        $cartRemove->delete();
        return view('front.checkout.razorpay_faild',compact('order_details'));
    }
    public function raozarpaySuccessPage(){
        return view('front.checkout.razorpay_success');
    }

}