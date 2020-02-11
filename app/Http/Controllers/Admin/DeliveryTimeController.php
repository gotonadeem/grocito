<?php
namespace App\Http\Controllers\Admin; //admin add
use App\Http\Requests;
use App\Http\Controllers\Controller;   // using controller class
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\City;
use App\Pincode;
use App\State;
use App\DeliveryTime;
use App\Delivery;
use DB;
use PDF;
use URL;
use DNS1D;
use DNS2D;
use Excel;
use File;
use Mail;
use Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
class DeliveryTimeController extends Controller
{
	
	 public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }
	
	function index()
	{
	   return view('admin.delivery_time.index');
	   
	}
	
	function getDeliveryTimeData(Request $request)
	{
		$requestData = $_REQUEST;
		$columns = array(
            0 => 'cities.id',
            1 => 'cities.name',
        );
        $totalAmenities = City::with('delivery_time')->where('status',1)->get()->count();
        $totalFiltered  = $totalAmenities;
        $amenities = City::with('delivery_time')->where('status',1)->orderBy('id', 'desc');
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value']))
        {
            $amenities->where('name','LIKE','%'.$searchString.'%');
            $totalFiltered = City::where('name','LIKE','%'.$searchString.'%')
                ->get()->count();
        }
		

        $orderColumn = $columns[$requestData['order'][0]['column']];
        $orderColumnDir = $requestData['order'][0]['dir'];
        $limit = $requestData['length'];
        $offset = $requestData['start'];
        $users = $amenities->offset($offset)->limit($limit)->orderBy($orderColumn, $orderColumnDir)->get();
        $data = array();
        $i = $offset;
        foreach ($users as $item) {
            $i++;
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = $item->name;
            $nestedData[] = $item->state->name;
            $nestedData[] = $item->delivery_time?$item->delivery_time->time_interval:1;
            $nestedData[] = $item->delivery_time?$item->delivery_time->express_time:45;
            //$nestedData[] = $item->status;
            $date = strtotime($item->created_at);
           /* $nestedData[] = date('d-m-Y', $date);*/
            $startTime = $item->delivery_time ? $item->delivery_time->start_time : "10:00AM";
            $EndTime = $item->delivery_time ? $item->delivery_time->end_time : "07:00PM";

            $standardLink = '<a href="javascript:void(0);" onclick="showModel(' . $item->id . ','.($item->delivery_time?$item->delivery_time->time_interval:1).','."'".$startTime."'".','."'".$EndTime."'".')" title="Standard">Standard Time</a>';
            $expressLink = '<a href="javascript:void(0);" onclick="showExpressModel('.$item->id.','.($item->delivery_time?$item->delivery_time->express_time:'45').')" title="Express">Express Time</a>';
            $deliveryCharge = '<a href="' . URL::to('/') . '/admin/delivery-charge/'.$item->id.'" title="Delivery Charges">Delivery Charges</a>';
            $nestedData[] = $standardLink.' | '. $expressLink.' | '.$deliveryCharge;
            $data[] = $nestedData;

        }
        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalAmenities),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        echo json_encode($json_data);
	}

    public function updateDeliveryTime(Request $request){
        $data['city_id'] = $request->city_id;
        $data['time_interval'] = $request->time_interval;
        //check exists
        $ifExists = DeliveryTime::where('city_id',$request->city_id)->count();
        if($ifExists > 0){
            DB::table('delivery_times')->where('city_id',$request->city_id)->update(array('time_interval' => $request->time_interval,'start_time'=>$request->start_time,'end_time'=>$request->end_time));
        }else{
            DB::table('delivery_times')->insert(array(
                    'city_id'     =>   $request->city_id,
                    'time_interval'   =>  $request->time_interval,
                    'start_time'   =>  $request->start_time,
                    'end_time'   =>  $request->end_time
                )
            );
        }


            return Response::json(array(
                'status_code' => 1,
                'message' => 'Send Successfully',
            ), 200);

    }
    public function updateExpressTime(Request $request){
        $data['city_id'] = $request->city_id;
        $data['express_time'] = $request->express_time;
        //check exists
        $ifExists = DeliveryTime::where('city_id',$request->city_id)->count();
        if($ifExists > 0){
            DB::table('delivery_times')->where('city_id',$request->city_id)->update(array('express_time' => $request->express_time));
        }else{
            DB::table('delivery_times')->insert(array(
                    'city_id'     =>   $request->city_id,
                    'express_time'   =>  $request->express_time,

                )
            );
        }
        return Response::json(array(
            'status_code' => 1,
            'message' => 'Send Successfully',
        ), 200);

    }
    public function deliveryCharge($id)
    {
        $city = City::where('id',$id)->first();
        $data= Delivery::where('city_id',$id)->where('type','standard')->first();
        return view('admin.delivery_time.delivery_charges',compact('data','city'));
    }

/////////////////
}
?>