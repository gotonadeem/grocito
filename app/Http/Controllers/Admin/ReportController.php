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
use App\OrderMeta;
use App\UserKyc;
use DB;
use URL;
use Excel;
use File;
use Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ReportController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }

    public function index()
    {
        return view("admin.report.index");
    }
    public function getDeliveryData(Request $request)
    {
        $requestData = $_REQUEST;
        $columns = array(
            // column index  => database column name
            0 => 'order_metas.id',
            1 => 'order_metas.order_id',
            2 => 'order_metas.status',
            3 => 'order_metas.qty',
            
        );
        $totalUsers = OrderMeta::get()->groupBy('order_id')->count();
        $totalFiltered = $totalUsers;
        $users = OrderMeta::with('seller','order')->groupBy('order_id')->orderBy('id', 'desc');
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
            $nestedData[] = (!is_null($item->order)?$item->order->order_id:"");
            $nestedData[] = (!is_null($item->seller)?$item->seller->username:"");
            $nestedData[] = (!is_null($item->seller)?$item->seller->mobile:"");
            $nestedData[] = (!is_null($item->order)?$item->order->total_amount+$item->order->shipping_charge+$item->order->extra_amount:"");
            $nestedData[] = str_replace("_"," ",$item->status);
            $nestedData[] = $item->qty;
            $nestedData[] = (!is_null($item->order)?$item->order->payment_mode:"");
            $nestedData[] = (!is_null($item->order)?date('d-m-Y',strtotime($item->order->created_at)):"");
            $nestedData[] = (!is_null($item->order)?$item->delivery_date:"");
           // $date = strtotime($item->created_at);
            //$nestedData[] = date('d-m-Y', $date);
          
            $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->order->id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
            //$editLink = '<a href="' . URL::to('/') . '/admin/notice/edit/' . $item->id . ' " title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>';
            $viewLink = '<a href="' . URL::to('/') . '/admin/order/view-order/' . $item->order->id . ' " title="View Order"><i class="glyphicon glyphicon-eye-open"></i></a>';
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
//Withdraw Amount.......................................
    public function withdraw()
    {
        return view("admin.deposit.withdraw");
    }
    public function getWithdrawData(Request $request)
    {
        $requestData = $request->toArray();
        $columns = array(
            // column index  => database column name
            0 => 'withdrawal_wallets.id',
            1 => 'withdrawal_wallets.user_id',
            2 => 'withdrawal_wallets.bank_name',
            3 => 'withdrawal_wallets.account_holder_name',
            4 => 'withdrawal_wallets.account_number',
            5 => 'withdrawal_wallets.ifsc_code',
            6 => 'withdrawal_wallets.type',
            7 => 'withdrawal_wallets.address',
            8 => 'withdrawal_wallets.mobile',
            9 => 'withdrawal_wallets.amount',
        );
        $totalItems = Withdraw::get()->count();
        $totalFiltered = $totalItems;
        $items = Withdraw::select('withdrawal_wallets.*')->orderBy('withdrawal_wallets.id', 'desc');
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value']))
        {
            $items->whereRaw("amount LIKE '%" . $searchString . "%'");
        }
        $orderColumn = $columns[$requestData['order'][0]['column']];
        $orderColumnDir = $requestData['order'][0]['dir'];
        $limit = $requestData['length'];
        $offset = $requestData['start'];
        $items=$items->offset($offset)->limit($limit)->orderBy($orderColumn, $orderColumnDir)->get();
        $data = array();
        $i = $offset;
        foreach ($items as $item) {
            $i++;
            $nestedData = array();
            if($item->status=='1'){
                $status='Success';
                $class='<span style="color:green;">';
            }else{
                $status='Panding';
                $class='<span style="color:red;">';
            }
            $nestedData[] = $i;
            $nestedData[] = $item->user_id;
            $nestedData[] = $item->user_id;
            $nestedData[] = $item->amount;
            $nestedData[] = $class.$status.'</span>';
            $nestedData[] = $item->created_at->format('F d, Y');
            if($item->status==1){ $class="on"; $title="active"; $status='0';} else { $class="off"; $title="inactive"; $status='0';}
            $activateLink = '<a href="' . URL::to('/') . '/admin/withdraw/withdraw-update-status/'.$status.'/'.$item->id.'/'.$item->user_id.'" title="'.$title.'"><i class="fa fa-toggle-'.$class.'" aria-hidden="true" ></i></a>';
            $viewLink = '<a href="' . URL::to('/') . '/admin/withdraw/withdraw-view/'.$item->id.'" title="View Details"><i class="fa fa-eye" aria-hidden="true" ></i></a>';
            $nestedData[] = $activateLink .' | '. $viewLink;
            $data[] = $nestedData;
        }
        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalItems),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);
    }
    function withdraw_view($id=null)
    {
        $user = Withdraw::where('id', $id)->first();
        //$user = Withdraw::with('user')->where('user_id', $id)->first();
        return view('admin.deposit.withdraw_view')->with('user', $user);
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
            $msg="Hi, <br><br>   Welcome to EuroCoin<br><br>";
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


}

?>