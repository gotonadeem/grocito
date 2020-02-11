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
use App\State;
use App\City;
use App\Admin;
use App\OrderMeta;
use Redirect;
use DB;
use QrCode;
use Helper;
use Response;
use URL;
use Excel;
use File;
use Mail; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AgentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }
    public function index()
    {
        return view("admin.agents.index");
    }
    //get list of record of subadmin...........................................................
    public function getAgentData(Request $request)
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
        $totalUsers = User::where('role_id',5)->where('verify_status','requested')->get()->count();
        $totalFiltered = $totalUsers;
        $users = User::select('*')->where('role_id',5)->where('verify_status','requested')->orderBy('id', 'desc');
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
            $nestedData[] = $item->unique_code;
            $nestedData[] = $item->simple_pass;
            $nestedData[] = $item->mobile;
            $date = strtotime($item->created_at);
            $nestedData[] = date('d-m-Y', $date);
			
             if($item->banned==0){ $class="on"; $title="active"; } else { $class="off"; $title="inactive"; }
              $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
              $ViewLink = '<a href="' . URL::to('/') . '/admin/merchant/view/'. $item->id .' " title="View"><i class="glyphicon glyphicon-eye-open"></i></a>';
			  $editLink = '<a href="' . URL::to('/') . '/admin/merchant/edit-merchant/'. $item->id .' " title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>';
              $activateLink = '<a href="' . URL::to('/') . '/admin/agent/update-status/'.$item->id.'" title="'.$title.'"><i class="fa fa-toggle-'.$class.'" aria-hidden="true" ></i></a>';
              $NoteLink = '<a href="'.URl::to('admin/user-note/'.$item->id).'" title="Create Note"><i class="fa fa-sticky-note"></i></a>';
           
			 $nestedData[] = $ViewLink." | ". $editLink." | ".$deleteLink." | ".$NoteLink." | ".$activateLink;
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
         return view("admin.agents.active");
     }
	 
    //get list of record of subadmin...........................................................
    public function getActiveAgentData(Request $request)
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
        $totalUsers = User::where('role_id',5)->where('verify_status','requested')->where('banned',0)->get()->count();
        $totalFiltered = $totalUsers;
        $users = User::select('*')->where('role_id',5)->where('verify_status','requested')->where('banned',0)->orderBy('id', 'desc');
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
			$nestedData[] = $item->unique_code;
            $nestedData[] = $item->simple_pass;
            $nestedData[] = $item->mobile;
            $nestedData[] = Helper::get_agent_value($item->id);
            $date = strtotime($item->created_at);
            $nestedData[] = date('d-m-Y', $date);
			
             if($item->banned==0){ $class="on"; $title="active"; } else { $class="off"; $title="inactive"; }
              $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
              $ViewLink = '<a href="' . URL::to('/') . '/admin/agent/view/'. $item->id .' " title="View"><i class="glyphicon glyphicon-eye-open"></i></a>';
			  $editLink = '<a href="' . URL::to('/') . '/admin/agent/edit-merchant/'. $item->id .' " title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>';
              $activateLink = '<a href="' . URL::to('/') . '/admin/agent/update-status/'.$item->id.'" title="'.$title.'"><i class="fa fa-toggle-'.$class.'" aria-hidden="true" ></i></a>';
              $NoteLink = '<a href="'.URl::to('admin/user-note/'.$item->id).'" title="Create Note"><i class="fa fa-sticky-note"></i></a>';
           
			 $nestedData[] = $ViewLink." | ". $editLink." | ".$deleteLink." | ".$NoteLink." | ".$activateLink;
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
         return view("admin.agents.inActive");
     }
	 
    //get list of record of subadmin...........................................................
    public function getinActiveAgentData(Request $request)
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
        $totalUsers = User::where('role_id',5)->where('verify_status','requested')->where('banned',1)->get()->count();
        $totalFiltered = $totalUsers;
        $users = User::select('*')->where('role_id',5)->where('verify_status','requested')->where('banned',1)->orderBy('id', 'desc');
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
			$nestedData[] = $item->unique_code;
            $nestedData[] = $item->simple_pass;
            $nestedData[] = $item->mobile;
            $date = strtotime($item->created_at);
            $nestedData[] = date('d-m-Y', $date);
			
             if($item->banned==0){ $class="on"; $title="active"; } else { $class="off"; $title="inactive"; }
              $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
              $ViewLink = '<a href="' . URL::to('/') . '/admin/merchant/view/'. $item->id .' " title="View"><i class="glyphicon glyphicon-eye-open"></i></a>';
			  $editLink = '<a href="' . URL::to('/') . '/admin/merchant/edit-merchant/'. $item->id .' " title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>';
              $activateLink = '<a href="' . URL::to('/') . '/admin/agent/update-status/'.$item->id.'" title="'.$title.'"><i class="fa fa-toggle-'.$class.'" aria-hidden="true" ></i></a>';
              $NoteLink = '<a href="'.URl::to('admin/user-note/'.$item->id).'" title="Create Note"><i class="fa fa-sticky-note"></i></a>';
           
			 $nestedData[] = $ViewLink." | ". $editLink." | ".$deleteLink." | ".$NoteLink." | ".$activateLink;
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
		$userCount = User::select(
            DB::raw('count(id) as total_count'), 
            DB::raw("DATE_FORMAT(created_at,'%M %Y') as months")
			  )->where('agent_id',$id)->groupBy('months')->get();
			  
        
		$user = UserKyc::with('user','country','state','city')->where('user_id', $id)->first();
        return view('admin.agents.view')->with('user', $user)->with('id', $id)->with('userCount', $userCount);
		
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
		$state_list= Country::get();
        $user = UserKyc::with('user')->where('user_id',$id)->first();
        return view('admin.merchant.edit')->with(['user'=>$user,'state_list'=>$state_list]);
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
	    'username'=>$request->input('username'),
		);
		
        $rules = array(
            'username'  =>'required|unique:users,username,'.$id,
            'email'     =>'required|unique:users,email,'.$id,
	        'mobile'    =>'required|unique:users,mobile,'.$id,
	        'f_name'    =>'required',
	        'l_name'    =>'required',

        );
        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
            return redirect('admin/agent/edit-agent/'.$id)->withInput()->withErrors($validator);
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
        $state_list= State::get();
        return view('admin.agents.add')->with('state_list',$state_list);
    }

    public function get_state(Request $request)
    {
        $id=$request->input('id');
        $state_list= State::where('country_id',$id)->get();
        return view('admin.seller.state_ajax')->with('state_list',$state_list);
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
	    'role_id'=>5,
        'unique_code'=>strtoupper($this->getNextNumber($request->f_name)),
	    'username'=>$request->input('username'),
		);
		
        $rules = array(
            'username'  =>'required|unique:users,username',
            'email'     =>'required|email|unique:users',
	        'mobile'    =>'required|unique:users,mobile',
	        'f_name'    =>'required',
	        'l_name'    =>'required',

        );
        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
            return redirect('admin/seller/create-seller')->withInput()->withErrors($validator);
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
        Session::flash('success_message', 'Your merchant has been added successfully');
        return redirect('/admin/agent/agent-list');
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
	
}

?>