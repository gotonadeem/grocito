<?php
namespace App\Http\Controllers\Admin; //admin add
use App\Http\Requests;
use App\Http\Controllers\Controller;   // using controller class
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\User;
use App\Order;
use App\Payment;
use App\UserWallet;
use App\OrderMeta;
use App\OrderReturnVideos;
use App\OrderRmaDetail;
use App\OrderExchange;
use App\OrderTracking;
use App\UserKyc;
use DB;
use Helper;
use URL;
use Excel;
use PDF;
use File;
use Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class PaymentController  extends Controller
{

    public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }

    public function index()
    {
        return view("admin.payment.index");
    }
    public function getPaymentData(Request $request)
    {
        $requestData = $_REQUEST;
        $columns = array(
            // column index  => database column name
            0 => 'order_metas.id',
            1 => 'order_metas.qty',
            2 => 'order_metas.price',
            
        );
        $totalUsers = OrderMeta::with('order','seller')->where('status',"delivered")->groupBy('seller_id')->get()->count();
        $totalFiltered = $totalUsers;
        $users = OrderMeta::with('order','seller')->where('status',"delivered")->groupBy('seller_id')->orderBy('id','DESC');
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
		 if(!empty($requestData['end_date']))
        {
            $start = date('Y-m-d',strtotime($requestData['start_date']));
            $end = date('Y-m-d',strtotime($requestData['end_date']));
            $users->whereBetween('created_at',  array($start, $end));
        }
        if (!empty($requestData['search']['value'])) {
           
		   //total filter data
		   $users->whereHas('seller', function ($query) use ($searchString)
            {
                 $query->whereRaw("username LIKE '%" .$searchString. "%'");
                 $query->orWhereRaw("mobile LIKE '%" .$searchString. "%'");
            });
			//total filter count
			$totalFiltered=OrderMeta::WhereHas('seller', function ($query) use ($searchString)
            {
                 $query->whereRaw("username LIKE '%" .$searchString. "%'");
				 $query->orWhereRaw("mobile LIKE '%" .$searchString. "%'");
				 
            })->get()->count();
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
			$deposit= Helper::get_wd_total_sum((!is_null($item->seller)?$item->seller->id:""),'deposit');
			$withdraw= Helper::get_wd_total_sum((!is_null($item->seller)?$item->seller->id:""),'withdraw');
			$id=(!is_null($item->seller)?$item->seller->id:"");
			$total_amount=Helper::get_seller_total_amount($id);
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = (!is_null($item->seller)?$item->seller->username:"");
            $nestedData[] = (!is_null($item->seller)?$item->seller->mobile:"");
            $nestedData[] = round($total_amount,2);
            $nestedData[] = round($total_amount-$deposit,2);
            $nestedData[] = round($deposit-$withdraw,2);
            $date = strtotime($item->delivery_date);
            $nestedData[] = date('d-m-Y', $date);
            
            $transactionLink = '<a href='.URL::to("/admin/payment/transaction_list/".$id).' title="View Transaction History"><i class="fa fa-history"></i></a>';
			$accountInfo='<a href="javascript:void(0)" onclick="get_account_information('.$id.')" title="View Account Information"><i class="fa fa-address-card-o"></i></a>';
            //$withdrawLink = '<a href="javascript:void(0)" id='.$id.' onclick="withdrawModel(this.id)" title="Withdraw"><i class="fa fa-arrow-circle-down"></i></a>';
            //$depositLink = '<a href="javascript:void(0)" id='.$id.' onclick="depositModel(this.id)" title="Deposit"><i class="fa fa-arrow-circle-up"></i></a>';
            $viewLink = '<a href="' . URL::to('/') . '/admin/payment/view-payment/' . $id . ' " title="View Payment"><i class="fa fa-google-wallet"></i></a>';
            $nestedData[] = $viewLink." | ".$transactionLink;


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

    ///delete subadmin...................
    public function delete()
    {
        $user = User::findOrFail($_POST['id']);
        if(!empty($user->delete()))
        {
            Session::flash('success_message', 'Subadmin has been deleted successfully!');
        }
        else {
            Session::flash('error_message', 'Unable to delete the subadmin');
        }
    }

    ///change deposit status...................
    function update_status($status=null,$id=null,$user_id=null)
    {

        $response=DB::statement("UPDATE working_wallets SET status =(CASE WHEN (status = '0') THEN '1' ELSE '0' END) where id = '$id'");
        if($response) {
            //$user_data=WorkingWallet::with('user')->findOrFail($user_id);
            $stud = DB::table('working_wallets')
                ->join('users','working_wallets.user_id','=','users.id')
                ->where('users.id','=',$user_id)
                ->where('working_wallets.id','=',$id)
                ->first();
            $msg="Hi, <br><br>   Welcome to Grocito<br><br>";
            $msg.="Your Deposit Coin has been Submited successfully.Please Check Your Working wallet <br>";
            $msg.="<br> <br>  Thanks EuroCoin";
            $emailData = array(
                'to'        => array($stud->email),
                'from'      => 'eurocoin@gmail.com',
                'subject'   => 'Deposit Successfully',
                'view'      => 'email.verification-email',
                'content'=>$msg
            );
            Mail::send($emailData['view'], $emailData, function ($message) use ($emailData) {
                $message
                    ->to($emailData['to'])
                    ->from($emailData['from'])
                    ->subject($emailData['subject']);

            });
            Session::flash('success_message', 'status has been updated successfully!');
        }
        else {
            Session::flash('error_message', 'Unable to update status');
        }
        return redirect('/admin/manage-deposit');
    }
	
    function withdraw_update_status($status=null,$id=null,$user_id)
    {
        $response=DB::statement("UPDATE withdrawal_wallets SET status =(CASE WHEN (status = '0') THEN '1' ELSE '0' END) where id = '$id'");
        if($response) {
            $stud = DB::table('withdrawal_wallets')
                ->join('users','withdrawal_wallets.user_id','=','users.id')
                ->where('users.id','=',$user_id)
                ->where('withdrawal_wallets.id','=',$id)
                ->first();
            $msg="Hi, <br><br>   Welcome to Careerganj<br><br>";
            $msg.="Your Withdraw Amount has been Submited successfully. <br>";

            $msg.="<br> <br>  Thanks Careerganj";
            $emailData = array(
                'to'        => array($stud->email),
                'from'      => 'Careerganj@gmail.com',
                'subject'   => 'Withdraw Successfully',
                'view'      => 'email.verification-email',
                'content'=>$msg
            );
            Mail::send($emailData['view'], $emailData, function ($message) use ($emailData) {
                $message
                    ->to($emailData['to'])
                    ->from($emailData['from'])
                    ->subject($emailData['subject']);

            });

            Session::flash('success_message', 'status has been updated successfully!');
        }
        else {
            Session::flash('error_message', 'Unable to update status');
        }
        return redirect('/admin/manage-withdraw');
    }
    function getExcelData()
    {
        DB::setFetchMode(\PDO::FETCH_ASSOC);
        $result = DB::select(DB::RAW('SELECT user_profiles.f_name,user_profiles.l_name,users.email,users.mobile,user_kyc.plan_amount,users.created FROM users INNER JOIN user_kyc ON users.id = user_kyc.user_id INNER JOIN user_profiles ON user_profiles.user_id = users.id where user_kyc.is_verified="yes" '));
        $filename = "Report.xls"; // File Name// Download file
        header("Content-Disposition: attachment;filename=\"$filename\"");
        header("Content-Type: application/vnd.ms-excel");
        for($i=0;$i<count($result);$i++)
        {
            
            if ($i==0) {

                echo implode("\t", array_keys($result[$i])) . "\r\n";
                
            }
            echo implode("\t", array_values($result[$i])) . "\r\n";
        }

    }
    public function export_to_excel()
    {

        $exportArray[] = ['S.No.','User Name','Email Address','Plan','Date'];

        $users = User::select('users.id','users.username','users.email','activation_wallets.coins','users.created')
            ->join('activation_wallets', 'users.id', '=', 'activation_wallets.user_id')->get();
        // set all records
        $i=0;
        foreach ($users as $record) {
            $i++;
            $exportArray[] = array(
                $i,
                $record->username,$record->email,$record->coins,
                $record->created
            );
        }
        Excel::create('user-'.date('Y-m-d_H:i:s',time()), function($excel) use ($exportArray) {
            $excel->setTitle('Users List');
            $excel->sheet('Sheet1', function($sheet) use ($exportArray) {
                $sheet->fromArray($exportArray, null, 'A1', false, false);
            });
        })->download('xls');
        die;
    }
	
	public function deposite(Request $request)
	{
		$data['user_id']=$request->input('user_id');
		$data['amount']=$request->input('amount');
		$data['type']="deposit";
		$obj= new Payment($data);
		if($obj->save())
		{
		echo json_encode(array('status'=>true,'message'=>'Deposited Successfully'));
		}
	}
	
	public function withdraw(Request $request)
	{
		$data['user_id']=$request->input('user_id');
		$data['amount']=$request->input('amount');
		$data['type']="withdraw";
		$obj= new Payment($data);
		if($obj->save())
		{
		echo json_encode(array('status'=>true,'message'=>'Withdraw Successfully'));
		}
	}
	
	public function transaction_list($id)
	{
		$user_data= User::findOrFail($id);
		return view('admin.payment.transaction_history',compact('user_data','id'));
	}
	
	public function getTransactionData(Request $request)
	{
	    $requestData = $_REQUEST;
        $columns = array(
            // column index  => database column name
            0 => 'seller_payments.id',
            1 => 'seller_payments.user_id',
            2 => 'seller_payments.amount',
            3 => 'seller_payments.type',
            
        );
        $totalUsers = Payment::with('seller_data')->where('user_id',$request->input('id'))->get()->count();
        $totalFiltered = $totalUsers;
        $users = Payment::with('seller_data')->where('user_id',$request->input('id'))->orderBy('id','desc');
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
		 if(!empty($requestData['end_date']))
        {
            $start = date('Y-m-d',strtotime($requestData['start_date']));
            $end = date('Y-m-d',strtotime($requestData['end_date']));
            $users->whereBetween('created_at',  array($start, $end));
        }
        if (!empty($requestData['search']['value'])) {
           
		   //total filter data
		   $users->where('product_name','LIKE','%'.$searchString.'%')->orWhereHas('seller', function ($query) use ($searchString)
            {
                 $query->whereRaw("	username LIKE '%" . $searchString . "%'");
                 $query->orWhereRaw("mobile LIKE '%" . $searchString . "%'");
            })->orWhereHas('order', function ($query) use ($searchString)
            {
                 $query->whereRaw("order_id LIKE '%" . $searchString . "%'");
            });
			//total filter count
			$totalFiltered=OrderMeta::where('product_name','LIKE','%'.$searchString.'%')->orWhereHas('seller', function ($query) use ($searchString)
            {
                 $query->whereRaw("username LIKE '%" . $searchString . "%'");
				 $query->orWhereRaw("mobile LIKE '%" . $searchString . "%'");
            })->orWhereHas('order', function ($query) use ($searchString)
            {
                 $query->whereRaw("order_id LIKE '%" . $searchString . "%'");
            })
                ->get()->count();
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
            $nestedData[] = $item->amount;
            $nestedData[] = $item->payment_type;
            $nestedData[] = $item->type;
            $nestedData[] = $item->transaction_id;
            $date = strtotime($item->created_at);
            $nestedData[] = date('d-m-Y h:i:s', $date);
          
            //$transactionLink = '<a href="javascript:void(0);" title="View Transaction History"><i class="fa fa-history"></i></a>';
            //$withdrawLink = '<a href="javascript:void(0)" id='.$item->seller->id.' onclick="withdrawModel(this.id)" title="Withdraw"><i class="fa fa-arrow-circle-down"></i></a>';
            //$depositLink = '<a href="javascript:void(0)" id='.$item->seller->id.' onclick="depositModel(this.id)" title="Deposit"><i class="fa fa-arrow-circle-up"></i></a>';
            //$viewLink = '<a href="' . URL::to('/') . '/admin/order/view-order/' . $item->id . ' " title="View Order"><i class="glyphicon glyphicon-eye-open"></i></a>';
            //$nestedData[] ="";


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
	
	
   public function payment_view($id)
   { 
       $data=User::with('user_kyc')->findOrFail($id);

	     $seller_order_date= Order::select(DB::raw("MIN(shipped_date) AS shipped_date"))->with('order_meta')->where('seller_id',$id)->where('shipped_date','!=','0000-00-00')->first();
       if($seller_order_date)
		  {
		 $date=date("Y-m-d",strtotime($seller_order_date->shipped_date));
       $response=DB::select("SELECT order_metas.delivery_date as shippedDate, 
             SUM(order_metas.price * order_metas.qty) AS total,
              SUM(order_metas.product_commission) AS total_admin_commission,
              SUM(order_metas.net_amount) AS net_amount
               
		FROM 
			orders inner join order_metas on orders.id= order_metas.order_id
		WHERE order_metas.seller_id='$id' and (order_metas.status='delivered' or orders.status='return' or orders.status='exchange')
		GROUP BY
			(order_metas.delivery_date) ORDER BY (order_metas.delivery_date) DESC");
         /*  echo "<pre>";
			
			print_r($response); die;*/
		 
      	return view("admin.payment.payment_view",compact('response','data'));
		  }
		  else
		  {
			return view("admin.payment.payment_view_sample");  
		  }
   }
   
   
   
   
   
   public  function pay_now(Request $request)
   {
	    $seller_details= User::with('user_kyc')->where('id',$request->input('id'))->first();
	    $data['user_id']=$request->input('id');
		$data['order_date']=$request->input('shippedDate');
		$data['amount']=$request->input('total_payable_amount');
		$data['tcs_amount']=$request->input('tcs_amount');
		$data['transaction_id']=$request->input('transaction_id');
		$data['bank_name']=$request->input('bank_name');
		$data['type']="deposit";
		$data['commission']=$request->input('totalAdminCommission');//admin commission
		$obj= new Payment($data);
		if($obj->save())
		{
		
				$data['user']= $seller_details;
				$pdf = PDF::loadView('admin.payment.pdf.invoice', $data);
				$label= public_path().'/invoices/'.time().'.pdf';
				$pdf->save($label);
		
		        $msg="Hi ".$seller_details->user_kyc->f_name.", <br><br>   Payment From Grocito<br><br>";
                $msg.="Your Payment has been credited.";
                $msg.="<h2>Your Grocito Payment</h2>";
		        $emailData = array(
                    'to'        => array($seller_details->email),
                    'from'      => 'support@grocito.com',
                    'subject'   => "Grocito Payment",
                    'view'      => 'email.seller-email',
                    'content'=>$msg
                );
             /*  $status=Mail::send($emailData['view'], $emailData, function ($message) use ($emailData,$label) {
                    $message
                        ->to($emailData['to'])
                        ->from($emailData['from'])
                        ->subject($emailData['subject'])
                        ->attach($label, array(
						'as' => 'payment.pdf', 
						'mime' => 'application/pdf')
					);

                });*/
				
			 Session::flash('success_message', 'Payment Completed successfully!');
		     echo json_encode(array('status'=>true,'message'=>'Paid Successfully'));
		}
   }
   
   
   public  function settle_now(Request $request)
   {
	    $seller_details= User::with('user_kyc')->where('id',$request->input('user_id'))->first();
	    $data['user_id']=$request->input('user_id');
		$data['amount']=$request->input('amount');
		$data['week_number']=$request->input('week_number');
		$data['from_date']=date('Y-m-d',strtotime($request->input('start_date')));
		$data['to_date']=date('Y-m-d',strtotime($request->input('end_date')));
		$data['transaction_id']=$request->input('transaction_id');
		$data['payment_type']='settlement';
		$data['bank_name']=$request->input('bank_name');
		$data['type']="deposit";
		$data['commission']=0;
		$data['seller_commission']=0;
		$obj= new Payment($data);
		if($obj->save())
		{
		
				$data['user']= $seller_details;
				$pdf = PDF::loadView('admin.payment.pdf.invoice', $data);
				$label= public_path().'/invoices/'.time().'.pdf';
				$pdf->save($label);
		
		        $msg="Hi ".$seller_details->user_kyc->f_name.", <br><br>   Payment From Cartlay<br><br>";
                $msg.="Your Payment has been credited.";
                $msg.="<h2>Your Cartlay Payment</h2>";
		        $emailData = array(
                    'to'        => array($seller_details->email),
                    'from'      => 'support@cartlay.com',
                    'subject'   => "Cartlay Payment",
                    'view'      => 'email.seller-email',
                    'content'=>$msg
                );
                $status=Mail::send($emailData['view'], $emailData, function ($message) use ($emailData,$label) {
                    $message
                        ->to($emailData['to'])
                        ->from($emailData['from'])
                        ->subject($emailData['subject'])
                        ->attach($label, array(
						'as' => 'payment.pdf', 
						'mime' => 'application/pdf')
					);

                });
				
			 Session::flash('success_message', 'Payment Completed successfully!');
		     echo json_encode(array('status'=>true,'message'=>'Paid Successfully'));
		}
   }
   
   public function get_order_list($id1,$id3)
   {
	    $data=User::findOrFail($id3);
		
	    return view('admin.payment.order_list',compact('data','id1','id2','id3'));
   }
  
 
   public function get_order_ajax_list(Request $request)
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
       $sellerId = $request->input('seller_id');
       $totalUsers = Order::with('order_meta_data')->whereHas('order_meta_data', function ($query) use ($sellerId,$request)
            {
                $query->where('delivery_date',date('Y-m-d',strtotime($request->input('shipped_date'))))->whereIn('order_metas.status', array('delivered','return','exchange'))->where('seller_id',$sellerId);
				
            })->get()->count();
        $totalFiltered = $totalUsers;
        $users = Order::with('user','user_kyc','order_meta_data')->whereHas('order_meta_data', function ($query) use($sellerId,$request)
            {
                $query->where('delivery_date',date('Y-m-d',strtotime($request->input('shipped_date'))))->whereIn('order_metas.status', array('delivered','return','exchange'))->where('seller_id',$sellerId);
				
            })->orderBy('id', 'desc');
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value'])) {
           
		    $users->where('order_id', 'LIKE', '%' . $searchString . '%')->orWhereHas('address', function ($query) use ($searchString)
            {
                $query->whereRaw("user_addresses.name  LIKE '%" . $searchString . "%'")->orWhereRaw("user_addresses.mobile  LIKE '%" . $searchString . "%'");
				
            });
            $totalFiltered = Order::where('order_id', 'LIKE', '%' . $searchString . '%')->orWhereHas('address', function ($query) use ($searchString)
            {
                $query->whereRaw("user_addresses.name  LIKE '%" . $searchString . "%'")->orWhereRaw("user_addresses.mobile  LIKE '%" . $searchString . "%'");
				
            })
                ->get()->count();
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
            $trackingData = Helper::getOrderTrackingStatus($item->id);
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = $item->order_id;
            $nestedData[] = (!is_null($item->user_kyc)?$item->user_kyc->f_name." ".$item->user_kyc->l_name:"");
            $nestedData[] = (!is_null($item->user)?$item->user->mobile:"");
            $totalAmount = Helper::get_seller_product_sum($item->id);
            $nestedData[] = $totalAmount->total;
            $nestedData[] =$trackingData->type;
            $nestedData[] = Helper::get_item_sum($item->id);
            $nestedData[] = Helper::get_qty_sum($item->id);
            $nestedData[] = $item->payment_mode;
            //$nestedData[] = $item->payment_status;
            $date = strtotime($item->order_meta_data[0]->delivery_date);
            $nestedData[] = date('d-m-Y', $date);
          
            //$deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-remove"></i></a>';
            $viewLink = '<a href="' . URL::to('/') . '/admin/order/view-order/' . $item->id . ' " title="View Order"><i class="glyphicon glyphicon-eye-open"></i></a>';
            //$noteLink = '<a href="' . URL::to('/') . '/admin/order-note/'.$item->id.'" title="Create Note"><i class="fa fa-sticky-note"></i></a>';
           
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
   
     public function reseller_payment_list()
   {
	  return view('admin.reseller_payment.index');
   }
   
   public function getResellerPaymentData()
   {
	   $requestData = $_REQUEST;
        $columns = array(
            // column index  => database column name
            0 => 'user_wallets.id',
            1 => 'user_wallets.user_id',
            2 => 'user_wallets.amount',
            3 => 'user_wallets.type',
            
        );
        $totalUsers = UserWallet::get()->count();
        $totalFiltered = $totalUsers;
        $users = UserWallet::with('user','user_kyc')->orderBy('created_at','desc');
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
		 if(!empty($requestData['end_date']))
        {
            $start = date('Y-m-d',strtotime($requestData['start_date']));
            $end = date('Y-m-d',strtotime($requestData['end_date']));
            $users->whereBetween('created_at',  array($start, $end));
        }
        if (!empty($requestData['search']['value'])) {
           
		   //total filter data
		   $users->whereHas('user', function ($query) use ($searchString)
            {
                 $query->whereRaw("email LIKE '%" . $searchString . "%'");
                 $query->orWhereRaw("mobile LIKE '%" . $searchString . "%'");
            });
			//total filter count
			$totalFiltered=UserWallet::with('user')->whereHas('user', function ($query) use ($searchString)
            {
                 $query->whereRaw("email LIKE '%" . $searchString . "%'");
				 $query->orWhereRaw("mobile LIKE '%" . $searchString . "%'");
            })
                ->get()->count();
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
            $nestedData[] = $item->user_kyc->f_name." ".$item->user_kyc->l_name;
            $nestedData[] = $item->user->email;
            $nestedData[] = $item->user->mobile;
            $nestedData[] = $item->amount;
            //$nestedData[] = $item->type;
			 if($item->status==0)
			 {
				$status= "<span style='color:coral'>Pending</span>"; 
			 }
			 elseif($item->status==1)
			 {
				 $status= "<span style='color:green'>Approved</span>"; 
			 }
			 elseif($item->status==2)
			 {
				 $status= "<span style='color:red'>Rejected</span>"; 
			 }
			 
            $nestedData[] =  $status;
            $date = strtotime($item->created_at);
            $nestedData[] = date('d-m-Y', $date);
			if($item->status==1){ 
			    $class="on"; $title="active";
			} else { 
			     $class="off"; $title="inactive";
     		}
			
			//<a href="' . URL::to('/') . '/admin/payment/update-reseller-payment-status/'.$item->id.'" title="'.$title.'"><i class="fa fa-toggle-'.$class.'" aria-hidden="true" ></i></a>
            $activateLink = "<select onchange='change_status(this.value)'>
		<option ".(($item->status==0)?'selected':'')." value='0,".$item->id."'>Pending</option>
			                 <option ".(($item->status==1)?'selected':'')." value='1,".$item->id."'>Approved</option>
			                 <option ".(($item->status==2)?'selected':'')." value='2,".$item->id."'>Rejected</option>
							 </select>";
			$viewLink = '<a href="javascript:void(0)" onClick="get_account_information('.$item->user_kyc->user_id.')" title="View Account Information"><i class="fa fa-address-card-o"></i></a>';
			$history = '<a href="'.URL::to('admin/reseller-payment-history/'.$item->user_kyc->user_id).'" title="View Payment History"><i class="fa fa-history" aria-hidden="true"></i></a>';
            $nestedData[] =$activateLink ." | ".$viewLink." | ".$history;
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
   
   function update_reseller_payment_status(Request $request)
   {
	   $id= $request->input('id');
	   $status= $request->input('status');
	   DB::statement("UPDATE user_wallets SET status ='$status' where id = '$id'");  
       Session::flash('success_message', 'status has been updated successfully!');
	    $json_data = array(
            "status" =>1
        );
        echo json_encode($json_data);  
       //return redirect('admin/payment/reseller-payment-list');
   }
   function get_account_details(Request $request)
   {
	   $id= $request->input('id');
	   $data= UserKyc::select('account_number','ifsc_code','account_holder_name')->where('user_id',$id)->first();
	   return view('admin.reseller_payment.account_details',compact('data'));
   }
   function reseller_payment_history($id)
   { 
       $data= User::findOrFail($id);
       $userkyc= UserKyc::where('user_id',$id)->first();
	   $payments = DB::table('reseller_payments')
				->join('orders', 'orders.id', '=', 'reseller_payments.order_id')
				->where('orders.user_id', '=',$id)
				->where('orders.payment_mode', '=','cod')
				->groupBy('reseller_payments.order_id')
				->orderBy('reseller_payments.order_id','desc')
				->select(DB::raw('sum(reseller_payments.amount+reseller_payments.extra_amount+reseller_payments.shipping_charge+reseller_payments.return_amount) AS sum'),'orders.created_at','orders.shipped_date','orders.order_id','orders.id')
				->get();
			   
				   $payment_array_total=array();
				   $total_sum=0;
				    foreach($payments as $vs)
				     {
						if($vs->shipped_date=="0000-00-00")
						   {
						   $payment_array['status']= "pending";
						   }
						   else
						   {
							  $payment_array['status']= (round((strtotime(date('Y-m-d h:i')) - strtotime($vs->shipped_date))/3600, 1)>=48)?'completed':'pending';
							  if((round((strtotime(date('Y-m-d h:i')) - strtotime($vs->shipped_date))/3600, 1)>=48))
							  {
								$total_sum=$total_sum + $vs->sum; 
							  }
						   }
                     }
                $payments_taken = DB::table('user_wallets')
				->where('user_id', '=',$id)
				->where('status', '=',1)
				->groupBy('user_id')
				->select(DB::raw('sum(amount) AS sum'))
				->first();
				$taken= (!is_null($payments_taken)?$payments_taken->sum:'');
				 //echo $taken; die;
				   $total_amount=$total_sum-$taken;
	   return view('admin.reseller_payment.payment_history',compact('id','data','userkyc','total_amount'));
   }
   
    public function getResellerPaymentHistory(Request $request)
    {
		$id= $request->input('id');
	   $requestData = $_REQUEST;
        $columns = array(
            // column index  => database column name
            0 => 'user_wallets.id',
            1 => 'user_wallets.user_id',
            2 => 'user_wallets.amount',
            3 => 'user_wallets.type',
            
        );
        $totalUsers = UserWallet::where('user_id',$id)->get()->count();
        $totalFiltered = $totalUsers;
        $users = UserWallet::with('user','user_kyc')->where('user_id',$id);
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
		 if(!empty($requestData['end_date']))
        {
            $start = date('Y-m-d',strtotime($requestData['start_date']));
            $end = date('Y-m-d',strtotime($requestData['end_date']));
            $users->whereBetween('created_at',  array($start, $end));
        }
        if (!empty($requestData['search']['value'])) {
           
		   //total filter data
		   $users->where('product_name','LIKE','%'.$searchString.'%')->orWhereHas('seller', function ($query) use ($searchString)
            {
                 $query->whereRaw("	username LIKE '%" . $searchString . "%'");
                 $query->orWhereRaw("mobile LIKE '%" . $searchString . "%'");
            })->orWhereHas('order', function ($query) use ($searchString)
            {
                 $query->whereRaw("order_id LIKE '%" . $searchString . "%'");
            });
			//total filter count
			$totalFiltered=OrderMeta::where('product_name','LIKE','%'.$searchString.'%')->orWhereHas('seller', function ($query) use ($searchString)
            {
                 $query->whereRaw("username LIKE '%" . $searchString . "%'");
				 $query->orWhereRaw("mobile LIKE '%" . $searchString . "%'");
            })->orWhereHas('order', function ($query) use ($searchString)
            {
                 $query->whereRaw("order_id LIKE '%" . $searchString . "%'");
            })
                ->get()->count();
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
            $nestedData[] = $item->amount;
            //$nestedData[] = $item->type;
			 if($item->status==0)
			 {
				$status= "<span style='color:coral'>Pending</span>"; 
			 }
			 elseif($item->status==1)
			 {
				 $status= "<span style='color:green'>Approved</span>"; 
			 }
			 elseif($item->status==2)
			 {
				 $status= "<span style='color:red'>Rejected</span>"; 
			 }
			 
            $nestedData[] =  $status;
            $date = strtotime($item->created_at);
            $nestedData[] = date('d-m-Y', $date);
			if($item->status==1){ 
			    $class="on"; $title="active";
			} else { 
			     $class="off"; $title="inactive";
     		}
			
			//<a href="' . URL::to('/') . '/admin/payment/update-reseller-payment-status/'.$item->id.'" title="'.$title.'"><i class="fa fa-toggle-'.$class.'" aria-hidden="true" ></i></a>
            $activateLink = "<select onchange='change_status(this.value)'>
		<option ".(($item->status==0)?'selected':'')." value='0,".$item->id."'>Pending</option>
			                 <option ".(($item->status==1)?'selected':'')." value='1,".$item->id."'>Approved</option>
			                 <option ".(($item->status==2)?'selected':'')." value='2,".$item->id."'>Rejected</option>
							 </select>";
			$viewLink = '<a href="javascript:void(0)" onClick="get_account_information('.$item->user_kyc->user_id.')" title="View Account Information"><i class="glyphicon glyphicon-eye-open"></i></a>';
			$history = '<a href="'.URL::to('reseller-payment-history/'.$item->user_kyc->user_id).'" title="View Payment History"><i class="fa fa-history" aria-hidden="true"></i></a>';
            $nestedData[] =$activateLink ." | ".$viewLink." | ".$history;
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
   
   function payment_details($seller_id=null)
   {
       $data=User::findOrFail($seller_id);
       $shipped_date= $_GET['shipped_date'];
	   $amount= $_GET['amount'];
	   $commission= $_GET['commission'];
       $returnAmount = Helper::getSellerReturnPenaltyAmount($seller_id,$shipped_date);
       $exchangePenalty = Helper::getSellerExchangePenaltyAmount($seller_id,$shipped_date);
	   $orders = Order::with('user','user_kyc','order_meta_data')->whereHas('order_meta_data', function ($query) use ($seller_id,$shipped_date)
            {
                $query->where('delivery_date',date('Y-m-d',strtotime($shipped_date)))->whereIn('order_metas.status', array('delivered','return','exchange'))->where('order_metas.seller_id',$seller_id);
				
            })->orderBy('id', 'desc')->get();
		
		
       $cancelled_charge= Payment::where('user_id',$seller_id)->where('type','withdraw')->whereDate('order_date', '=' ,$shipped_date)->sum('amount');
	   
	   $penalty_amount=Helper::get_seller_deduction(date('Y-m-d',strtotime($shipped_date)),date('Y-m-d',strtotime($shipped_date)),$seller_id);
		
		$sponsor_amount=Helper::get_sponsor_deduction(date('Y-m-d',strtotime($shipped_date)),date('Y-m-d',strtotime($shipped_date)),$seller_id);
		
		
		   $order_data= OrderMeta::with('order')->where('seller_id',$seller_id)->where('delivery_date',date('Y-m-d',strtotime($shipped_date)))->where('status','delivered')->groupBy('order_id');

			$order_list= $order_data->get();
			
			
			/*..........Return Orders.......*/
			$return_data= OrderRmaDetail::with('order')->whereDate('approved_date', '=' ,$shipped_date)->WhereHas('order', function ($query) use($seller_id)
            {
				$query->where('shipped_date',"!=","0000-00-00")->where('seller_id',$seller_id);	
            })->where('is_approved',1);
			$return_data= $return_data->get();
			
			
			/*........Exchange Orders.........*/
			$exchange_data= OrderExchange::with('order')->where('approved_date', '=', $shipped_date)->WhereHas('order', function ($query) use($seller_id)
            {
				$query->where('shipped_date',"!=","0000-00-00")->where('seller_id',$seller_id);	
            })->where('is_approved','1');
			$exchange_data= $exchange_data->get();
			
			
			
			/*........Cancelled Orders.........*/
       $cancelled_data= Order::with('order_meta_data')->where('status','cancelled')->where('seller_id',$seller_id)->whereDate('updated_at', '=', $shipped_date)->get();



       return view('admin.payment.payment_ajax',compact('returnAmount','exchangePenalty'))->with('amount',$amount)->with('shipped_date',$shipped_date)->with('data',$data)->with('orders',$orders)->with('user_id',$seller_id)->with('cancelled_charge',$cancelled_charge)->with('penalty_amount',$penalty_amount)->with('sponsor_amount',$sponsor_amount)->with('order_list',$order_list)->with('return_data',$return_data)->with('exchange_data',$exchange_data)->with('cancelled_data',$cancelled_data)->with('commission',$commission);
   }
   
   function order_details($id)
   {
	      $commission= $_GET['commission'];
	      $order_data = Order::with('address')->findOrFail($id);
	      $order_info = Order::with('address')->findOrFail($id);
		  $ordermeta_shipped = OrderMeta::with('seller')->where('order_id',$id)->where('status',"shipped")->get();
		  $track_data= OrderTracking::where('order_id',$id)->get();
		  $ordermeta_return = OrderMeta::with('seller')->where('order_id',$id)->where('status',"return")->get();
		  $ordermeta_exchange = OrderMeta::with('seller')->where('order_id',$id)->where('status',"exchange")->get();
		  $ordermeta_cancelled = OrderMeta::with('seller')->where('order_id',$id)->where('status',"cancelled")->get();
		  $return_video= OrderReturnVideos::with('order_meta','order_rma_details','order_exchanges')->where('order_id',$id)->get();
		  //dd($return_video);
          return view("admin.payment.order_details",compact('order_data','order_info','return_video','track_data','commission'))->with('ordermeta_data',$ordermeta_shipped);
   }
}

?>