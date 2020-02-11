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
use App\Pincode;
use App\Product;
use App\Category;
use App\Agreement;
use App\SellerCommission;
use App\State;
use App\City;
use App\Admin;
use App\OrderMeta;
use App\ProductRating;
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

class ReviewAndRatingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }
    public function index()
    {
        return view("admin.review_rating.index");
    }
    public function getReviewRatingData(Request $request)
    {

        $requestData = $_REQUEST;
        $columns = array(
            // column index  => database column name
            0 => 'product_ratings.id',
            1 => 'product_ratings.product_id',
            2 => 'product_ratings.rating',
            3 => 'product_ratings.message',
            4 => 'product_ratings.created_at',
        );
        $totalUsers = ProductRating::with('user','product')->get()->count();
        $totalFiltered = $totalUsers;
        $users = ProductRating::with('user','product')->orderBy('id', 'desc');
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value']))
        {
            $users=$users->where(function($query) use ($searchString) {
                return $query->where('rating','LIKE','%'.$searchString.'%')
                    ->orWhere('message','LIKE','%'.$searchString.'%');

            });
            $totalFiltered=ProductRating::with('user','product')->where(function($query) use ($searchString) {
                return $query->where('rating','LIKE','%'.$searchString.'%')
                    ->orWhere('message','LIKE','%'.$searchString.'%');

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
            $nestedData[] = $item->user['username'];
            $nestedData[] = $item->product['name'];
            $nestedData[] = $item->rating;
            $nestedData[] = $item->message;
            $date = strtotime($item->created_at);
            $nestedData[] = date('d-m-Y', $date);
            if($item->verify_status == 1){ $classs="on text-green"; $titles="active"; } else { $classs="off text-danger"; $titles="inactive"; }
            $verifyStatus = '<a onclick="return confirm(\'Are you sure to change status?\')" href="' . URL::to('/') . '/admin/product/change-status-rating-review/'.$item->id.'" title="'.$titles.'"><i class="fa fa-toggle-'.$classs.'" aria-hidden="true" ></i></a>';
            $nestedData[] = $verifyStatus;
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
    function changeStatusRatingReview(Request $request)
    {
        $id= $request->id;
        $response=DB::statement("UPDATE product_ratings SET verify_status =(CASE WHEN (verify_status = 1) THEN '0' ELSE '1' END) where id = $id");
        if($response) {
            Session::flash('success_message', 'Rating review status update successfully!');
        }
        else {
            Session::flash('error_message', 'Unable to update status');
        }
        return redirect('/admin/product/review-rating');
    }
//End------------------------------------
}