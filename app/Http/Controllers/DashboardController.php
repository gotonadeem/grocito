<?PHP
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Auth;
use App\User;
use App\UserKyc;
use App\Order;
use App\Wishlist;
use App\Wallet;
use App\UserAddress;
use App\OrderMeta;
use App\OrderCancel;
use App\OrderRmaDetail;
use App\OrderExchange;
use App\UserComplaint;
use App\CallRequest;
use App\RaisingComplaint;
use App\ReferralSetting;
use App\AdminNotification;
use Validator;
use Response;
use Session;
use Input;
use Hash;
use Helper;
use URL;
use DB;
use PDF;
use PaytmWallet;
class DashboardController extends Controller
{
    public function __construct()
    {
       parent::__construct();
    }

    function my_account()
    {
       $address= UserAddress::where('user_id',Auth::user()->id)->get();
        $user= User::where('id',Auth::user()->id)->first();
       return view('front.dashboard.my_account',compact('address','user'));
    }
    function userSupport(){
        $complaint= UserComplaint::where('user_id',Auth::user()->id)->get();
        $raising= RaisingComplaint::where('user_id',Auth::user()->id)->get();
        return view('front.dashboard.user_support',compact('complaint','raising'));
    }
    public function add_support(Request $request)
    {
        $account['subject']= $request->subject;
        $account['user_id']= Auth::user()->id;
        $account['complaint_message']= $request->complaint_message;
        $account['complaint_id']= Auth::user()->id.mt_rand(1000, 9999);
        $rules = array(
            'subject' => 'required',
            'complaint_message' => 'required',
        );
        $validator = Validator::make($account, $rules);
        if ($validator->fails()) {
            return Response::json(array(
                'status' => 0,
                'message' => 'validation error',
                'error_message'=>$validator->errors()->first(),
            ), 200);
        }
        else
        {
            $obj= new UserComplaint($account);
            $obj->save();
            return Response::json(array(
                'status' => 1,
                'message' => 'Sent successfully',
                'error_message'=>'Sent successfully',
            ), 200);

        }
    }
    function return_now(Request $request)
    {
        $check= OrderCancel::where('order_id',$request->order_id)->where('order_meta_id',$request->meta_id)->get()->count();//order rma table.
        if(!$check>0)
        {
            $data['order_id']= $request->order_id;
            $data['order_meta_id']= $request->meta_id;
            $data['reason']= $request->reason;
            $obj= new OrderCancel($data);
            if($obj->save())
            {
                $detailsData['order_id']=$request->order_id;
                $detailsData['order_meta_id']=$request->meta_id;
                $detailsData['order_rma_id']=$obj->id;
                $detailsData['reason']=$request->reason;
                $detailsData['is_approved']=0;
                $rma= new OrderRmaDetail($detailsData);
                $rma->save();

                DB::table("order_metas")->where('id',$data['order_meta_id'])->update(['return_status'=>1]);
                //add notification for admin ..........
                $adminnotifyObj = new AdminNotification;
                $adminnotifyObj->int_val = $request->order_id;//order id
                $adminnotifyObj->type = 'order_return';
                $adminnotifyObj->message = 'Order Return';
                $adminnotifyObj->save();
                return Response::json(array(
                    'status_code' => 1,
                    'message' => 'successfully saved',
                ), 200);
            }
            else
            {
                return Response::json(array(
                    'status_code' => 0,
                    'message' => 'Please Try Again',
                ), 200);
            }
        }

    }
    function exchange_now(Request $request)
    {
        $check= OrderCancel::where('order_id',$request->order_id)->where('order_meta_id',$request->meta_id)->get()->count();//order rma table.
        if(!$check>0)
        {
            $data['order_id']= $request->order_id;
            $data['order_meta_id']= $request->meta_id;
            $data['reason']= $request->reason;
            $obj= new OrderCancel($data);
            if($obj->save())
            {
                $detailsData['order_id']=$request->order_id;
                $detailsData['order_meta_id']=$request->meta_id;
                $detailsData['order_rma_id']=$obj->id;
                $detailsData['reason']=$request->reason;
                $detailsData['is_approved']=0;
                $exchange= new OrderExchange($detailsData);
                $exchange->save();

                DB::table("order_metas")->where('id',$data['order_meta_id'])->update(['exchange_status'=>1]);
                //add notification for admin ..........
                $adminnotifyObj = new AdminNotification;
                $adminnotifyObj->int_val = $request->order_id;//order id
                $adminnotifyObj->type = 'order_exchange';
                $adminnotifyObj->message = 'Order Exchange';
                $adminnotifyObj->save();
                return Response::json(array(
                    'status_code' => 1,
                    'message' => 'successfully saved',
                ), 200);
            }
            else
            {
                return Response::json(array(
                    'status_code' => 0,
                    'message' => 'Please Try Again',
                ), 200);
            }
        }

    }
    function download_invoice($id)
    {
        $orders = OrderMeta::with('order','order.address_details')->where('order_id',$id)->groupBy('order_id')->orderBy('created_at','desc')->first();
        $data['order']=$orders;
        $ordermeta_data = OrderMeta::with('seller')->where('order_id',$id)->get();
        $data['order_meta']=$ordermeta_data;
        $pdf = PDF::loadView('front.dashboard.pdf.order', $data);
        $pdf->save(storage_path().'_'.$orders->order->order_id.'.pdf');
        return $pdf->download($orders->order->order_id.'.pdf');
    }
    function getAccountAddress(Request $request){
        $id= $request->input('address_id');
        $address= UserAddress::where('id',$id)->first();
        return view('front.dashboard.address_box_ajax',compact('address'));
    }
    public function updateProfile($id){
        $userData = User::with('user_kyc')->where('id',$id)->first();
        return view('front.dashboard.update_profile',compact('userData'));
    }
    public function updateProfileStore(Request $request){
        $userId = Auth::user()->id;

        $rules = array(
            'f_name'   =>'required',
            'l_name'   =>'required',
            'email'   =>'required',
        );

        $validator = Validator::make(Input::all(),$rules);
        if ($validator->fails()) {
            return redirect('update-profile/'.$userId)->withInput()->withErrors($validator);
        }else{
            $userData =array();
            $userProfile['email']= $request->input('email');
            $updateUser = User::find($userId)->fill($userProfile)->update();
            if($updateUser){
                $kycData['f_name'] = $request->input('f_name');
                $kycData['l_name'] = $request->input('l_name');
                $kycData['dob'] = $request->input('dob');

                //Upload Image
                if($request->profile_image){

                    $image = $request->file('profile_image');
                    $path_original=public_path() . '/front/user_profile';
                    $file = $request->profile_image;

                    $photo_name = time() . '-' . $file->getClientOriginalName();
                    $file->move($path_original, $photo_name);
                    $kycData['profile_image'] = $photo_name;
                    $userKyc=UserKyc::where('user_id', '=',$userId)->first();
                    $userKyc->update($kycData);
                }

            }
            Session::flash('success_message', 'Your profile has been added successfully');
        }
        return redirect('my-account');
    }
     function my_order()
    {
       $data= Order::with('order_meta_data')->where('user_id',Auth::user()->id)->orderBy('id', 'desc')->get();
       return view('front.dashboard.my_order',compact('data'));
    }


    function my_order_details($id)
    {
        $data= Order::with('order_meta_data','address')->where('id',$id)->first();
        $order_meta= OrderMeta::with('product','seller')->where('order_id',$id)->where('parent_id','0')->get();
        $userOtp = $user = DB::table('delivery_boy_notifications')->where('type','warehouse_to_customer')->where('status','requested')->where('order_id',$data->id)->first();
        return view('front.dashboard.order_detail',compact('data','order_meta','userOtp'));
    }
    function with_exchage_item($orderId,$orderMeteId){
        $data= Order::with('order_meta_data','address')->where('id',$orderId)->first();
        $order_meta= OrderMeta::with('product')->where('order_id',$orderId)->where('parent_id',$orderMeteId)->get();
        return view('front.dashboard.exchange_item',compact('data','order_meta'));
    }

	function my_wallet()
    {
		
		
		 
	   $total=0;
       $total= Helper::get_wallet(Auth::user()->id);
       $total_list= Wallet::with('user')->where('user_id',Auth::user()->id)->whereNotIn('payment_type', ['refer_and_earn'])->orderBy('id','desc')->get();
       $ref_wallet= Wallet::with('user')->where('user_id',Auth::user()->id)->where('payment_type','refer_and_earn')->get();
        $user =User::where('id',Auth::user()->id)->first();
       return view('front.dashboard.my_wallet',compact('total','total_list','ref_wallet','user'));
    }
    public function referEarn(){
        $userId = Auth::user()->id;
        $user = User::where('id',$userId)->first();
        $content = ReferralSetting::first();
        return view('front.dashboard.refer_earn',compact('user','content'));
    }
    public function sendEmailRefer(Request $request){
        $email = $request->input('email');
        $referCode = $request->input('refer_code');
        $message = $request->input('message');
        $link = Url::to('/');
        //send mail....
        $msg="Hello ".$email." <br><br>";
        $msg.="Referrel Code ".$referCode.".<br> \n";
        $msg.=" ".$message.".<br> \n";
        $msg.="Please follow this link ".$link."<br> .\n";
        $msg.="Thanks Grocito";

        $emailData = array(
            'to'        => array(strtolower($email)),
            'from'      => 'support@grocito.com',
            'subject'   => 'Referral Code',
            'view'      => 'email.order-email',
            'content'=>$msg
        );
        Mail::send($emailData['view'], $emailData, function ($message) use ($emailData) {
            $message
                ->to($emailData['to'])
                ->from($emailData['from'])
                ->subject($emailData['subject']);

        });
        return Response::json(array(
            'status_code' => 1,
            'message' => 'Send Successfully',
        ), 200);
    }
    public function sendCallRequest(){
        $id = Auth::user()->id;
        $data['user_id'] =$id;
        $obj = new CallRequest($data);

        if($obj->save()){
            //add notification for admin ..........
            $adminnotifyObj = new AdminNotification;
            $adminnotifyObj->int_val = $id;//user  id
            $adminnotifyObj->type = 'user_call_request';
            $adminnotifyObj->message = 'User Call Request';
            $adminnotifyObj->save();
            return Response::json(array(
                'status_code' => 1,
                'message' => 'Send Successfully',
            ), 200);
        }
        
    }
	function addWalletBalance(){

        return view('front.dashboard.add_wallet_balance');
    }
    function addWallet(Request $request){
        $id = Auth::user()->id;
        $totalAmount = $request->input('totalAmount');
        $razorpay_payment_id = $request->input('razorpay_payment_id');
        if($razorpay_payment_id){
            DB::table('wallets')->insert(['user_id'=>Auth::user()->id,'type'=>'deposit','amount'=>$totalAmount,'reason'=>"Deposited by Razorpay.Your transaction id is ".$razorpay_payment_id,'transaction_id'=>$razorpay_payment_id,'status'=>'razorpay','payment_type'=>'add_balance']);
            echo json_encode(array(
                'success' => true,
                'success_code' => 1,
            ));
        }else{
            echo json_encode(array(
                'success' => false,
                'success_code' => 0,
            ));
        }


    }
    function updateNotifyViewStatus(Request $request){
        $id = Auth::user()->id;
        DB::table('users')
            ->where('id', $id)
            ->update(['is_clear_notification_date' => date('Y-m-d H:i:s')]);
    }
	public function paymentCallback()
	{
		$transaction = PaytmWallet::with('receive');
		//print_r($transaction);die;
		$response = $transaction->response();
		$order_id = $transaction->getOrderId();

		if($transaction->isSuccessful()){

			DB::table('wallets')->insert(['user_id'=>Auth::user()->id,'type'=>'deposit','amount'=>10,'status'=>'paytm']);
			Session::flash('success_message', 'Amount Added Successfull.');
			return redirect('my-wallet');
		}else if($transaction->isFailed()){
			Session::flash('error_message', 'Please try again.');
			return redirect('my-wallet');
		}
	}
	function edit_address($id)
	{
		 $address= UserAddress::where('id',$id)->first();
		 $address_list= UserAddress::where('user_id',Auth::user()->id)->get();
		 return view('front.dashboard.address',compact('address','address_list'));
	}
	
	function wishlist()
	{
		 $wishlist= Wishlist::with('product','product.product_image')->where('user_id',Auth::user()->id)->get();
		 //dd($wishlist);
		 return view('front.dashboard.wishlist',compact('wishlist'));
	}
	
	function update_address($id,Request $request)
	{
		   // print_r($request->all()); die;
			$rules = array(
					'address'   =>'required',
					
					
				);
		
        $validator = Validator::make(Input::all(),$rules);
        if ($validator->fails()) {
             return redirect('edit-address/'.$id)->withInput()->withErrors($validator);
        }else{
			  
			  $data= $request->all();
			  $user = UserAddress::find($id)->fill($data)->update();
			  Session::flash('success_message', 'Your merchant has been added successfully');
			 return redirect('my-account'); 
		}
	}
    public function rating(Request $request){
        $productId = $request->product_id;
        $orderId = $request->order_id;
        return view('front.dashboard.review_rating',compact('productId','orderId'));
    }
	public function updateUserRatingReview(Request $request){
        try{
            $data['user_id'] = Auth::user()->id;
            $data['rating'] = $request->rating;
            $data['message'] = $request->review_msg;
            $data['product_id'] = $request->productId;
            $data['order_id'] = $request->orderId;
            DB::table('product_ratings')->insert($data);
            Session::flash('success_message', 'Rating and review submit successfully');
        }catch (\Exception $e){
            return $e->getMessage();
        }
    }
    public function userOrderCancle(Request $request){
        try{
            $orderId = $request->orderId;
            $orderData['reason'] = $request->reason;
            $orderData['status'] = 'cancelled';
            $orderMetaData['status'] = 'cancelled';

            DB::table('orders')->where('id',$orderId)->update($orderData);
            DB::table('order_metas')->where('order_id',$orderId)->update($orderMetaData);
            Helper::updateOrderStatus($orderId, 'cancelled', $orderData['reason']);
            //update item qty if order cancel.....
            $getOrderItem=OrderMeta::where('order_id',$orderId)->get();
            foreach ($getOrderItem as $meta){
                $itemId = $meta['item_id'];
                $itemCancelQty = $meta['qty'];
                $getItemQty = DB::table('product_items')->where('id',$itemId)->first();
                $remainingQty = $getItemQty->qty;
                $updateQty = $itemCancelQty + $remainingQty;
                $updateItemQty =DB::table('product_items')->where('id',$itemId)->update(['qty' => $updateQty]);
            }
            //add wallet amount
            $orderData = Order::where('id',$orderId)->first();
            if($orderData->wallet_amount > 0){
                DB::table('wallets')->insert(['user_id'=>$orderData->user_id,'type'=>'deposit','amount'=>$orderData->wallet_amount,'reason'=>"Order Cancelled",'payment_type'=>'add_balance']);
            }

            //add notification for admin ..........
            $adminnotifyObj = new AdminNotification;
            $adminnotifyObj->int_val = $orderId;//order id
            $adminnotifyObj->type = 'order_cancel';
            $adminnotifyObj->message = 'Order Cancel';
            $adminnotifyObj->save();
            Session::flash('success_message', 'Order cancelled successfully!');
        }catch (\Exception $e){
            return $e->getMessage().' Line No:'.$e->getLine();
        }
    }
//End ===============================
}