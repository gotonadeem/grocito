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
use App\State;
use App\City;
use App\Admin;
use App\OrderMeta;
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

class SellerCommissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }
    public function index()
    {
        return view("admin.seller_commission.index");
    }
    //get list of record of subadmin...........................................................
    public function getSellerData(Request $request)
    {  
     
        $requestData = $_REQUEST;
        $columns = array(
            // column index  => database column name
            0 => 'users.id',
            1 => 'users.username',
            2 => 'users.email',
            3 => 'users.mobile',
            4 => 'users.created_at',
        );
        $totalUsers = User::where('role_id',2)->where('verify_status','requested')->get()->count();
        $totalFiltered = $totalUsers;
        $users = User::select('*')->where('role_id',2)->where('verify_status','requested')->orderBy('id', 'desc');
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value']))
        {
           $users=$users->where(function($query) use ($searchString) {
			   return $query->where('username','LIKE','%'.$searchString.'%')
                    ->orWhere('mobile','LIKE','%'.$searchString.'%')
					->orWhere('email','LIKE','%'.$searchString.'%');
			});
			$totalFiltered=User::where('verify_status','requested')->where(function($query) use ($searchString) {
                return $query->where('username','LIKE','%'.$searchString.'%')
                    ->orWhere('mobile','LIKE','%'.$searchString.'%')
					->orWhere('email','LIKE','%'.$searchString.'%');
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
            $nestedData[] = $item->username;
            $nestedData[] = $item->email;
            $nestedData[] = $item->simple_pass;
            $nestedData[] = $item->mobile;
            $date = strtotime($item->created_at);
            $nestedData[] = date('d-m-Y', $date);
			
             if($item->banned==0){ $class="on"; $title="active"; } else { $class="off"; $title="inactive"; }
              $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
              $ViewLink = '<a href="' . URL::to('/') . '/admin/seller/view/'. $item->id .' " title="View"><i class="glyphicon glyphicon-eye-open"></i></a>';
			  $editLink = '<a href="' . URL::to('/') . '/admin/seller/edit-seller/'. $item->id .' " title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>';
              //$activateLink = '<a href="' . URL::to('/') . '/admin/seller/update-status/'.$item->id.'" title="'.$title.'"><i class="fa fa-toggle-'.$class.'" aria-hidden="true" ></i></a>';
              $nestedData[] = $ViewLink." | ". $editLink." | ".$deleteLink;
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