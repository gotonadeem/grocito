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
use App\State;
use App\City;
use App\OrderMeta;
use DB;
use URL;
use Excel;
use File;
use Mail; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class SellerOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }
	
    public function index()
    {
        return view("admin.seller.index");
    }
	
    public function seller_order($id)
	{
	   $data=OrderMeta::where('seller_id',$id)->first();
	   return  view("admin.seller_order.index",compact('id'))->with('data',$data);
	}
	
	public function getSellerOrderData(Request $request)
	{
		$requestData = $_REQUEST;
        $columns = array(
            0 => 'order_metas.id',
            1 => 'order_metas.order_id',
            2 => 'order_metas.status',
            3 => 'order_metas.qty',
        );
        $totalUsers = OrderMeta::with('exchange_order','return_order')->where('seller_id',$request->input('id'))->get()->count();
        $totalFiltered = $totalUsers;
        $users = OrderMeta::with('seller','order','seller_kyc','exchange_order','return_order')->selectRaw('*, count(product_id) as total_product,sum(qty) as total_qty')->where('seller_id',$request->input('id'))->groupBy('order_id');
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
        //dd($users);
		$data = array();
        $i = $offset;
		 //dd($users);
        foreach ($users as $item) {
            $i++;
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = !is_null($item->order)?$item->order->order_id:'NA';
            $nestedData[] = $item->seller_kyc->f_name." ".$item->seller_kyc->l_name;
            $nestedData[] = $item->seller->username;
            $nestedData[] = $item->seller->mobile;
            $nestedData[] = (!is_null($item->order)?$item->order->payment_amount:'');
			                if($item->status=="exchange")
							{
								if($item->exchange_order->status=="unapproved")
								{
									$status="Delivered";
								}
								elseif($item->exchange_order->status=="completed")
								{
									$status="exchange";
								}
							}
                            elseif($item->status=="return")
							{
								if($item->return_order->is_approved==2)
								{
									$status="Delivered";
								}
								elseif($item->return_order->is_approved==1)
								{
									$status="Return";
								}
							}
							else
							{
								$status=$item->status;
							}	
							
            $nestedData[] = $status;
            
			$nestedData[] = $item->total_qty;
            $nestedData[] = $item->total_product;
            $nestedData[] = !is_null($item->order)?$item->order->payment_mode:'';
            $nestedData[] = !is_null($item->order)?$item->order->dock_no:'NA';
            $date = (!is_null($item->order)?$item->order->shipped_date:'');
            $nestedData[] = ($date=="0000-00-00")?'Not Delivered':$date;
          
            $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
            //$editLink = '<a href="' . URL::to('/') . '/admin/notice/edit/' . $item->id . ' " title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>';
            $viewLink = '<a href="' . URL::to('/') . '/admin/order/view-order/' . (!is_null($item->order)?$item->order->id:'NA') . ' " title="View Order"><i class="glyphicon glyphicon-eye-open"></i></a>';
            $nestedData[] =  $viewLink." | ". $deleteLink;


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
}