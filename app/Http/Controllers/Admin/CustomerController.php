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
use App\UserAddress;
use App\UserNetwork;
use App\Customer;
use App\Order;
use App\Wallet;
use DB;
use URL;
use Excel;
use File;
use Helper;
use Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Response;

class CustomerController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }

    public function index()
    {   
        return view("admin.customer.index");
    }

    //get list of record of subadmin...........................................................
    public function getCustomerData(Request $request)
    {		
        $requestData = $_REQUEST;
        $columns = array(
            // column index  => database column name
            0 => 'users.id',
            1 => 'users.username',
            2 => 'users.email',
            3 => 'users.password',
            3 => 'users.mobile',
            4 => 'users.created_at',

        );
        $totalUsers = User::where('role_id',3)->Join('user_kyc', 'user_kyc.user_id', '=', 'users.id')->get()->count();
        $totalFiltered = $totalUsers;
        $users = User::select('users.*')->where('role_id',3)->orderBy('users.id', 'desc');
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value']))
        {
			
			 $users=$users->where(function($query) use ($searchString) {
			   return $query->where('mobile','LIKE','%'.$searchString.'%')
					 ->orWhere('email','LIKE','%'.$searchString.'%');
			})->orWhereHas('user_kyc', function ($query) use ($searchString)
            {
                $query->whereRaw("user_kyc.f_name  LIKE '%" . $searchString . "%'")->orWhereRaw("user_kyc.l_name  LIKE '%" . $searchString . "%'");
				
            });
			
			$totalFiltered=User::where(function($query) use ($searchString) {
                return $query->where('mobile','LIKE','%'.$searchString.'%')
					  ->orWhere('email','LIKE','%'.$searchString.'%');
            })->orWhereHas('user_kyc', function ($query) use ($searchString)
            {
                $query->whereRaw("user_kyc.f_name  LIKE '%" . $searchString . "%'")->orWhereRaw("user_kyc.l_name  LIKE '%" . $searchString . "%'");
				
            })->get()->count();
  
        }

        $orderColumn = $columns[$requestData['order'][0]['column']];
        $orderColumnDir = $requestData['order'][0]['dir'];
        $limit = $requestData['length'];
        $offset = $requestData['start'];
        $users = $users->Join('user_kyc', 'user_kyc.user_id', '=', 'users.id')->offset($offset)->limit($limit)->orderBy($orderColumn, $orderColumnDir)->get();
        $data = array();
        $i = $offset;
        foreach ($users as $item) {
            $i++;
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = $item->user_kyc->f_name."&nbsp;&nbsp;".$item->user_kyc->l_name;
            $nestedData[] = $item->email;
           // $nestedData[] = (($item->simple_pass=="")?"OTP-".$item->otp:$item->simple_pass);
            $nestedData[] = $item->mobile;
            $date = strtotime($item->created_at);
            $nestedData[] = date('d-m-Y h:i:s', $date); 
             if($item->banned==0){ $class="on"; $title="active"; } else { $class="off"; $title="inactive"; }
              $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
              $ViewLink = '<a href="' . URL::to('/') . '/admin/customer/view/'. $item->id .' " title="View"><i class="glyphicon glyphicon-eye-open"></i></a>';
			  $editLink = '<a href="' . URL::to('/') . '/admin/user/edit/'. $item->id .' " title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>';
              $activateLink = '<a href="' . URL::to('/') . '/admin/customer/update-status/'.$item->id.'" title="'.$title.'"><i class="fa fa-toggle-'.$class.'" aria-hidden="true" ></i></a>';
             // $NoteLink = '<a href="'.URl::to('admin/user-note/'.$item->id).'" title="Create Note"><i class="fa fa-sticky-note"></i></a>';
			             
			 $nestedData[] = $ViewLink." | ".$activateLink." | ".$deleteLink;
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
	
	public function active()
    {   
        return view("admin.customer.active");
    }

    //get list of record of subadmin...........................................................
    public function getActiveCustomerData(Request $request)
    {  
        $requestData = $_REQUEST;
        $columns = array(
            // column index  => database column name
            0 => 'users.id',
            1 => 'users.username',
            2 => 'users.email',
            3 => 'users.password',
            4 => 'users.mobile',
            5 => 'users.created_at',

        );
        $totalUsers = User::where('role_id',3)->where('banned',0)->get()->count();
        $totalFiltered = $totalUsers;
        $users = User::with('user_kyc','user_kyc.city')->where('role_id',3)->where('banned',0)->orderBy('users.id', 'desc');
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value']))
        {
			
			 $users=$users->where(function($query) use ($searchString) {
			   return $query->where('mobile','LIKE','%'.$searchString.'%')
					 ->orWhere('email','LIKE','%'.$searchString.'%')
					 ->orWhere('username','LIKE','%'.$searchString.'%')
					 ->orWhere('city_name','LIKE','%'.$searchString.'%');
			});
			
			$totalFiltered=User::where(function($query) use ($searchString) {
                return $query->where('mobile','LIKE','%'.$searchString.'%')
					  ->orWhere('email','LIKE','%'.$searchString.'%');
            })->orWhereHas('user_kyc', function ($query) use ($searchString)
            {
                $query->whereRaw("user_kyc.f_name  LIKE '%" . $searchString . "%'")->orWhereRaw("user_kyc.l_name  LIKE '%" . $searchString . "%'");
				
            })->get()->count();
  
        }

        $orderColumn = $columns[$requestData['order'][0]['column']];
        $orderColumnDir = $requestData['order'][0]['dir'];
        $limit = $requestData['length'];
        $offset = $requestData['start'];
        $users = $users->Join('user_kyc', 'user_kyc.user_id', '=', 'users.id')->offset($offset)->limit($limit)->orderBy($orderColumn, $orderColumnDir)->get();
        $data = array();
        $i = $offset;
        foreach ($users as $item) {
            $i++;
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = $item->username;
            $nestedData[] = $item->email;
            //$nestedData[] = (($item->simple_pass=="")?"OTP-".$item->otp:$item->simple_pass);
            $nestedData[] = $item->mobile;
            $nestedData[] = $item->city_name;
            $date = strtotime($item->created_at);
            $nestedData[] = date('d-m-Y h:i:s', $date); 
             if($item->banned==0){ $class="on"; $title="active"; } else { $class="off"; $title="inactive"; }
              $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->user_id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
              $ViewLink = '<a href="' . URL::to('/') . '/admin/customer/view/'. $item->user_id .' " title="View"><i class="glyphicon glyphicon-eye-open"></i></a>';
			  $editLink = '<a href="' . URL::to('/') . '/admin/user/edit/'. $item->user_id .' " title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>';
              $activateLink = '<a href="' . URL::to('/') . '/admin/customer/update-status/'.$item->user_id.'" title="'.$title.'"><i class="fa fa-toggle-'.$class.'" aria-hidden="true" ></i></a>';
              $NoteLink = '<a href="'.URl::to('admin/user-note/'.$item->user_id).'" title="Create Note"><i class="fa fa-sticky-note"></i></a>';
              $deductMoney = '<a href="javascript:void(0);" title="Deduct Money" onclick="deductMoneyModel('.$item->user_id.')"><i class="fa fa-money"></i></a>';
              $addMoney = '<a href="javascript:void(0);" title="Add Money" onclick="addMoneyModel('.$item->user_id.')"><i class="fa fa-plus"></i></a>';

			 $nestedData[] = $ViewLink." | ".$activateLink." | ".$deleteLink." | ".$NoteLink.' | '.$deductMoney." | ".$addMoney;
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
	
	
	public function inactive()
    {   
        return view("admin.customer.inactive");
    }

    //get list of record of subadmin...........................................................
    public function getInActiveCustomerData(Request $request)
    {  
        $requestData = $_REQUEST;
        $columns = array(
            // column index  => database column name
            0 => 'users.id',
            1 => 'users.username',
            2 => 'users.email',
            3 => 'users.password',
            3 => 'users.mobile',
            4 => 'users.created_at',

        );
        $totalUsers = User::where('role_id',3)->where('banned',1)->get()->count();
        $totalFiltered = $totalUsers;
        $users = User::select('users.*')->where('role_id',3)->where('banned',1)->orderBy('users.id', 'desc');
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value']))
        {
			
			 $users=$users->where(function($query) use ($searchString) {
			   return $query->where('mobile','LIKE','%'.$searchString.'%')
					 ->orWhere('email','LIKE','%'.$searchString.'%');
			})->orWhereHas('user_kyc', function ($query) use ($searchString)
            {
                $query->whereRaw("user_kyc.f_name  LIKE '%" . $searchString . "%'")->orWhereRaw("user_kyc.l_name  LIKE '%" . $searchString . "%'");
				
            });
			
			$totalFiltered=User::where(function($query) use ($searchString) {
                return $query->where('mobile','LIKE','%'.$searchString.'%')
					  ->orWhere('email','LIKE','%'.$searchString.'%');
            })->orWhereHas('user_kyc', function ($query) use ($searchString)
            {
                $query->whereRaw("user_kyc.f_name  LIKE '%" . $searchString . "%'")->orWhereRaw("user_kyc.l_name  LIKE '%" . $searchString . "%'");
				
            })->get()->count();
  
        }

        $orderColumn = $columns[$requestData['order'][0]['column']];
        $orderColumnDir = $requestData['order'][0]['dir'];
        $limit = $requestData['length'];
        $offset = $requestData['start'];
        $users = $users->Join('user_kyc', 'user_kyc.user_id', '=', 'users.id')->offset($offset)->limit($limit)->orderBy($orderColumn, $orderColumnDir)->get();
        $data = array();
        $i = $offset;
        foreach ($users as $item) {
            $i++;
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = $item->user_kyc->f_name."&nbsp;&nbsp;".$item->user_kyc->l_name;
            $nestedData[] = $item->email;
            $nestedData[] = (($item->simple_pass=="")?"OTP-".$item->otp:$item->simple_pass);
            $nestedData[] = $item->mobile;
            $date = strtotime($item->created_at);
            $nestedData[] = date('d-m-Y h:i:s', $date); 
             if($item->banned==0){ $class="on"; $title="active"; } else { $class="off"; $title="inactive"; }
              $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
              $ViewLink = '<a href="' . URL::to('/') . '/admin/customer/view/'. $item->id .' " title="View"><i class="glyphicon glyphicon-eye-open"></i></a>';
			  $editLink = '<a href="' . URL::to('/') . '/admin/user/edit/'. $item->id .' " title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>';
              $activateLink = '<a href="' . URL::to('/') . '/admin/customer/update-status/'.$item->id.'" title="'.$title.'"><i class="fa fa-toggle-'.$class.'" aria-hidden="true" ></i></a>';
              $NoteLink = '<a href="'.URl::to('admin/user-note/'.$item->id).'" title="Create Note"><i class="fa fa-sticky-note"></i></a>';
			             
			 $nestedData[] = $ViewLink." | ".$activateLink." | ".$deleteLink." | ".$NoteLink;
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
	
	public function otp_customer()
    {   
        return view("admin.customer.otp_unverified");
    }

    //get list of record of subadmin...........................................................
    public function getOtpCustomerData(Request $request)
    {  
        $requestData = $_REQUEST;
        $columns = array(
            // column index  => database column name
            0 => 'users.id',
            1 => 'users.username',
            2 => 'users.email',
            3 => 'users.password',
            4 => 'users.mobile',
            5 => 'users.created_at',

        );
        $totalUsers = User::where('role_id',3)->where('simple_pass',"")->get()->count();
        $totalFiltered = $totalUsers;
        $users = User::select('users.*')->where('role_id',3)->where('simple_pass',"")->orderBy('users.id', 'desc');
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value']))
        {
			
			 $users=$users->where(function($query) use ($searchString) {
			   return $query->where('mobile','LIKE','%'.$searchString.'%')
					 ->orWhere('email','LIKE','%'.$searchString.'%');
			})->orWhereHas('user_kyc', function ($query) use ($searchString)
            {
                $query->whereRaw("user_kyc.f_name  LIKE '%" . $searchString . "%'")->orWhereRaw("user_kyc.l_name  LIKE '%" . $searchString . "%'");
				
            });
			
			$totalFiltered=User::where(function($query) use ($searchString) {
                return $query->where('mobile','LIKE','%'.$searchString.'%')
					  ->orWhere('email','LIKE','%'.$searchString.'%');
            })->orWhereHas('user_kyc', function ($query) use ($searchString)
            {
                $query->whereRaw("user_kyc.f_name  LIKE '%" . $searchString . "%'")->orWhereRaw("user_kyc.l_name  LIKE '%" . $searchString . "%'");
				
            })->get()->count();
  
        }

        $orderColumn = $columns[$requestData['order'][0]['column']];
        $orderColumnDir = $requestData['order'][0]['dir'];
        $limit = $requestData['length'];
        $offset = $requestData['start'];
        $users = $users->Join('user_kyc', 'user_kyc.user_id', '=', 'users.id')->offset($offset)->limit($limit)->orderBy($orderColumn, $orderColumnDir)->get();
        $data = array();
        $i = $offset;
        foreach ($users as $item) {
            $i++;
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = $item->user_kyc->f_name."&nbsp;&nbsp;".$item->user_kyc->l_name;
            $nestedData[] = $item->email;
            $nestedData[] = (($item->simple_pass=="")?"OTP-".$item->otp:$item->simple_pass);
            $nestedData[] = $item->mobile;
            $date = strtotime($item->created_at);
            $nestedData[] = date('d-m-Y h:i:s', $date); 
             if($item->banned==0){ $class="on"; $title="active"; } else { $class="off"; $title="inactive"; }
              $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
              $ViewLink = '<a href="' . URL::to('/') . '/admin/customer/view/'. $item->id .' " title="View"><i class="glyphicon glyphicon-eye-open"></i></a>';
			  $editLink = '<a href="' . URL::to('/') . '/admin/user/edit/'. $item->id .' " title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>';
              $activateLink = '<a href="' . URL::to('/') . '/admin/customer/update-status/'.$item->id.'" title="'.$title.'"><i class="fa fa-toggle-'.$class.'" aria-hidden="true" ></i></a>';
              $NoteLink = '<a href="'.URl::to('admin/user-note/'.$item->id).'" title="Create Note"><i class="fa fa-sticky-note"></i></a>';
			             
			 $nestedData[] = $ViewLink." | ".$activateLink." | ".$deleteLink." | ".$NoteLink;
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
	

    function view($id=null)
    {
        $user = User::with('user_kyc')->where('id', $id)->first();
		//dd($user);
        $user_address = UserAddress::where('user_id', $id)->get();
        return view('admin.customer.view')->with('user', $user)->with('id', $id)->with('user_address',$user_address);
    }
	
	  //get list of record of subadmin...........................................................
    public function getOrderData(Request $request)
    {
		$id= $request->input('id');
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
        $totalUsers = Order::where('user_id',$id)->get()->count();
        $totalFiltered = $totalUsers;
        $users = Order::with('user')->where('user_id',$id)->with('address')->orderBy('id', 'desc');
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value'])) {
           
		    $users->where('order_id', 'LIKE', '%' . $searchString . '%');
            $totalFiltered = Order::where('order_id', 'LIKE', '%' . $searchString . '%')
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
            $nestedData[] = $item->order_id;
            $nestedData[] = $item->user->username;
            $nestedData[] = $item->user->mobile;
            $nestedData[] = $item->total_amount;
            $trakingStatus = Helper::getOrderTrackingStatus($item->id);
            $nestedData[] = str_replace('_',' ',$trakingStatus ? $trakingStatus->type :'');
            
            $nestedData[] = Helper::get_qty_sum($item->id);
            $nestedData[] = $item->payment_mode;
            $date = strtotime($item->created_at);
            $nestedData[] = date('d-m-Y', $date);
          
            $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
            //$editLink = '<a href="' . URL::to('/') . '/admin/notice/edit/' . $item->id . ' " title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>';
            $viewLink = '<a href="' . URL::to('/') . '/admin/order/view-order/' . $item->id . ' " title="View Order"><i class="glyphicon glyphicon-eye-open"></i></a>';
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
    //to get list of wallet...........................................................
    public function getCustomerWalletData(Request $request)
    {
        $id= $request->input('id');
        $requestData = $_REQUEST;
        $columns = array(
            // column index  => database column name
            0 => 'wallets.id',
            1 => 'wallets.user_id',
            2 => 'wallets.amount',
            3 => 'wallets.payment_type',
            4 => 'wallets.type',
            5 => 'wallets.created_at',
        );
        $totalUsers =Wallet::with('user')->where('user_id',$id)->whereNotIn('payment_type', ['refer_and_earn'])->count();
        $totalFiltered = $totalUsers;
        $users = Wallet::with('user')->where('user_id',$id)->whereNotIn('payment_type', ['refer_and_earn'])->orderBy('id', 'desc');
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value'])) {

            $users->where('user_id', 'LIKE', '%' . $searchString . '%');
            $totalFiltered = Order::where('order_id', 'LIKE', '%' . $searchString . '%')
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
            $perticular='';
            if($item->payment_type =='placed_order'){
                $perticular = 'Purchase item';
            }
            if($item->payment_type =='return_order'){
                $perticular = 'Return order';
            }
            if($item->payment_type =='add_balance'){
                $perticular = 'Add wallet amount';
            }
            if($item->payment_type =='cashback'){
                $perticular = 'Cashback';
            }
            if($item->payment_type =='first_order_cashback'){
                $perticular = 'First order cashback';
            }
            if($item->payment_type =='withdraw_by_admin'){
                $perticular = 'Withdraw by admin';
            }
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = $item->user->username;
           // $nestedData[] = $item->created_at;
            $nestedData[] = $perticular;
            $nestedData[] = $item->amount;
            $nestedData[] = $item->reason;
            $nestedData[] = $item->type =='withdraw' ? 'DR' :'CR';
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
    //to get list of wallet...........................................................
    public function getCustomerReferralWalletData(Request $request)
    {
        $id= $request->input('id');
        $requestData = $_REQUEST;
        $columns = array(
            // column index  => database column name
            0 => 'wallets.id',
            1 => 'wallets.user_id',
            2 => 'wallets.amount',
            3 => 'wallets.payment_type',

        );
        $totalUsers =Wallet::with('user')->where('user_id',$id)->where('payment_type','refer_and_earn')->count();
        $totalFiltered = $totalUsers;
        $users = Wallet::with('user')->where('user_id',$id)->where('payment_type','refer_and_earn')->orderBy('id', 'desc');
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value'])) {

            $users->where('user_id', 'LIKE', '%' . $searchString . '%');
            $totalFiltered = Order::where('order_id', 'LIKE', '%' . $searchString . '%')
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
            $reffUser = Helper::getReffCode($item->ref_id);
            $reffUserData = Helper::getUserById($item->ref_id);
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = $reffUserData->username;
            $nestedData[] = $reffUser;
            $nestedData[] = $item->amount;
            //$nestedData[] = $item->created_at;

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
    public function getUserWalletAmount(Request $request){
        try{
            $userId = $request->userid;
            if($userId){
                $wallet_amount = Helper::get_wallet($userId);
                return Response::json(array(
                    'status' => true,
                    'wallet_amount' => $wallet_amount,
                    'message'=>"Success",
                ), 200);
            }
        }catch (\Exception $e){
            return $e->getMessage();
        }
    }
    public function deductUserWalletAmount(Request $request){
        try{
            $userId = $request->userid;
            $deduct_amount = $request->deduct_amount;
            $deduct_reason = $request->deduct_reason;
            $wallet_amount = Helper::get_wallet($userId);
            if($deduct_amount > $wallet_amount){
                return Response::json(array(
                    'status' => 0,
                    'message'=>"Deduct amount not more than total amount",
                ), 200);
            }else if($deduct_amount > 0){
                DB::table('wallets')->insert(['user_id'=>$userId,'type'=>'withdraw','amount'=>$deduct_amount,'status'=>'withdraw by admin','payment_type'=>'withdraw_by_admin','reason'=>$deduct_reason]);
                return Response::json(array(
                    'status' => 1,
                    'message'=>"Amount deduct successfully!",
                ), 200);
            }
        }catch (\Exception $e){
            return $e->getMessage();
        }
    }
    public function addUserWalletAmount(Request $request){
        try{
            $userId = $request->userid;
            $add_amount = $request->add_amount;
            $add_reason = $request->add_reason;
            if($add_amount > 0){
                DB::table('wallets')->insert(['user_id'=>$userId,'type'=>'deposit','amount'=>$add_amount,'reason'=>$add_reason,'payment_type'=>'add_balance']);
                return Response::json(array(
                    'status' => 1,
                    'message'=>"Amount added successfully!",
                ), 200);

            }else {
                return Response::json(array(
                    'status' => 0,
                    'message'=>"invalid amount!",
                ), 200);
            }
        }catch (\Exception $e){
            return $e->getMessage();
        }
    }
 
    ///delete user...................
    public function delete()
    {
        $user = User::findOrFail($_POST['id']);

        if(!empty($user->delete()))
        {

            Session::flash('success_message', 'User has been deleted successfully!');
        }
        else {
            Session::flash('error_message', 'Unable to delete the subadmin');
        }
    }
	
	function update_status($id=null)
    {
        $response=DB::statement("UPDATE users SET banned =(CASE WHEN (banned = 1) THEN '0' ELSE '1' END) where id = $id");
        if($response) {
            Session::flash('success_message', 'status has been updated successfully!');
        }
        else {
            Session::flash('error_message', 'Unable to update status');
        }
        return redirect('/admin/customer/customer-list');
    }

    ///change subadmin status...................
    
function getExcelData()
    {
        DB::setFetchMode(\PDO::FETCH_ASSOC);
        $result = DB::select(DB::RAW('SELECT user_profiles.f_name,user_profiles.l_name,users.email,users.mobile,user_profiles.my_sponsor_id,user_profiles.sponsor as Refer_By,user_kyc.transaction_id,user_kyc.plan_amount,user_kyc.method as Payment_type,user_kyc.is_verified FROM users INNER JOIN user_profiles ON users.id = user_profiles.user_id INNER JOIN user_kyc ON user_kyc.user_id = user_profiles.user_id'));
        $filename = "Users.xls"; // File Name// Download file
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
	
	
	
	public function edit($id)
    { 
        //$Package = Package::first();
        $user = User::find($id);
        return view('admin.user.edit')->with(['user'=>$user]);
    }
	
	public function notification()
    { 
	    $data= User::groupBy('city_name')->where('role_id',3)->get();
        return view('admin.customer.notification',compact('data'));
    }
	
	public function storeNotification(Request $request)
    { 
       //print_r($request->all()); die;
	     // validate
        $validator = Validator::make($request->all(),
            [
                'city_name' => 'required',
				'title' => 'required',
				'description' => 'required',

            ], [
                'city_name.required' => 'This field is required.',
				'title.required' => 'This field is required.',
				'description.required' => 'This field is required.',
            ]);
        if ($validator->fails())
        {
            return redirect('admin/customer/notification')->withInput()->withErrors($validator);
        }
        else
        {
			$token=array();
            $photo_name=0;
            if ($request->hasFile('image'))
            {
                $path_original=public_path() . '/admin/uploads/push_notification';
                $file = $request->image;
                $photo_name = time() . '-' . $file->getClientOriginalName();
                $file->move($path_original, $photo_name);

            }
			DB::table('users')->select('device_token')->where('role_id',3)->chunk(100, function($users) use($token,$request,$photo_name)
					{
						foreach ($users as $user)
						{
							$token[]= $user->device_token;
						}
						$data['title']= $request->input('title');
						$data['description']= $request->input('description');
                        if($photo_name){
                            $url  = URL::to('public/admin/uploads/push_notification/'.$photo_name);
                        }else{
                            $url = 0;
                        }
						$data['image']= $url;
						Helper::send_push_notification($token,$data);
					});
		     Session::flash('success_message', 'Notification send successfully!');
            return redirect('admin/customer/notification');
			
		}
		
	   
    }

    /**
    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        // validate
        $user = User::find($id);
        $validator = Validator::make($request->all(),
            [
                'username' => 'required',
				'email' => 'required',
				'mobile' => 'required',

            ], [
                'username.required' => 'This field is required.',
				'email.required' => 'This field is required.',
				'mobile.required' => 'This field is required.',
            ]);

        if ($validator->fails())
        {
            return redirect('admin/user/edit/'.$id)->withInput()->withErrors($validator);
        }
        else
        {
            $obj=new User();
            $obj->title=$request->input('title');
            $setting = User::first();

            if($user) {
                $data =$request->all();
                if ($request->hasFile('images'))
                {
                    $path_original=public_path() . '/admin/uploads/user_image';
                    $file = $request->images;

                    $photo_name = time() . '-' . $file->getClientOriginalName();
                    $file->move($path_original, $photo_name);
                    $data['old_images'] = $photo_name;
                    if ($request->old_img != '') {
                        try {
                            unlink($path_original . $request->old_img);

                        } catch (\Exception $e) {
                        }
                    }
                }
                $update_data = User::find($user->id)->fill($data);
                if($request->images){$update_data->images=$photo_name;}
                $update_data->update();
            }
            else
            {
                if ($file = $request->hasFile('images')) {
                    $file = $request->file('images');
                    $fileName = $file->getClientOriginalName();
                    $destinationPath = public_path() . '/admin/uploads/slider_image';
                    $file->move($destinationPath, $fileName);
                    $obj->images = $fileName;
                }
                $obj->save();
            }


            // redirect
            Session::flash('success_message', 'User Successfully updated');
            return redirect('admin/user/user-list');
        }

    }


}

?>