<?php
namespace App\Http\Controllers\Admin; //admin add
use App\Http\Requests;
use App\Http\Controllers\Controller;   // using controller class
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\User;
use App\Warehouse;
use App\Pincode;
use App\City;
use App\UserKyc;
use App\Admin;
use App\AddCoin;
use App\LevelIncome;
use App\RewardBonus;
use App\ActivationWallet;
use App\WorkingWallet;
use App\Transfer;
use App\UserNetwork;
use DB;
use URL;
use Excel;
use Helper;
use Response;
use File;
use Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class WareHouseController extends Controller
{
    var $sdata;
    public function __construct()
    {
		$this->sdata=Session::get('user_sdata');
        $this->middleware('auth.admin:admin');
    }

    public function index()
    {
        return view("admin.warehouse.index");
    }
    public function getWareHouseData(Request $request)
    {
		$data=Session::get('user_sdata');
	    $role_id=$data->role;
        $requestData = $_REQUEST;
        $columns = array(
            // column index  => database column name
            0 => 'warehouses.id',
            1 => 'warehouses.name',
            2 => 'warehouses.created_at',
        );
	
        if($data->role==2)
		{
         $totalUsers = Warehouse::with('get_city')->whereRaw("find_in_set(".$data->id.",subadmin_id)")->get()->count();			
	     $users = Warehouse::with('get_city')->whereRaw("find_in_set(".$data->id.",subadmin_id)")->select('warehouses.*')->orderBy('warehouses.id', 'desc'); 
    	}
		else
		{
		 $totalUsers = Warehouse::with('get_city')->get()->count();
		 $users = Warehouse::with('get_city')->select('warehouses.*')->orderBy('warehouses.id', 'desc');
		}
		
		$totalFiltered = $totalUsers;
		$searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value']))
        {
            $users=$users->where('name','LIKE','%'.$searchString.'%')->orWhere('email','LIKE','%'.$searchString.'%')->orWhere('mobile','LIKE','%'.$searchString.'%');
            $totalFiltered = Warehouse::where('name','LIKE','%'.$searchString.'%')->orWhere('email','LIKE','%'.$searchString.'%')->orWhere('mobile','LIKE','%'.$searchString.'%')
                ->get()->count();
        }

        $orderColumn = $columns[$requestData['order'][0]['column']];
        $orderColumnDir = $requestData['order'][0]['dir'];
        $limit = $requestData['length'];
        $offset = $requestData['start'];
        $users = $users->offset($offset)->limit($limit)->orderBy($orderColumn, $orderColumnDir)->get();
        $data = array();
        $i = $offset;
        $totalsubadmin= Admin::where('role',2)->get();
		$options="";
		foreach ($users as $item) {
            $i++;
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = $item->name;
            $nestedData[] = $item->get_city->name;
            $nestedData[] = $item->pincode;
            $date = strtotime($item->created_at);
            $nestedData[] = date('d-m-Y', $date);
		     
            			 
			$permissionLink = "<a href='javascript:void(0)' onclick='assign_subadmin(".$item->id.")'>Assign Subadmin</a>";
            if($item->status==1){ $class="on"; $title="active"; } else { $class="off"; $title="inactive"; }
            $deleteLink="";$ViewLink="";$editLink="";
			if(Helper::check_action($this->sdata->id,'warehouse','delete'))
			{
			  $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
            }
			
			if(Helper::check_action($this->sdata->id,'warehouse','delete'))
			{
			  $ViewLink = '<a href="' . URL::to('/') . '/admin/warehouse/view/'. $item->id .' " title="View"><i class="glyphicon glyphicon-eye-open"></i></a>';
            }
			if(Helper::check_action($this->sdata->id,'warehouse','edit'))
			{
			$editLink = '<a href="' . URL::to('/') . '/admin/warehouse/warehouse-edit/'. $item->id .' " title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>';
            }
			
			$activateLink = '<a href="' . URL::to('/') . '/admin/warehouse/update-status/'.$item->id.'" title="'.$title.'"><i class="fa fa-toggle-'.$class.'" aria-hidden="true" ></i></a>';
            $nestedData[] = $activateLink. ' | ' .$ViewLink." | ".$editLink. ' | ' .$deleteLink." | ".(($role_id!=2)?$permissionLink:'');
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
        $warehouse = Warehouse::where('id', $id)->first();
        return view('admin.warehouse.view')->with('warehouse', $warehouse);
    }
    public function add()
    {
        $city = City::where('status',1)->where('state_id',33)->get();
        return view('admin.warehouse.add')->with('cities',$city);
    }
    public function store(Request $request)
    {
        $sliderData = array(
            'name'     =>$request->input( 'name'),
            'city_id'    =>$request->input( 'city_id'),
            'address'     =>$request->input( 'address'),
            'pincode'    =>$request->input( 'pincode'),
            'lattitude'    =>$request->input( 'lattitude'),
            'longitude'    =>$request->input( 'longitude'),

        );
        $rules = array(
            'name'=>'required',
            'city_id'=>'required',
            'address'=>'required',
            'pincode'=>'required',
            'lattitude'=>'required',
            'longitude'=>'required',

        )   ;
        $validator = Validator::make($sliderData,$rules);
        if ($validator->fails()) {
            return redirect('admin/warehouse/add-warehouse')->withInput()->withErrors($validator);
        }else{
            $warehouseData = $request->all();
            $pincode = $request->input('pincode');
            $allPincode = implode(',', $pincode);
            $warehouseData['pincode'] = $allPincode;
            $user = new Warehouse($warehouseData);

            $user->save();
            Session::flash('success_message', 'Your warehouse has been added successfully');
            return redirect('/admin/warehouse/warehouse-list');
        }

    }
    public function edit($id)
    {
        $warehouse = Warehouse::find($id);
        $pincodeList = Pincode::where('city_id',$warehouse->city_id)->get();
        $cityList = City::where('status',0)->where('state_id',33)->get();
        return view('admin.warehouse.edit')->with(['warehouse'=>$warehouse, 'cityList' =>$cityList, 'pincodeList'=>$pincodeList]);
    }
    public function update($id, Request $request)
    {
        $warehouse = Warehouse::find($id);
        $validator = Validator::make($request->all(),
            [
                'name' => 'required',
                'city_id' => 'required',
                'address' => 'required',
                'pincode' => 'required',
                'lattitude'=>'required',
                'longitude'=>'required',

            ], [
                'name.required' => 'This field is required.',
                'city_id.required' => 'This field is required.',
                'address.required' => 'This field is required.',
                'pincode.required' => 'This field is required.',
                'warehouse_pincode.required' => 'This field is required.',
                'lattitude.required' => 'This field is required.',
                'longitude.required' => 'This field is required.',
            ]);

        if ($validator->fails())
        {
            return redirect('admin/warehouse/warehouse-edit/'.$id)->withInput()->withErrors($validator);
        }
        else
        {
            if($warehouse) {
                $data =$request->all();
                $pincode = $request->input('pincode');
                $allPincode = implode(',', $pincode);
                $data['pincode'] = $allPincode;
                $update_data = Warehouse::find($warehouse->id)->fill($data);
                $update_data->update();
                Session::flash('success_message', 'Warehouse Successfully updated');
                return redirect('admin/warehouse/warehouse-list');
            }
        }
    }


    public function delete()
    {
        $warehouse = Warehouse::findOrFail($_POST['id']);
        if(!empty($warehouse->delete()))
        {
            Session::flash('success_message', 'Warehouse has been deleted successfully!');
        }
        else {
            Session::flash('error_message', 'Unable to delete the Warehouse');
        }
    }

    function update_status($id=null)
    {
        $response=DB::statement("UPDATE warehouses SET status =(CASE WHEN (status = 1) THEN '0' ELSE '1' END) where id = $id");
        if($response) {
            Session::flash('success_message', 'status has been updated successfully!');
        }
        else {
            Session::flash('error_message', 'Unable to update status');
        }
        return redirect('/admin/warehouse/warehouse-list');
    }
    public function getPincode(Request $request)
    {
        $id= $request->input('id');
        $data=Pincode::where('city_id',$id)->get();
        return view('admin.ajax.get_pincode',compact('data'));
    }
	
	public function get_subadmin(Request $request)
    {
        $id= $request->input('id');
        $data=Admin::where('role',2)->get();
        $check=Warehouse::where('id',$id)->first();
        return view('admin.ajax.get_subadmin',compact('data','check'));
    }
	
	public function assign_subadmin_warehouse(Request $request)
    {
        $data['subadmin_id']= implode(",",$request->input('subadmin'));
        $id= $request->input('id');
		DB::table('warehouses')->where('id',$id)->update($data);
		Session::flash('sucess_message', 'Subadmin has been assigned successfully');
		return redirect('/admin/warehouse/warehouse-list');  
    }
	
	function subadmin_permission(Request $request)
	{
		$subadmin_id=$request->input('subadmin_id');
		$w_id=$request->input('w_id');
	    DB::table('warehouses')->where('id', $w_id)->update(['subadmin_id' =>$request->input('subadmin_id')]);
		return Response::json(array(
			'status_code' => 1,
			'message' => 'subadmin has been assigned successfully',
		), 200);
	}
    //END -------------------------------------------------//
}

?>