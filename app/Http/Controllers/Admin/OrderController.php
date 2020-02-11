<?php
namespace App\Http\Controllers\Admin; //admin add
use App\Http\Requests;
use App\Http\Controllers\Controller;   // using controller class
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Order;
use App\OrderReturnVideos;
use App\OrderMeta;
use App\OrderCancel;
use App\OrderRmaDetail;
use App\ResellerPayment;
use App\OrderExchange;
use App\OrderTracking;
use App\DeliveryBoyNotification;
use Carbon\Carbon;
use App\User;
use DB;
use SimpleXMLElement;
use DNS1D;
use DNS2D;
use PDF;
use URL;
use Excel;
use Helper;
use File;
use Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
class OrderController extends Controller
{
     var $sdata;
    public function __construct()
    {
		$data=Session::get('user_sdata');
		$this->sdata=Session::get('user_sdata');
		$this->warehouse_list=Helper::get_warehouse($data->id);
        $this->middleware('auth.admin:admin');
    }
	function new_shipping_charge($weight)
	{
		if ($weight <= 1000)
					return 70;
				else {
					$nWeight = $weight - 1000;
					if ($nWeight <= 500)
						return 100;//70+30
					else {
						$nAmount = 0;
						$reminder = (int) $nWeight % 500;
						if ($reminder == 0)
							$nAmount = (int) ($nWeight / 500)*30;
						else
							$nWeight = (int) ($nWeight / 500) *30 + 30;
						return $nAmount + 70 ;
					}

				  }
	}
	

    public function index()
    {
        return view("admin.order.index");
    }

    //get list of record of subadmin...........................................................
    public function getOrderData(Request $request)
    {
		$data=Session::get('user_sdata');
        $requestData = $_REQUEST;
        $columns = array(
            // column index  => database column name
            0 => 'orders.id',
            1 => 'orders.order_id',
            2 => 'orders.payment_amount',
            3 => 'orders.status',
            4 => 'orders.qty',
            5 => 'orders.payment_mode',
        );
        $totalUsers = Order::with('order_meta_data')->whereHas('order_meta_data', function ($query)
            {
                $query->whereIn('order_metas.status', array('pending','assign_to_rider', 'dispatched', 'ready_to_ship', 'to_be_dispatched'));
				
            });
        if($data->role==2)
		{
			$totalUsers=$totalUsers->whereIn('warehouse_id',array(explode(",",$this->warehouse_list)));
		}			
	  $totalUsers= $totalUsers->get()->count();
		
		$totalFiltered = $totalUsers;
        $users = Order::with('user','user_kyc','order_meta_data','address')->whereHas('order_meta_data', function ($query)
            {
                $query->whereIn('order_metas.status', array('pending','assign_to_rider', 'dispatched', 'ready_to_ship', 'to_be_dispatched'));
				
            })->orderBy('id', 'desc');
         if($data->role==2)
		{
			$users=$users->whereIn('warehouse_id',array(explode(",",$this->warehouse_list)));
		}			
		$searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value'])) {
            $users=$users->where(function($query) use ($searchString){
                return $query
                    ->where('order_id','LIKE','%'.$searchString.'%')
                    ->orWhereHas('address', function ($query) use ($searchString) {
                        $query->whereRaw("city  LIKE '%" . $searchString . "%'");
                    })
                ->orWhereHas('user', function ($query) use ($searchString) {
                    $query->whereRaw("mobile  LIKE '%" . $searchString . "%'");
                });
            });
            $totalFiltered = Order::where('order_id', 'LIKE', '%' . $searchString . '%')->get()->count();

        }
        $orderColumn = $columns[$requestData['order'][0]['column']];
        $orderColumnDir = $requestData['order'][0]['dir'];
        $limit = $requestData['length'];
        $offset = $requestData['start'];
        $users = $users->offset($offset)->limit($limit)->orderBy($orderColumn, $orderColumnDir)->get();
        $data1 = array();
        $i = $offset;
        foreach ($users as $item) {
            $i++;
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = $item->order_id;
            $nestedData[] = (!is_null($item->user_kyc)?$item->user_kyc->f_name." ".$item->user_kyc->l_name:"");
            $nestedData[] = (!is_null($item->user)?$item->user->mobile:"");
            $nestedData[] =$item->address['city'];
            $nestedData[] = $item->total_amount+$item->shipping_charge;
            $trakingStatus = Helper::getOrderTrackingStatus($item->id);
            $nestedData[] =  Helper::check_order_status($item->id);
            $nestedData[] = Helper::get_item_sum($item->id);
            $nestedData[] = Helper::get_qty_sum($item->id);
            $nestedData[] = $item->payment_mode;
            if($item->payment_status =='faild'){
                $pStatus = 'failed';
            }else{
                $pStatus = $item->payment_status;
            }
            $nestedData[] = $pStatus;
            $date = strtotime($item->created_at);
            $nestedData[] = date('d-m-Y h:i:s', $date);
            $deleteLink="";
            $viewLink="";
			if(Helper::check_action($data->id,'order','delete'))
			{
            $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Order Cancel"><i class="glyphicon glyphicon-remove"></i></a>';
            }
			if(Helper::check_action($data->id,'order','view'))
			{
			$viewLink = '<a href="' . URL::to('/') . '/admin/order/view-order/' . $item->id . ' " title="View Order"><i class="glyphicon glyphicon-eye-open"></i></a>';
            }
			
			$noteLink = '<a href="' . URL::to('/') . '/admin/order-note/'.$item->id.'" title="Create Note"><i class="fa fa-sticky-note"></i></a>';
			
			
			$nestedData[] =  $viewLink. " | ". $deleteLink." | ".$noteLink;
            $data1[] = $nestedData;

        }
        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalUsers),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data1
        );
        echo json_encode($json_data);

    }

    function create()
    {
        return view("admin.notice.add");
    }
    
    	
    function view($id)
    {
		  $order_data = Order::with('address','get_warehouse')->findOrFail($id);
		  $order_info = Order::with('address')->where('id',$id)->first();
		  $track_data= OrderTracking::where('order_id',$id)->get();
		  $ordermeta_data = OrderMeta::with('seller')->where('order_id',$id)->get();
		  $return_video= OrderReturnVideos::with('order_meta','order_rma_details','order_exchanges')->where('order_id',$id)->get();
        $deliveryBoy = DeliveryBoyNotification::with('get_delivery_boy')->where('type','warehouse_to_customer')->where('order_id',$id)->where('status','delivered')->first();
          return view("admin.order.view",compact('order_data','return_video','order_info','track_data','deliveryBoy'))->with('ordermeta_data',$ordermeta_data);
    }

	function view_exchange_order($id)
    {
		  $order_data = Order::with('sender','address')->findOrFail($id);
		  $ordermeta_data = OrderMeta::with('seller')->where('order_id',$id)->get();
		  $return_video= OrderReturnVideos::with('order_meta')->where('order_id',$id)->get();
		  //dd($return_video);
          return view("admin.order.view",compact('order_data','return_video'))->with('ordermeta_data',$ordermeta_data);
    }

    public function store(Request $request)
    {

        $noticeData = array(
            'heading'     => $request->input( 'heading'),
            'description'    => $request->input( 'description'),
        );
        $rules = array(
		'heading'=>'required',
		'description'=>'required',
		);
        $data = $request->all();
        $validator = Validator::make($noticeData, $rules);
        if ($validator->fails()) {
            return redirect('admin/notice/create-notice')->withInput()->withErrors($validator);
        }
        $notice = new Notice($request->all());
        //Upload Image
        // $image = $request->file('image');
        // if($image) {
            // $path_original = public_path() . '/admin/uploads/post';
            // $file = $request->image;
            // $photo_name = time() . '-' . $file->getClientOriginalName();
            // $file->move($path_original, $photo_name);
            // $post->image = $photo_name;
        // }
        $notice->save();
        Session::flash('success_message', 'Notice has been created successfully!');
        return redirect('admin/notice/notice-list');
    }
	
	function update_status($id=null)
    {
        $response=DB::statement("UPDATE notices SET status =(CASE WHEN (status = 1) THEN '0' ELSE '1' END) where id = $id");
        if($response) {
            Session::flash('success_message', 'status has been updated successfully!');
        }
        else {
            Session::flash('error_message', 'Unable to update status');
        }
        return redirect('/admin/notice/notice-list');
    }
	///cancelled order...................
    public function delete()
    {
		  $res=DB::table('order_metas')
                ->where('order_id', $_POST['id'])
                ->update(['status' => 'cancelled']);
        DB::table('orders')
            ->where('id', $_POST['id'])
            ->update(['status' => 'cancelled']);
        //add wallet amount
        $orderData = Order::where('id',$_POST['id'])->first();
        if($orderData->wallet_amount > 0){
            DB::table('wallets')->insert(['user_id'=>$orderData->user_id,'type'=>'deposit','amount'=>$orderData->wallet_amount,'reason'=>"Order Cancelled by admin",'payment_type'=>'add_balance']);
        }
        Helper::updateOrderStatus($_POST['id'],'cancelled','Order Cancelled by admin');
        if($res)
        {
                                
                                /*$userDetails=User::where('id',$order_details->user_id)->first();
                                $usersInfo=User::where('id',$userDetails['id'])->first();
                                $mmsg="Hi ".$userDetails['username'].",  \n";
                                $mmsg.="Here is you order number ".$order_details->order_id.". \n";
                                $mmsg.=" Your order has been Cancelled. \n";    
                                $mmsg.="Thanks Gracito";
                                Helper::send_msg($userDetails['mobile'],$mmsg);
                                            
                                // send mail....
                                $msg="Hi ".$userDetails['username'].", <br><br>";
                                $msg.="Here is you order number ".$order_details->order_id.". \n";
                                $msg.="Your order has been cancelled. \n";                          
                                $msg.="Thanks Gracito";
                                        
                                            $emailData = array(
                                                'to'        => array(strtolower($usersInfo['email'])),
                                                'from'      => 'support@gracito.com',
                                                'subject'   => 'Order Cancelled',
                                                'view'      => 'email.order-email',
                                                'content'=>$msg
                                            );
                                             Mail::send($emailData['view'], $emailData, function ($message) use ($emailData) {
                                                 $message
                                                     ->to($emailData['to'])
                                                     ->from($emailData['from'])
                                                     ->subject($emailData['subject']);

                                             }); */

            Session::flash('success_message', 'Order has been Cancelled successfully!');
        }
        else {
            Session::flash('error_message', 'Unable to cancelled the order');
        }
    }

    function download_invoice($id)
    {
        $orders = OrderMeta::with('order','order.address_details')->where('order_id',$id)->groupBy('order_id')->orderBy('created_at','desc')->first();
        $data['order']=$orders;
        $ordermeta_data = OrderMeta::with('seller')->where('order_id',$id)->get();
        $data['order_meta']=$ordermeta_data;
        $pdf = PDF::loadView('admin.order.pdf.order', $data);
        //$pdf->save(storage_path().'_'.$orders->order->order_id.'.pdf');
        return $pdf->download($orders->order->order_id.'.pdf');
    }
	///Cancelled Order
	public function cancelled_order()
    {
        return view("admin.order.cancelled_order");
    }

    //get list of record of subadmin...........................................................
    public function getCancelledOrderData(Request $request)
    {

		$data=Session::get('user_sdata');
        $requestData = $_REQUEST;
        $columns = array(
            // column index  => database column name
            0 => 'orders.id',
            1 => 'orders.order_id',
            2 => 'orders.payment_amount',
            3 => 'orders.status',
            4 => 'orders.qty',
            5 => 'orders.payment_mode',
        );
        $totalUsers = Order::with('order_meta_data')->whereHas('order_meta_data', function ($query)
            {
                $query->whereIn('order_metas.status', array('cancelled'));
				
            });
		 if($data->role==2)
		{
			$totalUsers=$totalUsers->whereIn('warehouse_id',array(explode(",",$this->warehouse_list)));
		}
		$totalUsers=$totalUsers->get()->count();
        $totalFiltered = $totalUsers;
        $users = Order::with('user','user_kyc','order_meta_data','address')->where('status','cancelled')->orderBy('id', 'desc');
         if($data->role==2)
		{
			$users=$users->whereIn('warehouse_id',array(explode(",",$this->warehouse_list)));
		}
		
		$searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value'])) {
            $users=$users->where(function($query) use ($searchString){
                return $query
                    ->where('order_id','LIKE','%'.$searchString.'%')
                    ->orWhereHas('address', function ($query) use ($searchString) {
                        $query->whereRaw("city  LIKE '%" . $searchString . "%'");
                    })
                    ->orWhereHas('user', function ($query) use ($searchString) {
                        $query->whereRaw("mobile  LIKE '%" . $searchString . "%'");
                    });
            });
            $totalFiltered = Order::where('order_id', 'LIKE', '%' . $searchString . '%')->get()->count();

        }
        $orderColumn = $columns[$requestData['order'][0]['column']];
        $orderColumnDir = $requestData['order'][0]['dir'];
        $limit = $requestData['length'];
        $offset = $requestData['start'];
        $users = $users->offset($offset)->limit($limit)->orderBy($orderColumn, $orderColumnDir)->get();
        $data1 = array();
        $i = $offset;
        foreach ($users as $item) {
            $i++;
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = $item->order_id;
            $nestedData[] = (!is_null($item->user_kyc)?$item->user_kyc->f_name." ".$item->user_kyc->l_name:"");
            $nestedData[] = (!is_null($item->user)?$item->user->mobile:"");
            $nestedData[] =$item->address['city'];
            $nestedData[] = $item->total_amount+$item->shipping_charge;
            //$trakingStatus = Helper::getOrderTrackingStatus($item->id);
            $nestedData[] = Helper::check_order_status($item->id);
            $nestedData[] =$item['reason'];
            $nestedData[] = Helper::get_item_sum($item->id);
            $nestedData[] = Helper::get_qty_sum($item->id);
            $nestedData[] = $item->payment_mode;
            $nestedData[] = $item->payment_status;
            $date = strtotime($item->created_at);
            $nestedData[] = date('d-m-Y h:i:s', $date);
            $deleteLink="";
            $viewLink="";
			if(Helper::check_action($this->sdata->id,'cancelled_order','delete'))
			{
            $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-remove"></i></a>';
            }
			if(Helper::check_action($this->sdata->id,'cancelled_order','view'))
			{
			$viewLink = '<a href="' . URL::to('/') . '/admin/order/view-order/' . $item->id . ' " title="View Order"><i class="glyphicon glyphicon-eye-open"></i></a>';
            }
			$noteLink = '<a href="' . URL::to('/') . '/admin/order-note/'.$item->id.'" title="Create Note"><i class="fa fa-sticky-note"></i></a>';
           
			$nestedData[] =  $viewLink." | ".$noteLink;
            $data1[] = $nestedData;

        }
        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalUsers),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data1
        );
        echo json_encode($json_data);

    }
	
	///Completed Order
	public function completed_order()
    {
        return view("admin.order.completed_order");
    }

    //get list of record of subadmin...........................................................
    public function getCompletedOrderData(Request $request)
    {
        $requestData = $_REQUEST;
        $columns = array(
            // column index  => database column name
            0 => 'orders.id',
            1 => 'orders.order_id',
            2 => 'orders.payment_amount',
            3 => 'orders.status',
            4 => 'orders.qty',
            5 => 'orders.payment_mode',
        );
        $totalUsers = Order::with('order_meta_data')->whereHas('order_meta_data', function ($query)
            {
                $query->whereIn('order_metas.status', array('delivered'));
				
            })->get()->count();
        $totalFiltered = $totalUsers;
        $users = Order::with('user','user_kyc','order_meta_data')->whereHas('order_meta_data', function ($query)
            {
                $query->whereIn('order_metas.status', array('delivered'));
				
            })->orderBy('id', 'desc');
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value'])) {
            $users=$users->where(function($query) use ($searchString){
                return $query
                    ->where('order_id','LIKE','%'.$searchString.'%')
                    ->orWhereHas('address', function ($query) use ($searchString) {
                        $query->whereRaw("city  LIKE '%" . $searchString . "%'");
                    })
                    ->orWhereHas('user', function ($query) use ($searchString) {
                        $query->whereRaw("mobile  LIKE '%" . $searchString . "%'");
                    });
            });
            $totalFiltered = Order::where('order_id', 'LIKE', '%' . $searchString . '%')->get()->count();

        }
        $orderColumn = $columns[$requestData['order'][0]['column']];
        $orderColumnDir = $requestData['order'][0]['dir'];
        $limit = $requestData['length'];
        $offset = $requestData['start'];
        $users = $users->offset($offset)->limit($limit)->orderBy($orderColumn, $orderColumnDir)->get();
        $data1 = array();
        $i = $offset;
        foreach ($users as $item) {
            $i++;
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = $item->order_id;
            $nestedData[] = (!is_null($item->user_kyc)?$item->user_kyc->f_name." ".$item->user_kyc->l_name:"");
            $nestedData[] = (!is_null($item->user)?$item->user->mobile:"");
            $nestedData[] = $item->address['city'];;
            //$nestedData[] = ($item->dock_no!="")?$item->dock_no."(".$item->shipped_by.")" :"";
            $nestedData[] = $item->total_amount+$item->shipping_charge;
            //$trakingStatus = Helper::getOrderTrackingStatus($item->id);
            $nestedData[] = Helper::check_order_status($item->id);
            $nestedData[] = Helper::get_item_sum($item->id);
            $nestedData[] = Helper::get_qty_sum($item->id);
            $nestedData[] = $item->payment_mode;
            if($item->payment_status =='faild'){
                $pStatus = 'failed';
            }else{
                $pStatus = $item->payment_status;
            }
            $nestedData[] = $pStatus;
            //$date = strtotime($item->shipped_date);
            //$nestedData[] = date('d-m-Y', $date);
            $nestedData[] = $item->order_meta_data[0]->delivery_date;
            $deleteLink="";
            $viewLink="";
			if(Helper::check_action($this->sdata->id,'completed_order','delete'))
			{
            $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-remove"></i></a>';
            }
			if(Helper::check_action($this->sdata->id,'completed_order','view'))
			{
			$viewLink = '<a href="' . URL::to('/') . '/admin/order/view-order/' . $item->id . ' " title="View Order"><i class="glyphicon glyphicon-eye-open"></i></a>';
            }
			$noteLink = '<a href="' . URL::to('/') . '/admin/order-note/'.$item->id.'" title="Create Note"><i class="fa fa-sticky-note"></i></a>';
          
			$nestedData[] =  $viewLink. " | ". $deleteLink." | ".$noteLink;
            $data1[] = $nestedData;

        }
        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalUsers),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data1
        );
        echo json_encode($json_data);

    }
	
	///Return/Exchange Order
	public function return_exchange_order()
    {
        return view("admin.order.return_exchange_order");
    }

    //get list of record of subadmin...........................................................
    public function getReturnOrderData(Request $request)
    {
        $requestData = $_REQUEST;
        $columns = array(
            // column index  => database column name
            0 => 'orders.id',
            1 => 'orders.order_id',
            2 => 'orders.payment_amount',
            3 => 'orders.status',
            4 => 'orders.qty',
            5 => 'orders.payment_mode',
        );
        $totalUsers = Order::with('order_meta_data')->whereHas('order_meta_data', function ($query)
            {
				$query->where('return_status',1)->orWhere('exchange_status',1);
            })->get()->count();
        $totalFiltered = $totalUsers;
        $users = Order::with('user','user_kyc','order_meta_data')->whereHas('order_meta_data', function ($query)
            {
			   $query->where('return_status',1)->orWhere('exchange_status',1);
				
            })->orderBy('id', 'desc');
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value'])) {


		    $users->where('order_id', 'LIKE', '%' . $searchString . '%');
            $totalFiltered = Order::where('order_id', 'LIKE', '%' . $searchString . '%')->get()->count();
        }
        $orderColumn = $columns[$requestData['order'][0]['column']];
        $orderColumnDir = $requestData['order'][0]['dir'];
        $limit = $requestData['length'];
        $offset = $requestData['start'];
        $users = $users->offset($offset)->limit($limit)->orderBy($orderColumn, $orderColumnDir)->get();
        $data = array();
        $i = $offset;
        foreach ($users as $item) {
            $i++;
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = $item->order_id;
            $nestedData[] = (!is_null($item->user_kyc)?$item->user_kyc->f_name." ".$item->user_kyc->l_name:"");
            $nestedData[] = (!is_null($item->user)?$item->user->mobile:"");
            $nestedData[] = ($item->dock_no!="")?$item->dock_no."(".$item->shipped_by.")" :"";
            $nestedData[] = $item->total_amount+$item->shipping_charge;
            $trakingStatus = Helper::check_order_status($item->id);
            $nestedData[] = str_replace('_',' ',$trakingStatus ? $trakingStatus->type : '');
            $nestedData[] = Helper::get_item_sum($item->id);
            $nestedData[] = Helper::get_qty_sum($item->id);
            $nestedData[] = $item->payment_mode;
            if($item->payment_status =='faild'){
                $pStatus = 'failed';
            }else{
                $pStatus = $item->payment_status;
            }
            $nestedData[] = $pStatus;
            $date = strtotime($item->created_at);
            $nestedData[] = date('d-m-Y h:i:s', $date);
            $deleteLink="";
            $viewLink="";
			if(Helper::check_action($this->sdata->id,'return_exchange_order','delete'))
			{
            $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-remove"></i></a>';
            }
			if(Helper::check_action($this->sdata->id,'return_exchange_order','view'))
			{
			$viewLink = '<a href="' . URL::to('/') . '/admin/order/view-order/' . $item->id . ' " title="View Order"><i class="glyphicon glyphicon-eye-open"></i></a>';
            }
			$noteLink = '<a href="' . URL::to('/') . '/admin/order-note/'.$item->id.'" title="Create Note"><i class="fa fa-sticky-note"></i></a>';
          
			$nestedData[] =  $viewLink. " | ". $deleteLink." | ".$noteLink;
            $data[] = $nestedData;

        }
        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalUsers),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        echo json_encode($json_data);

    }
	//return pending order

    public function return_pending_order()
    {
        return view("admin.order.return_pending_order");
    }

    //get list of record of subadmin...........................................................
    public function getReturnPendingOrderData(Request $request)
    {
        $requestData = $_REQUEST;
        $columns = array(
            // column index  => database column name
            0 => 'order_rma_details.id',
            1 => 'order_rma_details.order_id',
            2 => 'order_rma_details.is_approved',
            3 => 'order_rma_details.status',
            4 => 'order_rma_details.order_meta_id',
            5 => 'order_rma_details.product_id',
        );
        $totalUsers = OrderRmaDetail::where('is_approved',0)->get()->count();
        $totalFiltered = $totalUsers;
        $users = OrderRmaDetail::with('order','order.user_kyc','order.order_meta_data')->orderBy('id','desc')->where('is_approved',0);
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value'])) {


            $users->where('order_id', 'LIKE', '%' . $searchString . '%');
            $totalFiltered = Order::where('order_id', 'LIKE', '%' . $searchString . '%')->get()->count();
        }
        $orderColumn = $columns[$requestData['order'][0]['column']];
        $orderColumnDir = $requestData['order'][0]['dir'];
        $limit = $requestData['length'];
        $offset = $requestData['start'];
        $users = $users->offset($offset)->limit($limit)->orderBy($orderColumn, $orderColumnDir)->get();
        //print_r($users);die;
        $data = array();
        $i = $offset;
        foreach ($users as $item) {
            $i++;
            $nestedData = array();
            $nestedData[] = $i;
            $orderMeta = Helper::getOrderMeta($item->order_meta_id);
            $nestedData[] = $item->order->order_id;
            $nestedData[] = (!is_null($item->order->user_kyc)?$item->order->user_kyc->f_name." ".$item->order->user_kyc->l_name:"");

            $nestedData[] = $orderMeta->price*$orderMeta->qty;
            $nestedData[] = $item['reason'];
            $trakingStatus = 'return';//Helper::getOrderTrackingStatus($item->id);
            $nestedData[] = $trakingStatus;
            $nestedData[] = $orderMeta->qty;
            //$nestedData[] = Helper::get_qty_sum($item->order->id);
            $nestedData[] = $item->order->payment_mode;
            if($item->payment_status =='faild'){
                $pStatus = 'failed';
            }else{
                $pStatus = $item->payment_status;
            }
            $nestedData[] = $pStatus;
            $date = strtotime($item->order->created_at);
            $nestedData[] = date('d-m-Y h:i:s', $date);
            if($item->is_approved==1){
                $class="on"; $title="active";

            } else {
                $class="off"; $title="inactive";
            }
            $activateLink = '<a href="' . URL::to('/') . '/admin/order/approve-for-return/'.$item->order_meta_id.'/'.$item->order_id.'" title="'.$title.'"><i class="fa fa-toggle-'.$class.'" aria-hidden="true" ></i></a>';
            $viewLink = '<a href="' . URL::to('/') . '/admin/order/return-item/'.$item->order_meta_id . ' " title="View Item"><i class="glyphicon glyphicon-eye-open"></i></a>';
            //$viewLink = '<a href="' . URL::to('/') . '/admin/order/view-order/' . $item->order->id . ' " title="View Order"><i class="glyphicon glyphicon-eye-open"></i></a>';

            $nestedData[] =  $activateLink. ' | '. $viewLink;
            $data[] = $nestedData;

        }
        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalUsers),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        echo json_encode($json_data);

    }
    function return_item($id){
        $order_item = OrderMeta::where('id',$id)->first();
        return view("admin.order.return_item",compact('order_item'));
    }
    function approve_for_return($meta_id,$order_id)
    {
        //update wallet
        $order_info= OrderMeta::with('order')->where('id',$meta_id)->first();
        if($order_info){
             DB::table('order_rma_details')->where('order_id',$order_id)->where('order_meta_id',$meta_id)->update(['is_approved'=>1,'approved_date'=>date('Y-m-d'),'shipping_charge'=>$order_info->order->shipping_charge]);
             DB::table('order_metas')->where('id',$meta_id)->update(['status'=>'return']);
             DB::table('wallets')->insert(['user_id'=>$order_info->order->user_id,'amount'=>($order_info->price * $order_info->qty),'type'=>'deposit','payment_type'=>'return_order']);
             DB::table('seller_penalties')->insert(['order_id'=>$order_id,'order_meta_id'=>$meta_id,'seller_id'=>$order_info->order->seller_id,'amount'=>(($order_info->price * $order_info->qty)+$order_info->order->shipping_charge),'type'=>'return']);
            //update product qty.....
            $itemId = $order_info->item_id;
            $itemCancelQty = $order_info->qty;
            $getItemQty = DB::table('product_items')->where('id',$itemId)->first();
            $remainingQty = $getItemQty->qty;
            $updateQty = $itemCancelQty + $remainingQty;
            $updateItemQty =DB::table('product_items')->where('id',$itemId)->update(['qty' => $updateQty]);
        }

        Session::flash('success_message', 'Return Request has been approved successfully successfully!');

        return redirect('admin/order/return-approved');
    }
    //return approved order

    public function return_approved_order()
    {
        return view("admin.order.return_approved_order");
    }
 

    //get list of record of subadmin...........................................................
    public function getReturnApprovedOrderData(Request $request)
    {
        $requestData = $_REQUEST;
        $columns = array(
            // column index  => database column name
            0 => 'order_rma_details.id',
            1 => 'order_rma_details.order_id',
            2 => 'order_rma_details.is_approved',
            3 => 'order_rma_details.status',
            4 => 'order_rma_details.order_meta_id',
            5 => 'order_rma_details.product_id',
        );
        $totalUsers = OrderRmaDetail::where('is_approved',0)->get()->count();
        $totalFiltered = $totalUsers;
        $users = OrderRmaDetail::with('order','order_meta','order_rma','order.user_kyc','order.order_meta_data')->orderBy('id','desc')->where('is_approved',1);
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value'])) {


            $users->where('order_id', 'LIKE', '%' . $searchString . '%');
            $totalFiltered = Order::where('order_id', 'LIKE', '%' . $searchString . '%')->get()->count();
        }
        $orderColumn = $columns[$requestData['order'][0]['column']];
        $orderColumnDir = $requestData['order'][0]['dir'];
        $limit = $requestData['length'];
        $offset = $requestData['start'];
        $users = $users->offset($offset)->limit($limit)->orderBy($orderColumn, $orderColumnDir)->get();
        $data = array();
        $i = $offset;
        foreach ($users as $item) {
            $i++;
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = $item->order->order_id;
            $nestedData[] = (!is_null($item->order->user_kyc)?$item->order->user_kyc->f_name." ".$item->order->user_kyc->l_name:"");
            $nestedData[] = (!is_null($item->order->user)?$item->order->user->mobile:"");
            $nestedData[] = ($item->order_meta->price*$item->order_meta->qty);
            $nestedData[] = 1;
            $nestedData[] = $item->order->payment_mode;
            $nestedData[] = $item->order_rma->reason;
          
            $date = strtotime($item->order->created_at);
            $nestedData[] = date('d-m-Y h:i:s', $date);

            //$deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-remove"></i></a>';

            $viewLink = '<a href="' . URL::to('/') . '/admin/order/return-item/'. $item->order_meta_id . ' " title="View Order"><i class="glyphicon glyphicon-eye-open"></i></a>';

           // $noteLink = '<a href="' . URL::to('/') . '/admin/order-note/'.$item->id.'" title="Create Note"><i class="fa fa-sticky-note"></i></a>';

            $nestedData[] =  $viewLink;
            $data[] = $nestedData;

        }
        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalUsers),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        echo json_encode($json_data);

    }

  ///////////////////////////////   Exchange oredr   ///////////////////////////////


    public function exchange_pending_order()
    {
        return view("admin.order.exchange_pending_order");
    }

    //get list of record of subadmin...........................................................
    public function getExchangePendingOrderData(Request $request)
    {
        $requestData = $_REQUEST;
        $columns = array(
            // column index  => database column name
            0 => 'order_exchanges.id',
            1 => 'order_exchanges.order_id',
            2 => 'order_exchanges.is_approved',
            3 => 'order_exchanges.status',
            4 => 'order_exchanges.order_meta_id',
            5 => 'order_exchanges.product_id',
        );
        $totalUsers = OrderExchange::where('is_approved',0)->get()->count();
        $totalFiltered = $totalUsers;
        $users = OrderExchange::with('order','order.user_kyc','order.order_meta_data')->where('is_approved',0);
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value'])) {


            $users->where('order_id', 'LIKE', '%' . $searchString . '%');
            $totalFiltered = Order::where('order_id', 'LIKE', '%' . $searchString . '%')->get()->count();
        }
        $orderColumn = $columns[$requestData['order'][0]['column']];
        $orderColumnDir = $requestData['order'][0]['dir'];
        $limit = $requestData['length'];
        $offset = $requestData['start'];
        $users = $users->offset($offset)->limit($limit)->orderBy($orderColumn, $orderColumnDir)->get();
        $data = array();
        $i = $offset;
        foreach ($users as $item) {
            $i++;
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = $item->order->order_id;
            $nestedData[] = (!is_null($item->order->user_kyc)?$item->order->user_kyc->f_name." ".$item->order->user_kyc->l_name:"");
            $nestedData[] = (!is_null($item->user)?$item->order->user->mobile:"");

            $nestedData[] = $item->order->total_amount+$item->order->shipping_charge;
            $nestedData[] = $item['reason'];
            $trakingStatus = Helper::getOrderTrackingStatus($item->id);
            $nestedData[] = str_replace('_',' ',$trakingStatus ? $trakingStatus->type : '');
            $nestedData[] = Helper::get_item_sum($item->order->id);
            $nestedData[] = Helper::get_qty_sum($item->order->id);
            $nestedData[] = $item->order->payment_mode;
            if($item->payment_status =='faild'){
                $pStatus = 'failed';
            }else{
                $pStatus = $item->payment_status;
            }
            $nestedData[] = $pStatus;
            $date = strtotime($item->order->created_at);
            $nestedData[] = date('d-m-Y h:i:s', $date);
            if($item->is_approved==1){
                $class="on"; $title="active";

            } else {
                $class="off"; $title="inactive";
            }
            $activateLink = '<a href="' . URL::to('/') . '/admin/order/approve-for-exchange/'.$item->order_meta_id.'/'.$item->order_id.'" title="'.$title.'"><i class="fa fa-toggle-'.$class.'" aria-hidden="true" ></i></a>';
            $viewLink = '<a href="' . URL::to('/') . '/admin/order/view-order/' . $item->order->id . ' " title="View Order"><i class="glyphicon glyphicon-eye-open"></i></a>';
            // $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->order->id . ',this)" title="Delete"><i class="glyphicon glyphicon-remove"></i></a>';

            // $viewLink = '<a href="' . URL::to('/') . '/admin/order/view-order/' . $item->order->id . ' " title="View Order"><i class="glyphicon glyphicon-eye-open"></i></a>';

            //$noteLink = '<a href="' . URL::to('/') . '/admin/order-note/'.$item->order->id.'" title="Create Note"><i class="fa fa-sticky-note"></i></a>';

            $nestedData[] =  $activateLink. ' | '. $viewLink;
            $data[] = $nestedData;

        }
        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalUsers),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        echo json_encode($json_data);

    }

    //return approved order

    public function exchange_approved_order()
    {
        return view("admin.order.exchange_approved_order");
    }


    //get list of record of subadmin...........................................................
    public function getExchangeApprovedOrderData(Request $request)
    {
        $requestData = $_REQUEST;
        $columns = array(
            // column index  => database column name
            0 => 'order_exchanges.id',
            1 => 'order_exchanges.order_id',
            2 => 'order_exchanges.is_approved',
            3 => 'order_exchanges.status',
            4 => 'order_exchanges.order_meta_id',
            5 => 'order_exchanges.product_id',
        );
        $totalUsers = OrderExchange::where('is_approved',0)->get()->count();
        $totalFiltered = $totalUsers;
        $users = OrderExchange::with('order','order.user_kyc','order.order_meta_data')->where('is_approved',1);
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value'])) {


            $users->where('order_id', 'LIKE', '%' . $searchString . '%');
            $totalFiltered = Order::where('order_id', 'LIKE', '%' . $searchString . '%')->get()->count();
        }
        $orderColumn = $columns[$requestData['order'][0]['column']];
        $orderColumnDir = $requestData['order'][0]['dir'];
        $limit = $requestData['length'];
        $offset = $requestData['start'];
        $users = $users->offset($offset)->limit($limit)->orderBy($orderColumn, $orderColumnDir)->get();
        $data = array();
        $i = $offset;
        foreach ($users as $item) {
            $i++;
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = $item->order->order_id;
            $nestedData[] = (!is_null($item->order->user_kyc)?$item->order->user_kyc->f_name." ".$item->order->user_kyc->l_name:"");
            $nestedData[] = (!is_null($item->order->user)?$item->order->user->mobile:"");
            $nestedData[] = ($item->order->dock_no!="")?$item->order->dock_no."(".$item->order->shipped_by.")" :"";
            $nestedData[] = $item->order->total_amount+$item->order->shipping_charge;
            $trakingStatus = Helper::getOrderTrackingStatus($item->id);
            $nestedData[] = str_replace('_',' ',$trakingStatus ? $trakingStatus->type : '');
            $nestedData[] = Helper::get_item_sum($item->order->id);
            $nestedData[] = Helper::get_qty_sum($item->order->id);
            $nestedData[] = $item->order->payment_mode;
            if($item->payment_status =='faild'){
                $pStatus = 'failed';
            }else{
                $pStatus = $item->payment_status;
            }
            $nestedData[] = $pStatus;
            $date = strtotime($item->order->created_at);
            $nestedData[] = date('d-m-Y h:i:s', $date);

            //$deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-remove"></i></a>';

            $viewLink = '<a href="' . URL::to('/') . '/admin/order/view-order/' . $item->order->id . ' " title="View Order"><i class="glyphicon glyphicon-eye-open"></i></a>';

            // $noteLink = '<a href="' . URL::to('/') . '/admin/order-note/'.$item->id.'" title="Create Note"><i class="fa fa-sticky-note"></i></a>';

            $nestedData[] =  $viewLink;
            $data[] = $nestedData;

        }
        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalUsers),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        echo json_encode($json_data);

    }
    function approve_for_exchange($meta_id,$order_id)
    {
        $order_info= OrderMeta::with('order')->where('id',$meta_id)->first();
        $user_info= order::with('address','seller','seller_kyc')->where('id',$order_info->order_id)->first();
        $exchange_item=OrderExchange::where('order_meta_id',$meta_id)->first();

        $exchange_data['seller_id']=  $order_info->seller_id;
        $exchange_data['parent_id']=  $meta_id;
        $exchange_data['order_id']=  $order_info->order_id;
        $exchange_data['product_id']= $order_info->product_id;
        $exchange_data['item_id']= $order_info->item_id;
        $exchange_data['weight']= $order_info->weight;
        $exchange_data['price']= $order_info->price;
        $exchange_data['product_commission']= $order_info->product_commission;
        $exchange_data['cashback_amount']= $order_info->cashback_amount;
        $exchange_data['qty']= $order_info->qty;
        $exchange_data['product_image']= $order_info->product_image;
        $exchange_data['product_name']= $order_info->product_name;
        $exchange_data['is_return']= $order_info->is_return;
        $exchange_data['is_exchange']= $order_info->is_exchange;
        $exchange_data['cancel_request']= $order_info->cancel_request;
        $exchange_data['status']= 'pending';
            $obj= new OrderMeta($exchange_data);
            //$obj->save();
            DB::table('order_exchanges')->where('order_id',$order_id)->where('order_meta_id',$meta_id)->update(['is_approved'=>'1','approved_date'=>date('Y-m-d')]);
            //exchange_amount

        DB::table('order_metas')->where('order_id',$order_id)->where('id',$meta_id)->update(['status'=>'exchange']);
        DB::table('seller_penalties')->insert(['order_id'=>$order_id,'order_meta_id'=>$meta_id,'seller_id'=>$order_info->order->seller_id,'amount'=>$order_info->order->shipping_charge,'type'=>'exchange']);
         
		    $mmsg="Hi ".$user_info->address->name.",  \n";
		    $mmsg.="Your exchange request for order id ".$user_info->order_id."  \n";
			$mmsg.="has been accepted successfully. \n";
			$mmsg.="It will be processed within next 72 hours \n";    
			$mmsg.="Thanks Gracito";
			Helper::send_msg($user_info->address->mobile,$mmsg);
			
        /*$reseller_exchange= ResellerPayment::where('order_id',$order_id)->first();
        $data['exchange_amount']=$reseller_exchange->exchange_amount+($shipping*2);
        DB::table('reseller_payments')->where('order_id',$order_id)->update($data);*/
            Session::flash('success_message', 'Exchange Request has been approved successfully successfully!');

        return redirect('admin/order/exchange-approved');
    }
    /////////////////////////////////////////end exchange ////


	/////Return/Echange Order
	public function incompleted_order_list()
    {
        return view("admin.order.incompleted_order_list");
    }

    //get list of record of subadmin...........................................................
    public function getIncompletedOrderData(Request $request)
    {
        $requestData = $_REQUEST;
        $columns = array(
            // column index  => database column name
            0 => 'orders.id',
            1 => 'orders.order_id',
            2 => 'orders.payment_amount',
            3 => 'orders.status',
            4 => 'orders.qty',
            5 => 'orders.payment_mode',
        );
        $totalUsers = Order::with('order_meta_data')->whereHas('order_meta_data', function ($query)
            {
                $query->whereIn('order_metas.status', array('incomplete'));
				
            })->get()->count();
        $totalFiltered = $totalUsers;
        $users = Order::with('user','user_kyc','order_meta_data','address')->whereHas('order_meta_data', function ($query)
            {
                $query->whereIn('order_metas.status', array('incomplete'));
				
            })->orderBy('id', 'desc');
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value'])) {
            $users=$users->where(function($query) use ($searchString){
                return $query
                    ->where('order_id','LIKE','%'.$searchString.'%')
                    ->orWhereHas('address', function ($query) use ($searchString) {
                        $query->whereRaw("city  LIKE '%" . $searchString . "%'");
                    })
                    ->orWhereHas('user', function ($query) use ($searchString) {
                        $query->whereRaw("mobile  LIKE '%" . $searchString . "%'");
                    });
            });
            $totalFiltered = Order::where('order_id', 'LIKE', '%' . $searchString . '%')->get()->count();

        }
        $orderColumn = $columns[$requestData['order'][0]['column']];
        $orderColumnDir = $requestData['order'][0]['dir'];
        $limit = $requestData['length'];
        $offset = $requestData['start'];
        $users = $users->offset($offset)->limit($limit)->orderBy($orderColumn, $orderColumnDir)->get();
        $data = array();
        $i = $offset;
        foreach ($users as $item) {
            $i++;
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = $item->order_id;
            $nestedData[] = (!is_null($item->user_kyc)?$item->user_kyc->f_name." ".$item->user_kyc->l_name:"");
            $nestedData[] = (!is_null($item->user)?$item->user->mobile:"");
            $nestedData[] = $item->address['city'];
            $nestedData[] = $item->dock_no;
            $nestedData[] = $item->total_amount+$item->shipping_charge;
            //$trakingStatus = Helper::getOrderTrackingStatus($item->id);
            $nestedData[] = Helper::check_order_status($item->id);
            $nestedData[] = Helper::get_item_sum($item->id);
            $nestedData[] = Helper::get_qty_sum($item->id);
            $nestedData[] = $item->payment_mode;
            if($item->payment_status =='faild'){
                $pStatus = 'failed';
            }else{
                $pStatus = $item->payment_status;
            }
            $nestedData[] = $pStatus;
            $date = strtotime($item->created_at);
            $nestedData[] = date('d-m-Y h:i:s', $date);
            $deleteLink="";
            $viewLink="";
			if(Helper::check_action($this->sdata->id,'incompleted_order','delete'))
			{
            $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-remove"></i></a>';
            }
			if(Helper::check_action($this->sdata->id,'incompleted_order','view'))
			{
			$viewLink = '<a href="' . URL::to('/') . '/admin/order/view-order/' . $item->id . ' " title="View Order"><i class="glyphicon glyphicon-eye-open"></i></a>';
            }
			$noteLink = '<a href="' . URL::to('/') . '/admin/order-note/'.$item->id.'" title="Create Note"><i class="fa fa-sticky-note"></i></a>';
          
		$nestedData[] =  $viewLink. " | ". $deleteLink." | ".$noteLink;
            $data[] = $nestedData;

        }
        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalUsers),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        echo json_encode($json_data);

    }

	function view_return_reason(Request $request)
	{
		$id=$request->input('id');
		$data['data']=OrderCancel::where('order_meta_id',$id)->first();
		return view("admin.order.order_rma_reason",$data);
	}
	
	function unapprove_for_return($meta_id,$order_id)
	{
		 $msg="Your return has been unapproved";
	     DB::table('order_rma_details')->where('order_id',$order_id)->where('order_meta_id',$meta_id)->update(['is_approved'=>2,'status'=>$msg]); 
		 Session::flash('success_message', 'Return Request has been UnApproved!');	
		  return redirect('admin/order/view-order/'.$order_id); 		
	}

	function approve_for_exchange_old($meta_id,$order_id)
	{
	    $order_info= OrderMeta::with('order')->where('id',$meta_id)->first();
		$user_info= order::with('address','seller','seller_kyc')->where('id',$order_info->order_id)->first();
		$exchange_item=OrderExchange::where('order_meta_id',$meta_id)->first();
	      $response=(string)Helper::return_dtdc_order($order_info,$user_info);
		  $xml = new SimpleXMLElement($response);
		  if($xml->ORDER->Succeed=="Yes")
		  {
		    $exchange_data['size']= $exchange_item->size;
			$exchange_data['product_image']= $exchange_item->image;
			$exchange_data['product_id']= $exchange_item->product_id;
			$exchange_data['item_id']= $order_info->item_id;
			$exchange_data['weight']= $order_info->weight;
			$exchange_data['price']= $order_info->price;
			$exchange_data['qty']= $order_info->qty;
			$exchange_data['product_name']= $order_info->product_name;
			$exchange_data['shipping_free_amount']= $order_info->shipping_free_amount;
			$exchange_data['is_return']= $order_info->is_return;
			$exchange_data['is_in_exchange']= $order_info->is_in_exchange;
			$exchange_data['cancel_request']= $order_info->cancel_request;
			$exchange_data['status']= 'pending';
			$exchange_data['seller_id']=  $order_info->seller_id;
			$exchange_data['order_id']=  $order_info->order_id;
			$obj= new OrderMeta($exchange_data);
			$obj->save();
			DB::table('order_exchanges')->where('order_id',$order_id)->where('order_meta_id',$meta_id)->update(['status'=>'completed','approved_date'=>date('Y-m-d'),'dock_no'=>$xml->ORDER->DOCKNO,'message'=>$xml->ORDER->Reason]);
			//exchange_amount
			$order_date= date("d-m-Y",strtotime($order_info->created_at));
			$old_date= date("d-m-Y",strtotime('30-1-2018'));
			if($order_date<=$old_date)
			{	
				if($order_info->weight<=500)
				{
				$shipping=50;
				}
				else
				{
					$div= parseInt($order_info->weight%500);
					if($div == 0)
					{
						$shipping= parseInt(($order_info->weight/500))*50;
					}
					else
					{
						$shipping= parseInt(($order_info->weight/500))*50+50;
					}
				
				}
			}
			else
			{
			  $shipping=$this->new_shipping_charge($order_info->weight);
			   
			}
			DB::table('order_metas')->where('order_id',$order_id)->where('id',$meta_id)->
			update(['status'=>'exchange']);
			$reseller_exchange= ResellerPayment::where('order_id',$order_id)->first();
			$data['exchange_amount']=$reseller_exchange->exchange_amount+($shipping*2);
			DB::table('reseller_payments')->where('order_id',$order_id)->update($data);		
            Session::flash('success_message', 'Exchange Request has been approved successfully successfully!');				
		 }
		 else
		 {
			  Session::flash('error_message', 'Please Try Again!');
		 }
		  return redirect('admin/order/view-order/'.$order_id);
	}
	
	function get_exchange_order_details(Request $request)
	{
		$order_meta=$request->input('id');
		$data= OrderExchange::where('order_meta_id',$order_meta)->first();
		//dd($data);
		return view("admin.order.exchange_product_details")->with('data',$data);
	}
	
	function unapprove_for_exchange($meta_id,$order_id)
	{
		 $msg="Your exchange request has been unapproved";
		 DB::table('order_exchanges')->where('order_id',$order_id)->where('order_meta_id',$meta_id)->update(['status'=>'unapproved',
		'message'=>$msg]);
		    
			$order_info= OrderMeta::with('order')->where('id',$meta_id)->first();
            $user_info= order::with('address','seller','seller_kyc')->where('id',$order_info->order_id)->first();
			$mmsg="Hi ".$user_info->address->name.",  \n";
		    $mmsg.="Your exchange request for order id ".$user_info->order_id."  \n";
			$mmsg.="has been accepted successfully. \n";
			$mmsg.="It will be processed within next 72 hours \n";    
			$mmsg.="Thanks Gracito";
			Helper::send_msg($user_info->address->mobile,$mmsg);
			
		 Session::flash('success_message', 'Exchange Request has been unapproved!');
		 return redirect('admin/order/view-order/'.$order_id);		
	}
	function change_return_status()
	{
		$data=OrderRmaDetail::where('is_approved',2)->get();
		foreach($data as $vs)
		{
			//$data1=OrderRmaDetail::where('is_approved',1)->first();
			
		      //echo $vs->order_id."<br>";
			  
			 // DB::table('order_metas')->where('order_id',$vs->order_id)->where('id',$vs->order_meta_id)->update(['status'=>'shipped']);
			
			
		}
	}
}