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
use App\DeliveryBoyRide;
use App\Warehouse;
use App\State;
use App\City;
use App\Admin;
use App\OrderMeta;
use App\DeliveryBoySetting;
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
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Database;
class DeliveryBoyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }
    public function index()
    {
        return view("admin.merchant.index");
    }
    //get list of record of subadmin...........................................................
    public function getDeliveryBoyData(Request $request)
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
        $totalUsers = User::where('role_id',4)->where('verify_status','requested')->get()->count();
        $totalFiltered = $totalUsers;
        $users = User::with('user_kyc','user_kyc.city')->select('*')->where('role_id',4)->where('verify_status','requested')->orderBy('id', 'desc');
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
            $nestedData[] = $item->username;
            $nestedData[] = $item->user_kyc->city ? $item->user_kyc->city->name :'';
            $nestedData[] = $item->email;
            $nestedData[] = $item->simple_pass;
            $nestedData[] = $item->mobile;
            $date = strtotime($item->created_at);
            $nestedData[] = date('d-m-Y', $date);
			
             if($item->banned==0){ $class="on"; $title="active"; } else { $class="off"; $title="inactive"; }
              $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
              $ViewLink = '<a href="' . URL::to('/') . '/admin/delivery-boy/view/'. $item->id .' " title="View"><i class="glyphicon glyphicon-eye-open"></i></a>';
			  $editLink = '<a href="' . URL::to('/') . '/admin/delivery-boy/edit-delivery-boy/'. $item->id .' " title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>';
              $activateLink = '<a href="' . URL::to('/') . '/admin/delivery-boy/update-status/'.$item->id.'" title="'.$title.'"><i class="fa fa-toggle-'.$class.'" aria-hidden="true" ></i></a>';
              $NoteLink = '<a href="'.URl::to('admin/user-note/'.$item->id).'" title="Create Note"><i class="fa fa-sticky-note"></i></a>';

			 $nestedData[] = $activateLink." | ".$ViewLink." | ". $editLink." | ".$deleteLink." | ".$NoteLink ;
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
	
	
	public function riderLocation($id){
        $rider = User::where('id',$id)->first();
        $driverLat =26.849684;
        $driverLong =75.769188;
        $serviceAccount = ServiceAccount::fromJsonFile(public_path().'/grocito-c6f82-firebase-adminsdk-z0mu8-4b836aeef4.json');
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->withDatabaseUri('https://grocito-c6f82.firebaseio.com/')
            ->create();

        $database = $firebase->getDatabase();
        $reference = $database->getReference('grocitorider')
            ->getSnapshot()->getValue()
        ;
        if(count($reference)>0){
            if(array_key_exists($id,$reference)){
                $driverLat = $reference[$id]['l']['0'];
                $driverLong = $reference[$id]['l']['1'];
            }

        }

        return view("admin.merchant.rider_location",compact('rider','id','driverLat','driverLong'));
    }
	
	public function active()
     {
         return view("admin.merchant.active");
     }
	 
    //get list of record of subadmin...........................................................
    public function getActiveDeliveryBoyData(Request $request)
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
        $totalUsers = User::where('role_id',4)->where('verify_status','requested')->where('banned',0)->get()->count();
        $totalFiltered = $totalUsers;
        $users = User::with('user_kyc','user_kyc.city','user_kyc.warehouse')->select('*')->where('role_id',4)->where('verify_status','requested')->where('banned',0)->orderBy('id', 'desc');
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
            $nestedData[] = $item->username;
            $nestedData[] = $item->user_kyc->city ? $item->user_kyc->city->name :'';
            $nestedData[] = $item->email;
            $nestedData[] = $item->simple_pass;
            $nestedData[] = $item->mobile;
            $nestedData[] = (!is_null($item->user_kyc->warehouse)?$item->user_kyc->warehouse->name:"");
            $date = strtotime($item->created_at);
            $nestedData[] = date('d-m-Y', $date);
			
             if($item->banned==0){ $class="on"; $title="active"; } else { $class="off"; $title="inactive"; }
              $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
              $ViewLink = '<a href="' . URL::to('/') . '/admin/delivery-boy/view/'. $item->id .' " title="View"><i class="glyphicon glyphicon-eye-open"></i></a>';
			  $editLink = '<a href="' . URL::to('/') . '/admin/delivery-boy/edit-delivery-boy/'. $item->id .' " title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>';
              $activateLink = '<a href="' . URL::to('/') . '/admin/delivery-boy/update-status/'.$item->id.'" title="'.$title.'"><i class="fa fa-toggle-'.$class.'" aria-hidden="true" ></i></a>';
              $NoteLink = '<a href="'.URl::to('admin/user-note/'.$item->id).'" title="Create Note"><i class="fa fa-sticky-note"></i></a>';
            $locaLink = '<a href="'.URl::to('admin/delivery-boy/rider-location/'.$item->id).'" title="Location"><i class="fa fa-map-marker" aria-hidden="true"></i></a>';
            $onlineIndicator = $item->is_active==1?'<i class="fa fa-circle" style="color:green"></i>':'<i class="fa fa-circle" style="color:red"></i>';

            $nestedData[] = $activateLink." | ".$ViewLink." | ". $editLink." | ".$deleteLink." | ".$NoteLink ." | ".$locaLink." | ".$onlineIndicator;
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
	
	public function inActive()
     {
         return view("admin.merchant.inActive");
     }
	 
    //get list of record of subadmin...........................................................
    public function getinActiveDeliveryBoyData(Request $request)
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
        $totalUsers = User::where('role_id',4)->where('verify_status','requested')->where('banned',1)->get()->count();
        $totalFiltered = $totalUsers;
        $users = User::with('user_kyc','user_kyc.city')->select('*')->where('role_id',4)->where('verify_status','requested')->where('banned',1)->orderBy('id', 'desc');
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
            $nestedData[] = $item->username;
            $nestedData[] = $item->user_kyc->city ? $item->user_kyc->city->name :'';
            $nestedData[] = $item->email;
            $nestedData[] = $item->simple_pass;
            $nestedData[] = $item->mobile;
            $date = strtotime($item->created_at);
            $nestedData[] = date('d-m-Y', $date);
			
             if($item->banned==0){ $class="on"; $title="active"; } else { $class="off"; $title="inactive"; }
              $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
              $ViewLink = '<a href="' . URL::to('/') . '/admin/delivery-boy/view/'. $item->id .' " title="View"><i class="glyphicon glyphicon-eye-open"></i></a>';
			  $editLink = '<a href="' . URL::to('/') . '/admin/delivery-boy/edit-delivery-boy/'. $item->id .' " title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>';
              $activateLink = '<a href="' . URL::to('/') . '/admin/delivery-boy/update-status/'.$item->id.'" title="'.$title.'"><i class="fa fa-toggle-'.$class.'" aria-hidden="true" ></i></a>';
              $NoteLink = '<a href="'.URl::to('admin/user-note/'.$item->id).'" title="Create Note"><i class="fa fa-sticky-note"></i></a>';
           
			 $nestedData[] = $activateLink." | ".$ViewLink." | ". $editLink." | ".$deleteLink." | ".$NoteLink;
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
	

 

    //unpaid_user.................................

    public function unpaid_users()
    {
        return view("admin.unpaid_user.index");
    }


	function send_email(Request $request)
	{
		        $userData=Session::get('user_data');
		        $msg=$request->input('message');
		        $subject=$request->input('subject');
		         $emailData = array(
                    'to'        => array($request->input('email')),
                    'from'      => $userData->email,
                    'subject'   => $subject,
                    'view'      => 'email.seller-email',
                    'content'=>$msg
                );
                $status=Mail::send($emailData['view'], $emailData, function ($message) use ($emailData) {
                    $message
                        ->to($emailData['to'])
                        ->from($emailData['from'])
                        ->subject($emailData['subject']);

                });
				if($status)
				{
				    echo json_encode(array('status'=>true,'message'=>'Mail has been sent successfully'));
				}
				else
				{
					echo json_encode(array('status'=>false,'message'=>'Please try again'));
				}
	}
	
    function view($id=null)
    {
        $user = UserKyc::with('user','country','state','city')->where('user_id', $id)->first();
        $riderHistory = DB::table('rider_login_history')->select('created_at as loginDate','login_time','is_login')->where('rider_id',$id)->orderBy('id','desc')->get();
        $deliveryBoyOrder = DeliveryBoyRide::with('order')->where('delivery_boy_id',$id)->get();
        return view('admin.merchant.view')->with('user', $user)->with('id', $id)->with('riderHistory',$riderHistory)->with('deliveryBoyOrder',$deliveryBoyOrder);
    }
	
	 public function getSellerProductData(Request $request)
    {
		$id=$request->input('id');
        $requestData = $_REQUEST;
        $columns = array(
            0 => 'products.id',
            1 => 'users.username',
            2 => 'products.name',
            3 => 'products.starting_price',
            4 => 'products.description',
        );
        $totalAmenities = Product::with('user_name','main_category','sub_category')->where('user_id',$id)->get()->count();
        $totalFiltered  = $totalAmenities;
        $amenities = Product::with('user_name','main_category','sub_category')->select('products.*')->where('user_id',$id)->orderBy('products.id', 'desc');
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value']))
        {
            $amenities->where('name','LIKE','%'.$searchString.'%')->orWhere('watermark','LIKE','%'.$searchString.'%')->orWhereHas('main_category', function ($query) use ($searchString)
            {
                $query->whereRaw("main_category.name  LIKE '%" . $searchString . "%'");
            });
            $totalFiltered = Product::where('name','LIKE','%'.$searchString.'%')->orWhere('watermark','LIKE','%'.$searchString.'%')->orWhereHas('main_category', function ($query) use ($searchString)
            {
                $query->whereRaw("main_category.name LIKE '%" . $searchString . "%'");
            })
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
            $nestedData[] = !is_null($item->main_category)?$item->main_category->name:'';
            $nestedData[] = ((strlen($item->name)>50)?wordwrap(substr($item->name,0,50),20,"<br>\n"):wordwrap($item->name,20,"<br>\n"));
            $nestedData[] = $item->starting_price;
            $nestedData[] = $item->sell_price;
            $date = strtotime($item->created_at);
            $nestedData[] = date('d-m-Y', $date);
            if($item->is_admin_approved==1){ $class="on"; $title="active"; } else { $class="off"; $title="inactive"; }
            $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
            $editLink = '<a href="' . URL::to('/') . '/admin/product/edit/'. $item->id .' " title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>';
            $ViewLink = '<a href="' . URL::to('/') . '/admin/product/show/'. $item->id .' " title="View"><i class="glyphicon glyphicon-eye-open"></i></a>';
            $activateLink = '<a href="' . URL::to('/') . '/admin/product/update-status/'.$item->id.'" title="'.$title.'"><i class="fa fa-toggle-'.$class.'" aria-hidden="true" ></i></a>';
            $NoteLink = '<a href="'.URl::to('admin/user-note/'.$item->id).'" title="Create Note"><i class="fa fa-sticky-note"></i></a>';
			$nestedData[] = $activateLink ." | ".$ViewLink."|". $editLink." | ".$deleteLink." | ". $NoteLink;
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
        return redirect()->back();
    }
	
	function update_verify_status($id=null)
    {
		$status=User::with('user_kyc')->where('id',$id)->first();
		$agreement=Agreement::first();
        if($status->verify_status=="kyc_completed")
		{
			    $mmsg="Hi ".$status->user_kyc->f_name.", \n  Welcome to Cartlay\n";
                $mmsg.="Your Account has been varified successfully. Please login using below email and password \n";
                $mmsg.="Your email address is: ".$status->email."\n\n";
                $mmsg.="Your Password is : ".$status->simple_pass."\n\n";
                $mmsg.="Click on Below link to Login As seller \n";
                $mmsg.="http://seller.cartlay.com \n";
                $mmsg.="\n\n Thanks Cartlay";
		        Helper::send_msg($status->mobile,$mmsg);
				
				$msg="Hi ".$status->user_kyc->f_name.", <br><br>   Welcome to Cartlay<br><br>";
                $msg.="Your Account has been varified successfully. Please login using below email and password<br>";
                $msg.="Your email address is: ".$status->email."<br><br>";
                $msg.="Your Password is : ".$status->simple_pass."<br><br>";
                $msg.="<a href='http://seller.cartlay.com'>Click</a> on Below link to login <br>";
                $msg.="<a href='http://seller.cartlay.com'>Login As seller</a><br>";
                $msg.="<h2>Your Agreement</h2>";
				 $tillDate = date('d-m-Y', strtotime("+3 months", strtotime($status->user_kyc->created_at)));
			     $agreement_data= str_replace("@@current_date@@",date('d-m-Y', strtotime($status->user_kyc->created_at)),$agreement->description);
			     $agreement_data= str_replace("@@seller_name@@",$status->user_kyc->f_name." ".$status->user_kyc->l_name,$agreement_data);
			     $agreement_data= str_replace("@@seller_address@@",$status->user_kyc->address_1,$agreement_data);
			     $agreement_data= str_replace("@@valid_till@@",$tillDate,$agreement_data);
				 $agreement_data= str_replace("@@commission@@",$status->user_kyc->cartlay_commission,$agreement_data);
			   
                $msg.="<b>".$agreement_data."</b>";
				
                $msg.="<br> <br>  Thanks Cartlay";
				
                $emailData = array(
                    'to'        => array($status->email),
                    'from'      => 'support@cartlay.com',
                    'subject'   => 'Account Varified',
                    'view'      => 'email.verification-email',
                    'content'=>$msg
					
                );
                /*Mail::send($emailData['view'], $emailData, function ($message) use ($emailData) {
                    $message
                        ->to($emailData['to'])
                        ->from($emailData['from'])
                        ->subject($emailData['subject']);

                });*/
		}
		 
		$response=DB::statement("UPDATE users SET verify_status =(CASE WHEN (verify_status = 'kyc_completed') THEN 'verified' ELSE 'kyc_completed' END) where id = $id");
        if($response) {
            Session::flash('success_message', 'status has been updated successfully!');
        }
        else {
            Session::flash('error_message', 'Unable to update status');
        }
        return redirect('/admin/seller/verified-seller-list');
    }
	
	function update_block_status($id=null)
    {
        $response=DB::statement("UPDATE users SET banned =(CASE WHEN (banned = 1) THEN '0' ELSE '1' END) where id = $id");
        if($response) {
            Session::flash('success_message', 'status has been updated successfully!');
        }
        else {
            Session::flash('error_message', 'Unable to update status');
        }
        return redirect('/admin/seller/unverified-seller-list');
    }
	function update_block_status_verified($id=null)
    {
        $response=DB::statement("UPDATE users SET banned =(CASE WHEN (banned = 1) THEN '0' ELSE '1' END) where id = $id");
        if($response) {
            Session::flash('success_message', 'status has been updated successfully!');
        }
        else {
            Session::flash('error_message', 'Unable to update status');
        }
        return redirect('/admin/seller/verified-seller-list');
    }
	

	public function edit($id)
    {
        //$Package = Package::first();
		$city_list= City::where('status',1)->get();
        $user = UserKyc::with('user')->where('user_id',$id)->first();
	    $warehouse_list= Warehouse::where('city_id',$user->city_id)->get();
        return view('admin.merchant.edit')->with(['warehouse_list'=>$warehouse_list,'user'=>$user,'city_list'=>$city_list,'warehouse_list'=>$warehouse_list]);
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
		$user = User::find($id);
          $userData = array(
		'mobile'=>$request->input('mobile'),
	    'email'=>strtolower($request->input('email')),
	    'username'=>$request->input('f_name').$request->input('l_name'),
		);
		
        $rules = array(
            'username'  =>'required',
            'email'     =>'required|unique:users,email,'.$id,
	        'mobile'    =>'required|unique:users,mobile,'.$id,
        );
        $validator = Validator::make($userData,$rules);
        if ($validator->fails()) {
            return redirect('admin/seller/edit-seller/'.$id)->withInput()->withErrors($validator);
        }else{
			 $kycData=$request->all();
			 
				if($request->hasFile('profile_image'))
				{
                $image = $request->file('profile_image');
                $path_original = public_path() . '/admin/uploads/seller/';
                $file = $request->profile_image;
                $photo_name = time() . '-' . $file->getClientOriginalName();
                $file->move($path_original, $photo_name);
                $kycData['profile_image'] = $photo_name;
                }
            if($request->hasFile('aadhar_image'))
            {
                $image = $request->file('aadhar_image');
                $path_original = public_path() . '/admin/uploads/seller/';
                $file = $request->aadhar_image;
                $photo_name = time() . '-' . $file->getClientOriginalName();
                $file->move($path_original, $photo_name);
                $kycData['aadhar_image'] = $photo_name;
            }
            if($request->hasFile('driving_licence_image'))
            {
                $image = $request->file('driving_licence_image');
                $path_original = public_path() . '/admin/uploads/seller/';
                $file = $request->driving_licence_image;
                $photo_name = time() . '-' . $file->getClientOriginalName();
                $file->move($path_original, $photo_name);
                $kycData['driving_licence_image'] = $photo_name;
            }
			 $update_data = User::find($user->id)->fill($userData);
             $update_data->update();
			 
			  $userKyc=UserKyc::where('user_id', '=',$user->id)->first();
			  $userKyc->update($kycData);
			 }
        // redirect
        Session::flash('success_message', 'Your merchant has been added successfully');
       return redirect(url()->previous());
    }
	
	public function add()
    {
        $city_list= City::where('status',1)->get();
        return view('admin.merchant.add')->with('city_list',$city_list);
    }

    public function get_state(Request $request)
    {
        $id=$request->input('id');
        $state_list= State::where('country_id',$id)->get();
        return view('admin.seller.state_ajax')->with('state_list',$state_list);
    }
	
	public function get_warehouse(Request $request)
    {
        $id=$request->input('id');
        $warehouse_list= Warehouse::where('city_id',$id)->get();
        return view('admin.merchant.warehouse_ajax')->with('warehouse_list',$warehouse_list);
    }

    public function get_city(Request $request)
    {
        $id=$request->input('id');
        $city_list= City::where('state_id',$id)->get();
        return view('admin.seller.state_ajax')->with('state_list',$city_list);
    }

	function check_user(Request $request)
	{
		  $value=$request->input('value');
		  $column_name=$request->input('column');
          $count= User::where("$column_name",$value)->get()->count();
		  $column_name= ucfirst($column_name);
		  if($count)
		  {
			  $data=array('status'=>false,'msg'=>"$column_name is already exist");
		  }
		  else
		  {
			  $data=array('status'=>true);
		  }
		  echo json_encode($data);
	}
    public function getNextNumber($value)
   {
        // Get the last created order
        $lastUser = User::orderBy('id', 'desc')->first();
        if ( ! $lastUser )
        {
            
            $number = 0;
        }
        else 
        { 
          $number = $lastUser->id+1;
          return substr($value,0,2) . sprintf('%04d', intval($number));
        }
 }
    public function store(Request $request)
    {
        $userData = array(
		'mobile'=>$request->input('mobile'),
	    'email'=>strtolower($request->input('email')),
	    'unique_code'=>strtoupper(substr($request->input('f_name'),0,2).str_random(4)),
	    'role_id'=>4,
        'username'=>$request->input('f_name').$request->input('l_name'),
		);
		
        $rules = array(
            'username'  =>'required',
            'email'     =>'required|email|unique:users',
	        'mobile'    =>'required|unique:users,mobile',
        );
        $validator = Validator::make($userData,$rules);
        if ($validator->fails()) {
            return redirect('admin/delivery-boy/create-delivery-boy')->withInput()->withErrors($validator);
        }else{
			    $password=str_random(3).rand(123,456);
			    $userData['password'] =    Hash::make($password);
			    $userData['simple_pass'] =    $password;
			    $kycData=$request->all();
				if($request->hasFile('profile_image'))
				{
                $image = $request->file('profile_image');
                $path_original = public_path() . '/admin/uploads/seller/';
                $file = $request->profile_image;
                $photo_name = time() . '-' . $file->getClientOriginalName();
                $file->move($path_original, $photo_name);
                $kycData['profile_image'] = $photo_name;
                }
            if($request->hasFile('aadhar_image'))
            {
                $image = $request->file('aadhar_image');
                $path_original = public_path() . '/admin/uploads/seller/';
                $file = $request->aadhar_image;
                $photo_name = time() . '-' . $file->getClientOriginalName();
                $file->move($path_original, $photo_name);
                $kycData['aadhar_image'] = $photo_name;
            }
            if($request->hasFile('driving_licence_image'))
            {
                $image = $request->file('driving_licence_image');
                $path_original = public_path() . '/admin/uploads/seller/';
                $file = $request->driving_licence_image;
                $photo_name = time() . '-' . $file->getClientOriginalName();
                $file->move($path_original, $photo_name);
                $kycData['driving_licence_image'] = $photo_name;
            }
    			 $user = new User($userData);
                 $user->save();
    			 $user_id= $user->id;
    			 $kycData['user_id']= $user_id;
    			 $userKyc = new UserKyc($kycData);	
                 $userKyc->save();
    			 }
               // redirect
		        $mmsg="Hi ".$request->input('f_name').", \n  Welcome to Cartlay\n";
                $mmsg.="Your Account has been created successfully. Please login using below email and password \n";
                $mmsg.="Your email address is: ".strtolower($request->input('email'))."\n\n";
                $mmsg.="Your Password is : ".$password."\n\n";
                $mmsg.="Click on Below link to Login As seller \n";
                $mmsg.="http://seller.cartlay.com \n";
                $mmsg.="\n\n Thanks Cartlay";
		         //Helper::send_msg($userData['mobile'],$mmsg);
				 
		        $msg="Hi ".$request->input('f_name').", <br><br>   Welcome to Cartlay<br><br>";
                $msg.="Your Account has been created successfully. Please login using below email and password<br>";
                $msg.="Your email address is: ".$request->input('email')."<br><br>";
                $msg.="Your Password is : ".$password."<br><br>";
                $msg.="<a href='http://seller.cartlay.com'>Click</a> on Below link to login <br>";
                $msg.="<a href='http://seller.cartlay.com'>Login As seller</a><br>";
                $msg.="<br> <br>  Thanks Cartlay";
		        $emailData = array(
                    'to'        => array($request->input('email')),
                    'from'      => 'support@saleplus.com',
                    'subject'   => 'Account Created',
                    'view'      => 'email.verification-email',
                    'content'=>$msg
                );
               /* Mail::send($emailData['view'], $emailData, function ($message) use ($emailData) {
                    $message
                        ->to($emailData['to'])
                        ->from($emailData['from'])
                        ->subject($emailData['subject']);

                });*/
        Session::flash('success_message', 'Delivery has been added successfully');
        return redirect('/admin/delivery-boy/delivery-boy-list');
    }
	
	function subadmin_permission(Request $request)
	{
		$subadmin_id=$request->input('subadmin_id');
		$seller_id=$request->input('seller_id');
	    DB::table('users')->where('id', $seller_id)->update(['subadmin_id' =>$request->input('subadmin_id')]);
		return Response::json(array(
			'status_code' => 1,
			'message' => 'subadmin has been assigned successfully',
		), 200);
	}
	
	
	public function income_setting()
    {
        $data= DeliveryBoySetting::first();
		
        return view('admin.merchant.income_setting')->with('data',$data);
    }
	
	public function income_setting_store(Request $request)
    {
		$perkm=$request->input('per_km');
		$bonus=$request->input('bonus');
		$cod_limit=$request->input('cod_limit');
		$base_income=$request->input('base_income');
	    DB::table('delivery_boy_commissions')->update(['per_km' =>$request->input('per_km'),'cod_limit' =>$request->input('cod_limit'),'bonus'=>$bonus,'base_income'=>$base_income]);
	    Session::flash('success_message', 'Updated successfully');
        return redirect()->back();
    }
	
	public function cod_payment()
    {
        $cod_payment= DeliveryBoyRide::with('order','user')->where('payment_mode','cod')->whereIn('type',array('warehouse_to_customer'))->where('is_cod_submitted',0)->orderBy('id','DESC')->paginate(20);
		return view('admin.merchant.cod_payment')->with('data',$cod_payment);
    }
	
	public function accept_cod_payment($id)
    {
        DB::table('delivery_boy_rides')->where('id',$id)->update(['is_cod_submitted'=>1]);
	    Session::flash('success_message', 'Accepted successfully');
        return redirect()->back();
    }
	
}

?>