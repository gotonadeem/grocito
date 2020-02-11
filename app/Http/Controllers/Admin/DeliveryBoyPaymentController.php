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
use App\Country;
use App\Product;
use App\Category;
use App\Agreement;
use App\SellerCommission;
use App\DeliveryBoyPayment;
use App\PaymentSlot;
use App\Warehouse;
use App\State;
use App\City;
use App\Admin;
use App\OrderMeta;
use App\DeliveryBoySetting;
use App\DeliveryBoyRide;
use Redirect;
use DB;
use Helper;
use Response;
use URL;
use Excel;
use File;
use Mail; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
class DeliveryBoyPaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }
    public function index()
    {
        return view("admin.delivery_boy.index");
    }
	function getData(Request $request)
	{
		 $requestData = $_REQUEST;
         $columns = array(
            // column index  => database column name
            0 => 'delivery_boy_rides.id',
            1 => 'delivery_boy_rides.seller_id',
            2 => 'delivery_boy_rides.order_id',
            3 => 'delivery_boy_rides.user_id',
            4 => 'delivery_boy_rides.created_at',
        );
        $totalUsers = DeliveryBoyRide::with('user')->groupBy('delivery_boy_id')->get()->count();
        $totalFiltered = $totalUsers;
        $users = DeliveryBoyRide::with('user','user_kyc','user_kyc.city')->groupBy('delivery_boy_id')->orderBy('id', 'desc');
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value']))
        {
           $users=$users->where(function($query) use ($searchString) {
			   return $query->where('username','LIKE','%'.$searchString.'%')
                    ->orWhere('mobile','LIKE','%'.$searchString.'%')
					->orWhere('email','LIKE','%'.$searchString.'%')
                   ->orWhereHas('user_kyc.city', function ($query) use ($searchString)
                   {
                       $query->whereRaw("name  LIKE '%" . $searchString . "%'");
                   });
			});
			$totalFiltered=User::where('verify_status','requested')->where(function($query) use ($searchString) {
                return $query->where('username','LIKE','%'.$searchString.'%')
                    ->orWhere('mobile','LIKE','%'.$searchString.'%')
					->orWhere('email','LIKE','%'.$searchString.'%')
                    ->orWhereHas('user_kyc.city', function ($query) use ($searchString)
                    {
                        $query->whereRaw("name  LIKE '%" . $searchString . "%'");
                    });
            })->get()->count();
        }

        $orderColumn = $columns[$requestData['order'][0]['column']];
        $orderColumnDir = $requestData['order'][0]['dir'];
        $limit = $requestData['length'];
        $offset = $requestData['start'];
		$users=$users->offset($offset)->limit($limit)->orderBy($orderColumn, $orderColumnDir)->get();
        $data = array();
        $i = $offset;
        foreach ($users as $item) {
            $i++;
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = $item->user->username;
            $nestedData[] = $item->user_kyc->city ? $item->user_kyc->city->name :'';
            $nestedData[] = $item->user->email;
            $nestedData[] = $item->user->simple_pass;
            $nestedData[] = $item->user->mobile;
            $date = strtotime($item->created_at);
            $nestedData[] = date('d-m-Y', $date);
			
             if($item->banned==0){ $class="on"; $title="active"; } else { $class="off"; $title="inactive"; }
              $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->delivery_boy_id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
              $ViewLink = '<a href="' . URL::to('/') . '/admin/delivery-boy/view/'. $item->delivery_boy_id .' " title="View"><i class="glyphicon glyphicon-eye-open"></i></a>';
              $paymentLink = '<a href="' . URL::to('/') . '/admin/delivery-boy-payment/view/'. $item->delivery_boy_id .' " title="payment View"><i class="fa fa-credit-card"></i></a>';
			  $editLink = '<a href="' . URL::to('/') . '/admin/delivery-boy/edit-delivery-boy/'. $item->delivery_boy_id .' " title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>';
              $activateLink = '<a href="' . URL::to('/') . '/admin/delivery-boy/update-status/'.$item->delivery_boy_id.'" title="'.$title.'"><i class="fa fa-toggle-'.$class.'" aria-hidden="true" ></i></a>';
              $NoteLink = '<a href="'.URl::to('admin/user-note/'.$item->delivery_boy_id).'" title="Create Note"><i class="fa fa-sticky-note"></i></a>';

			 $nestedData[] = $paymentLink;
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
	
	public function payment_view($id=null)
    {
		$data=PaymentSlot::get();
		$paid=DeliveryBoyPayment::with('payment_slot')->where('delivery_boy_id',$id)->get();
		$userdata=User::findOrFail($id);
        return view("admin.delivery_boy.payment_view",compact('data','userdata','paid'));
    }
	
	public function payment_details(Request $request)
    {
		$from_date= $request->input('from_date');
		$to_date= $request->input('to_date');
		$user_id= $request->input('user_id');
		$data=DeliveryBoyRide::with('order','seller','warehouse')->where('delivery_boy_id',$user_id)->whereBetween('date', [$from_date, $to_date])->get();
		return view("admin.delivery_boy.payment_details",compact('data'));
    }
	
	public function make_rider_payment(Request $request)
    {
			   $data['transaction_id']= $request->input('transaction_id');
			   $data['description']= $request->input('description');
			   $data['delivery_boy_id']= $request->input('rider_id');
			   $data['payment_amount']= $request->input('payable');
			   $data['amount']= $request->input('total');
			   $data['cod']= $request->input('cod_total');
			   $data['total']= $request->input('total');
			   $data['distance_wise_amount']= $request->input('amount_per_km');
			   $data['distance']= $request->input('total_distance');
			   $data['order_count']= $request->input('total_count');
			   $data['bonus']= $request->input('bonus');
			   $data['payment_slot_id']=$request->input('id');
			   $obj= new DeliveryBoyPayment($data);
			   $obj->save();
			   //$this->Payment_model->insert_report($data);
			   //$this->session->set_flashdata('succes_message', 'Transaction has been completed successfully');
			   echo json_encode(array('status'=>true,'message'=>"Transaction has been completed successfully"));
			   
		//$from_date= $request->input('from_date');
		//$to_date= $request->input('to_date');
		//$user_id= $request->input('user_id');
		//$data=DeliveryBoyRide::with('order','seller','warehouse')->where('delivery_boy_id',$user_id)->whereBetween('date', [$from_date, $to_date])->get();
		//return view("admin.delivery_boy.payment_details",compact('data'));
    }
}