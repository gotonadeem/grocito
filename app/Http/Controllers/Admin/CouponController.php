<?php
namespace App\Http\Controllers\Admin; 
use App\Http\Requests;
use App\Http\Controllers\Controller;   
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\GeneralSetting;
use App\PaymentGatwaySetting;
use App\Package;
use App\Coupon;
use DB;
use URL;
use File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
class CouponController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }

    public function index()
    {
      return view('admin.coupons.index');
    }
    
    public function getCouponCode(Request $request)
    {
        $requestData = $request->toArray();
        $columns = array(
            // column index  => database column name
            0 => 'coupons.id',
            1 => 'coupons.code',
            2 => 'coupons.start_date',
            3 => 'coupons.end_date',
            4 => 'coupons.discount_amount',
            5 => 'coupons.discount_unit',
            6 => 'coupons.no_of_usage',
            7 => 'coupons.usage_per_user',
            8 => 'coupons.status',
            9 => 'coupons.min_ord_amount',
        );

        $totalItems = Coupon::get()->count();
        $totalFiltered = $totalItems;
        $items = Coupon::where('id','!=',0);

        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value']))
        {
            $items->whereRaw("package_name LIKE '%" . $searchString . "%'");
            //$totalFiltered = User::whereRaw("name LIKE '%" . $searchString . "%'")->get()->count();
        }

        $orderColumn = $columns[$requestData['order'][0]['column']];
        $orderColumnDir = $requestData['order'][0]['dir'];
        $limit = $requestData['length'];
        $offset = $requestData['start'];

        $items=$items->offset($offset)->limit($limit)->orderBy($orderColumn, $orderColumnDir)->get();
        //dd($users->toSql(), $users->getBindings());
        //print_r($items);
        $data = array();
        $i = $offset;
        foreach ($items as $item) {
            $i++;
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = $item->code;
            $nestedData[] = $item->start_date;
            $nestedData[] = $item->end_date;
            $nestedData[] = $item->discount_amount;
            $nestedData[] = $item->discount_unit;
            $nestedData[] = $item->no_of_usage;
            $nestedData[] = $item->usage_per_user;
            $nestedData[] = $item->status;
            $nestedData[] = $item->min_ord_amount;
            $nestedData[] = date('d-m-Y',strtotime($item->created_at));
            $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
            $editLink = '<a href="' . URL::to('/') . '/admin/coupon/edit/'. $item->id .' " title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>';
           
            $nestedData[] = $editLink." | ".$deleteLink;
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

    public function create()
    {

      return view('admin.coupons.create');
    }

   
    public function store(Request $request)
    {

        $packageData = array(
            'code'          =>$request->input('code'),
            'start_date'    =>date('Y-m-d',strtotime($request->input('start_date'))),
            'end_date'      =>date('Y-m-d',strtotime($request->input('end_date'))),
            'discount_amount'=>$request->input('discount_amount'),
            'discount_unit'  =>$request->input('discount_unit'),
            'no_of_usage'    =>$request->input('no_of_usage'),
            'usage_per_user' =>$request->input('usage_per_user'),
            'status'         =>$request->input('status'),
            'min_ord_amount' =>$request->input('min_ord_amount'),
        );

        $rules = array(
            'code'          =>'required',
            'start_date'    =>'required',
            'end_date'      =>'required',
            'no_of_usage'   =>'required',
            'discount_amount'=>'required',
            'no_of_usage'    =>'required',
            'usage_per_user' =>'required',
        );
        $validator = Validator::make($packageData,$rules);
        if ($validator->fails()) {
            return redirect('admin/coupon/create')->withInput()->withErrors($validator);
        }else{
            $coupon = new Coupon($packageData);
            $coupon->save();
        }
        // redirect
        Session::flash('success_message', 'Your Coupon code has been added successfully');
        return redirect('/admin/coupon/coupon-list');
    }
    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('admin.coupons.edit',compact('coupon'));
    }
    public function update(Request $request, $id)
    {
         $data =$request->all();
         $category = Coupon::findOrFail($id);
         $rules = array(
            'code'          =>'required',
            'start_date'    =>'required',
            'end_date'      =>'required',
            'no_of_usage'   =>'required',
            'discount_amount'=>'required',
            'no_of_usage'    =>'required',
            'usage_per_user' =>'required',
        );
         $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('admin/coupon/edit/'.$id)->withInput()->withErrors($validator);
        }
        $category->fill($data)->save();
        // redirect
        Session::flash('success_message', 'Coupon has been updated successfully!');
       return redirect('admin/coupon/coupon-list');
    }
     public function delete($id)
    {
        if($art = Coupon::find($id)){
            $art->delete();
            $data =  response('deleted',200);
            Session::flash('success_message', 'Category has been deleted successfully!');
        }else{
            $data = response('some_thing_is_wrong',500);
            Session::flash('success_message', 'Please Try Again!');
        }
        return $data;
    }
}