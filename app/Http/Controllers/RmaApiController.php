<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use App\User;
use App\Slider;
use App\UserProfile;
use App\UserSender;
use App\UserAddress;
use App\Enquiry;
use App\OrderRmaDetail;
use App\OrderExchange;
use App\OrderCancel;
use App\Category;
use App\UserProductShare;
use App\ProductCategory;
use App\Product;
use App\ProductImage;
use App\SubCategory;
use App\Cart;
use App\Order;
use App\OrderMeta;
use App\DeliveryPincode;
use DB;
use URL;
use Helper;
use Excel;
use Auth;
use File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
class RmaApiController extends Controller
{
	public function __construct()
	{
	  parent::__construct();
	}
	
/*..................Return...................................................................*/
	public function order_rma(Request $request)
     {
        $users = array(
            'id'    =>$request->input('id'),
            'order_id'    =>$request->input('order_id'),
            'reason'    =>$request->input('reason'),
            'account_holder_name'    =>$request->input('account_holder_name'),
            'account_number'    =>$request->input('account_number'),
            'ifsc_code'    =>$request->input('ifsc_code'),
            'address_id'    =>$request->input('address_id'),
            'comment'    =>$request->input('comment'),
            'status'    =>$request->input('status'),
        );
        $rules = array(
            'account_holder_name' =>'required',
            'account_number' =>'required',
            'address_id' =>'required',
            'ifsc_code' =>'required',
            'order_id' =>'required',
            'reason' =>'required',
            'id' =>'required',
        );
        $validator = Validator::make($users,$rules);
        if ($validator->fails()) {
            return Response::json(array(
                'status_code' => 0,
                'message' => 'validation error',
                'error_message'=>$validator->errors()->first(),
            ), 200);
        }else{
			try { 
			       
				    $response=DB::table('order_metas')->where('order_id', $users['order_id'])->where('status','shipped')->where('id', $users['id'])->update(['return_status' =>1,'message'=>'Processing']);
					if($response)
					{
						$cancelData['order_id']=  $users['order_id'];
						$cancelData['order_meta_id']=  $users['id'];
						$cancelData['reason']=  $users['reason'];
						$cancelData['comment']=  $users['comment'];
						$obj= new OrderCancel($cancelData);
						$obj->save();
						/*.....................start...........................*/
						$rmaData['order_id']=$cancelData['order_id'];
						$rmaData['order_rma_id']=$obj->id;
						$rmaData['order_meta_id']=$users['id'];
						$rmaData['account_number']=$users['account_number'];
						$rmaData['account_holder_name']=$users['account_holder_name'];
						$rmaData['ifsc_code']=$users['ifsc_code'];
						$rmaData['status']="Processing";
						$rmaData['address_id']=$users['address_id'];
						$obj= new OrderRmaDetail($rmaData);
						$obj->save();
						/*.......................end.........................*/
					}
					return Response::json(array(
						'status_code' => 1,
						'message' => 'Your request for return has been proceed successfully. Money will be refund into given account details with 2 to 3 business days',
					), 200);
				} 
				catch (\Exception $e) {
                   return Response::json(array(
						'status_code' => 0,
						'message' => $e->getMessage(),
					), 500);
				}
        }
    }
	
	/*..................Exchange...................................................................*/
	public function order_exchange(Request $request)
     {
        $users = array(
            'id'    =>$request->input('id'),
            'order_id'    =>$request->input('order_id'),
            'reason'    =>$request->input('reason'),
            'size'    =>$request->input('size'),
            'image'    =>$request->input('image'),
            'product_id'    =>$request->input('product_id'),
            'address_id'    =>$request->input('address_id'),
            'comment'    =>$request->input('comment'),
            'status'    =>$request->input('status'),
        );
        $rules = array(
            'size' =>'required',
            'image' =>'required',
            'product_id' =>'required',
            'address_id' =>'required',
            'order_id' =>'required',
            'reason' =>'required',
            'id' =>'required',
        );
        $validator = Validator::make($users,$rules);
        if ($validator->fails()) {
            return Response::json(array(
                'status_code' => 0,
                'message' => 'validation error',
                'error_message'=>$validator->errors()->first(),
            ), 200);
        }else{
			try { 
				    $response=DB::table('order_metas')->where('order_id', $users['order_id'])->where('status','shipped')->where('id', $users['id'])->update(['exchange_status' =>1,'message' =>'Processing']);
					if($response)
					{
						$cancelData['order_id']=  $users['order_id'];
						$cancelData['order_meta_id']=  $users['id'];
						$cancelData['reason']=  $users['reason'];
						$cancelData['comment']=  $users['comment'];
						$obj= new OrderCancel($cancelData);
						$obj->save();
						/*.....................Start...........................*/
						$pImage=ProductImage::where('id',$users['image'])->select('image')->first();
						$rmaData['order_id']=$cancelData['order_id'];
						$rmaData['order_rma_id']=$obj->id;
						$rmaData['order_meta_id']=$users['id'];
						$rmaData['product_id']=$users['product_id'];
						$rmaData['image']=$pImage['image'];
						$rmaData['size']=$users['size'];
						$rmaData['address_id']=$users['address_id'];
						$obj= new OrderExchange($rmaData);
						$obj->save();
						/*.......................end.........................*/
					}
					return Response::json(array(
						'status_code' => 1,
						'message' => 'Your request for exchange has been processed successfully',
					), 200);
				} 
				catch (\Exception $e) {
                   return Response::json(array(
						'status_code' => 0,
						'message' => $e->getMessage(),
					), 500);
				}
        }
    }
}