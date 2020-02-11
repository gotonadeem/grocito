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

class SellerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }
    public function index()
    {
        return view("admin.seller.index");
    }
    //get list of record of subadmin...........................................................
    public function getSellerData(Request $request)
    {  
     
        $requestData = $_REQUEST;
        $columns = array(
            // column index  => database column name
            0 => 'users.id',
            1 => 'users.email',
            2 => 'users.mobile',
            3 => 'users.created_at',
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
            
            $nestedData[] = $item->email;

            $nestedData[] = $item->mobile;
            $date = strtotime($item->created_at);
            $nestedData[] = date('d-m-Y', $date);
			
             if($item->banned==0){ $class="on"; $title="active"; } else { $class="off"; $title="inactive"; }
              $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
              $ViewLink = '<a href="' . URL::to('/') . '/admin/seller/view/'. $item->id .' " title="View"><i class="glyphicon glyphicon-eye-open"></i></a>';
			  $editLink = '<a href="' . URL::to('/') . '/admin/seller/edit-seller/'. $item->id .' " title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>';
              //$activateLink = '<a href="' . URL::to('/') . '/admin/seller/update-status/'.$item->id.'" title="'.$title.'"><i class="fa fa-toggle-'.$class.'" aria-hidden="true" ></i></a>';
             // $NoteLink = '<a href="'.URl::to('admin/user-note/'.$item->id).'" title="Create Note"><i class="fa fa-sticky-note"></i></a>';
           
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
	
	
	 public function unverified_seller()
     {
        return view("admin.seller.unverified_seller");
     }
	 
    //get list of record of subadmin...........................................................
    public function getUnverifiedSellerData(Request $request)
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
        $totalUsers = User::with('user_kyc')->where('role_id',2)->get()->where('verify_status','kyc_completed')->where('banned',0)->count();
        $totalFiltered = $totalUsers;
        $users = User::with('user_kyc')->select('*')->where('role_id',2)->where('verify_status','kyc_completed')->where('banned',0)->orderBy('id', 'desc');
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value']))
        {
			$users=$users->where(function($query) use ($searchString) {
			   return $query->where('username','LIKE','%'.$searchString.'%')
                    ->orWhere('mobile','LIKE','%'.$searchString.'%')
					->orWhere('email','LIKE','%'.$searchString.'%');
			});
			$totalFiltered=User::where('verify_status','kyc_completed')->where(function($query) use ($searchString) {
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
		//dd($users);
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
			
             if($item->verify_status=='kyc_completed'){ $class="off"; $title="inactive"; } else { $class="on"; $title="active"; }
             if($item->banned==0){ $lockclass="unlock"; $locktitle="Active"; } else { $lockclass="lock"; $locktitle="Inactive";}
              
			  $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
              $ViewLink = '<a href="' . URL::to('/') . '/admin/seller/view/'. $item->id .' " title="View"><i class="glyphicon glyphicon-eye-open"></i></a>';
			  $editLink = '<a href="' . URL::to('/') . '/admin/seller/edit-seller/'. $item->id .' " title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>';
              $activateLink = '<a href="' . URL::to('/') . '/admin/seller/update-verify-status/'.$item->id.'" title="'.$title.'"><i class="fa fa-toggle-'.$class.'" aria-hidden="true" ></i></a>';
              $blockLink = '<a href="' . URL::to('/') . '/admin/seller/update-block-status/'.$item->id.'" title="'.$locktitle.'"><i class="fa fa-'.$lockclass.'" aria-hidden="true" ></i></a>';
              $whatappLink = '<a href="https://api.whatsapp.com/send?phone=+91'.$item->mobile.'"  id='.$item->id.' title="Chat Now"><i class="fa fa-whatsapp"></i></a>';
              $NoteLink = '<a href="'.URl::to('admin/user-note/'.$item->id).'" title="Create Note"><i class="fa fa-sticky-note"></i></a>';
           
			  $nestedData[] = $ViewLink." | ".$activateLink." | ". $editLink." | ".$deleteLink." | ".$blockLink." | ".$whatappLink." | ".$NoteLink;
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
	
	 public function verified_seller()
     {
        return view("admin.seller.verified_seller");
     }
	 
    //get list of record of subadmin...........................................................
    public function getVerifiedSellerData(Request $request)
    {  
	
	$data=Session::get('user_sdata');
	$role_id=$data->role;
	
        $requestData = $_REQUEST;
        $columns = array(
            // column index  => database column name
            0 => 'users.id',
            1 => 'users.username',
            2 => 'users.email',
            3 => 'users.mobile',
            4 => 'users.created_at',
        );
		if($data->role==2)
		{ 
	     $totalUsers = User::with('user_kyc')->where('role_id',2)->where('subadmin_id',$data->id)->where('banned',0)->get()->where('verify_status','verified')->count();
         $users = User::with('user_kyc','user_kyc.city')->select('*')->where('role_id',2)->where('verify_status','verified')->where('banned',0)->where('subadmin_id',$data->id)->orderBy('id', 'desc');
            
    	}
		else
		{
		 $totalUsers = User::with('user_kyc')->where('role_id',2)->get()->where('verify_status','verified')->where('banned',0)->count();
		 $users = User::with('user_kyc','user_kyc.city')->select('*')->where('role_id',2)->where('verify_status','verified')->where('banned',0)->orderBy('id', 'desc');
		}
        //echo '<pre>';print_r($users);die;
		$totalFiltered = $totalUsers;
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value']))
        {
            $users=$users->where(function($query) use ($searchString) {
			   return $query->where('username','LIKE','%'.$searchString.'%')
                    ->orWhere('mobile','LIKE','%'.$searchString.'%')
					->orWhere('email','LIKE','%'.$searchString.'%')
					->orWhere('id',$searchString)
                   ->orWhereHas('user_kyc.city', function ($query) use ($searchString)
                   {
                       $query->whereRaw("name  LIKE '%" . $searchString . "%'");
                   });
			});
			$totalFiltered=User::where('verify_status','verified')->where(function($query) use ($searchString) {
                return $query->where('username','LIKE','%'.$searchString.'%')
                    ->orWhere('mobile','LIKE','%'.$searchString.'%')
					->orWhere('email','LIKE','%'.$searchString.'%')

					->orWhere('id',$searchString)
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
		$totalsubadmin= Admin::where('role',2)->get();
			
        foreach ($users as $item) {
            $i++;
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = $item->id;
            $nestedData[] = $item->username;
            $nestedData[] = $item->user_kyc->city ? $item->user_kyc->city->name :'';
            $nestedData[] = $item->email;
			$nestedData[] = $item->simple_pass;
            $nestedData[] = $item->mobile;
            $date = strtotime($item->created_at);
            $nestedData[] = date('d-m-Y', $date);
			$options="";
			
			foreach($totalsubadmin as $vs)
			{
				if($vs->id==$item->subadmin_id)
				{ 
			      $options.= "<option value=".$vs->id." selected>".$vs->username."</option>";
			    }
				else
				{
					$options.= "<option value=".$vs->id.">".$vs->username."</option>";
				}
			}
             if($item->verify_status=='verified'){ $class="on"; $title="active"; } else { $class="off"; $title="inactive"; }
			 if($item->banned==0){ $lockclass="unlock"; $locktitle="Block"; } else { $lockclass="lock"; $locktitle="UnBlock";}
               $permissionLink = "<select onChange='subadmin(this.value,this.id)' id=".$item->id.">
			                      <option value=''>Subadmin</option>
								  ".$options."
							 </select>";
              $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
              $ViewLink = '<a href="' . URL::to('/') . '/admin/seller/view/'. $item->id .' " title="View"><i class="glyphicon glyphicon-eye-open"></i></a>';
			  $editLink = '<a href="' . URL::to('/') . '/admin/seller/edit-seller/'. $item->id .'?v=1 " title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>';
              $blockLink = '<a href="' . URL::to('/') . '/admin/seller/update-block-status-verified/'.$item->id.'" title="'.$locktitle.'"><i class="fa fa-'.$lockclass.'" aria-hidden="true" ></i></a>';
              $emailLink = '<a href="javascript:void(0)" onclick="send_email(this.id)" id='.$item->email.' title="Send Email"><i class="fa fa-envelope" aria-hidden="true" ></i></a>';
			  $activateLink = '<a href="' . URL::to('/') . '/admin/seller/update-verify-status/'.$item->id.'" title="'.$title.'"><i class="fa fa-toggle-'.$class.'" aria-hidden="true" ></i></a>';
			  $OrderLink = '<a href="' . URL::to('/') . '/admin/sellerorder/verified-seller-order/'.$item->id.'" title="'.$title.'">View Order</a>';
              $whatappLink = '<a href="https://api.whatsapp.com/send?phone=+91'.$item->mobile.'"  id='.$item->id.' title="Chat Now"><i class="fa fa-whatsapp"></i></a>';
              $NoteLink = '<a href="'.URl::to('admin/user-note/'.$item->id).'" title="Create Note"><i class="fa fa-sticky-note"></i></a>';
           
			  $nestedData[] = $ViewLink." | ".$activateLink." | ".$editLink." | ".$deleteLink." | ".$blockLink." | ".$emailLink." | ".$OrderLink." | ".$whatappLink." | ". $NoteLink;
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
	
	public function blocked_seller()
     {
         return view("admin.seller.blocked_seller");
     }
	 
    //get list of record of subadmin...........................................................
    public function getBlockedSellerData(Request $request)
    {
	$data=Session::get('user_sdata');
	$role_id=$data->role;
	
        $requestData = $_REQUEST;
        $columns = array(
            // column index  => database column name
            0 => 'users.id',
            1 => 'users.username',
            2 => 'users.email',
            3 => 'users.mobile',
            4 => 'users.created_at',
        );
		if($data->role==2)
		{ 
	     $totalUsers = User::with('user_kyc')->where('role_id',2)->where('subadmin_id',$data->id)->get()->where('verify_status','verified')->where('banned',1)->count();
         $users = User::with('user_kyc')->select('*')->where('role_id',2)->where('verify_status','verified')->where('subadmin_id',$data->id)->where('banned',1)->orderBy('id', 'desc');
            
    	}
		else
		{
		 $totalUsers = User::with('user_kyc')->where('role_id',2)->get()->where('verify_status','verified')->where('banned',1)->count();
		 $users = User::with('user_kyc')->select('*')->where('role_id',2)->where('verify_status','verified')->where('banned',1)->orderBy('id', 'desc');
		}
		$totalFiltered = $totalUsers;
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value']))
        {
            $users=$users->where(function($query) use ($searchString) {
			   return $query->where('username','LIKE','%'.$searchString.'%')
                    ->orWhere('mobile','LIKE','%'.$searchString.'%')
					->orWhere('email','LIKE','%'.$searchString.'%');
			});
			$totalFiltered=User::where('verify_status','verified')->where(function($query) use ($searchString) {
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
		$totalsubadmin= Admin::where('role',2)->get();
			
        foreach ($users as $item) {
            $i++;
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = $item->id;
            $nestedData[] = $item->username;
            $nestedData[] = $item->email;
			$nestedData[] = $item->simple_pass;
			$nestedData[] = $item->user_kyc->cartlay_commission;
            $nestedData[] = $item->mobile;
            $date = strtotime($item->created_at);
            $nestedData[] = date('d-m-Y', $date);
			$options="";
			
			foreach($totalsubadmin as $vs)
			{
				if($vs->id==$item->subadmin_id)
				{ 
			      $options.= "<option value=".$vs->id." selected>".$vs->username."</option>";
			    }
				else
				{
					$options.= "<option value=".$vs->id.">".$vs->username."</option>";
				}
			}
             if($item->verify_status=='verified'){ $class="on"; $title="active"; } else { $class="off"; $title="inactive"; }
			 if($item->banned==0){ $lockclass="unlock"; $locktitle="Active"; } else { $lockclass="lock"; $locktitle="Inactive";}
               $permissionLink = "<select onChange='subadmin(this.value,this.id)' id=".$item->id.">
			                      <option value=''>Subadmin</option>
								  ".$options."
							 </select>";
              $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
              $ViewLink = '<a href="' . URL::to('/') . '/admin/seller/view/'. $item->id .' " title="View"><i class="glyphicon glyphicon-eye-open"></i></a>';
			  $editLink = '<a href="' . URL::to('/') . '/admin/seller/edit-seller/'. $item->id .' " title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>';
              $blockLink = '<a href="' . URL::to('/') . '/admin/seller/update-block-status-verified/'.$item->id.'" title="'.$locktitle.'"><i class="fa fa-'.$lockclass.'" aria-hidden="true" ></i></a>';
              $emailLink = '<a href="javascript:void(0)" onclick="send_email(this.id)" id='.$item->email.' title="Send Email"><i class="fa fa-envelope" aria-hidden="true" ></i></a>';
			  $activateLink = '<a href="' . URL::to('/') . '/admin/seller/update-verify-status/'.$item->id.'" title="'.$title.'"><i class="fa fa-toggle-'.$class.'" aria-hidden="true" ></i></a>';
			  $OrderLink = '<a href="' . URL::to('/') . '/admin/sellerorder/verified-seller-order/'.$item->id.'" title="'.$title.'">View Order</a>';
              $whatappLink = '<a href="https://api.whatsapp.com/send?phone=+91'.$item->mobile.'"  id='.$item->id.' title="Chat Now"><i class="fa fa-whatsapp"></i></a>';
              $NoteLink = '<a href="'.URl::to('admin/user-note/'.$item->id).'" title="Create Note"><i class="fa fa-sticky-note"></i></a>';
           
			  $nestedData[] = $ViewLink." | ".$activateLink." | ".$editLink." | ".$deleteLink." | ".$blockLink." | ".$emailLink." | ".$OrderLink." | ".$whatappLink." | ". $NoteLink;
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
		        $userData=Session::get('user_sdata');
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
        return view('admin.seller.view')->with('user', $user)->with('id', $id);
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
        return redirect('/admin/seller/seller-list');
    }
	
	function update_verify_status($id=null)
    {
		$status=User::with('user_kyc')->where('id',$id)->first();
		$agreement=Agreement::first();
        if($status->verify_status=="kyc_completed")
		{
			    $mmsg="Hi ".$status->user_kyc->f_name.", \n  Welcome to Grocito\n";
                $mmsg.="Your Account has been varified successfully. Please login using below mobile and password \n";
                $mmsg.="Your mobile number is: ".$status->mobile."\n\n";
                $mmsg.="Your Password is : ".$status->simple_pass."\n\n";
                $mmsg.="Click on Below link to Login As seller \n";
                $mmsg.="http://grocito.com/seller/login \n";
                $mmsg.="\n\n Thanks Grocito";
		        Helper::send_msg($status->mobile,$mmsg);
				
				$msg="Hi ".$status->user_kyc->f_name.", <br><br>   Welcome to Grocito<br><br>";
                $msg.="Your Account has been varified successfully. Please login using below mobile and password<br>";
                $msg.="Your mobile number is: ".$status->mobile."<br><br>";
                $msg.="Your Password is : ".$status->simple_pass."<br><br>";
                $msg.="<a href='http://grocito.com/seller/login'>Click</a> on Below link to login <br>";
                $msg.="<a href='http://grocito.com/seller/login'>Login As seller</a><br>";
                $msg.="<h2>Your Agreement</h2>";
				 $tillDate = date('d-m-Y', strtotime("+3 months", strtotime($status->user_kyc->created_at)));
			     $agreement_data= str_replace("@@current_date@@",date('d-m-Y', strtotime($status->user_kyc->created_at)),$agreement->description);
			     $agreement_data= str_replace("@@seller_name@@",$status->user_kyc->f_name." ".$status->user_kyc->l_name,$agreement_data);
			     $agreement_data= str_replace("@@seller_address@@",$status->user_kyc->address_1,$agreement_data);
			     $agreement_data= str_replace("@@valid_till@@",$tillDate,$agreement_data);
				 
			   
                $msg.="<b>".$agreement_data."</b>";
				
                $msg.="<br> <br>  Thanks Grocito";
				
                $emailData = array(
                    'to'        => array($status->email),    //array(strtolower($userData['email']))
                    'from'      => 'support@grocito.com',
                    'subject'   => 'Account Varified',
                    'view'      => 'email.verification-email',
                    'content'=>$msg
					
                );
              Mail::send($emailData['view'], $emailData, function ($message) use ($emailData) {
                    $message
                        ->to($emailData['to'])
                        ->from($emailData['from'])
                        ->subject($emailData['subject']);

                });
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
	
	
	
	public function edit($id,Request $request)
    {
        $verifyStatus = $request->input('v')?$request->input('v'):0;

        //$Package = Package::first();
		$country_list= Country::get();
        $user = UserKyc::with('user')->where('user_id',$id)->first();
        $deliveryPincode = Pincode::where('city_id',$user->city_id)->get();
        return view('admin.seller.edit')->with(['user'=>$user,'country_list'=>$country_list,'deliveryPincode'=>$deliveryPincode,'verifyStatus'=>$verifyStatus]);
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
	    'role_id'=>2,
	    'username'=>$request->input('username'),
	    'delivery_pincode'=>$request->input('delivery_pincode'),
		);
		
        $rules = array(
           // 'username'  =>'required|unique:users,username,'.$id,
           // 'email'     =>'required|unique:users,email,'.$id,
	        'mobile'    =>'required|unique:users,mobile,'.$id,
	        //'f_name'    =>'required',
	       // 'l_name'    =>'required',
	        'delivery_pincode'    =>'required',

        );
        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
            return redirect('admin/seller/edit-seller/'.$id)->withInput()->withErrors($validator);
        }else{
			 $kycData=$request->all();
			 if($request->hasFile('pan_image'))
				{
                $image = $request->file('pan_image');
                $path_original = public_path() . '/admin/uploads/seller/';
                $file = $request->pan_image;
                $photo_name = time() . '-' . $file->getClientOriginalName();
                $file->move($path_original, $photo_name);
                $kycData['pan_image'] = $photo_name;
                }
				
				if($request->hasFile('seller_image'))
				{
                $image = $request->file('seller_image');
                $path_original = public_path() . '/admin/uploads/seller/';
                $file = $request->seller_image;
                $photo_name = time() . '-' . $file->getClientOriginalName();
                $file->move($path_original, $photo_name);
                $kycData['seller_image'] = $photo_name;
                }
				
				if($request->hasFile('cancel_cheque'))
				{
                $image = $request->file('cancel_cheque');
                $path_original = public_path() . '/admin/uploads/seller/';
                $file = $request->cancel_cheque;
                $photo_name = time() . '-' . $file->getClientOriginalName();
                $file->move($path_original, $photo_name);
                $kycData['cancel_cheque'] = $photo_name;
				
                }
				
				
				
				if($request->hasFile('cin_image'))
				{
                $image = $request->file('cin_image');
                $path_original = public_path() . '/admin/uploads/seller/';
                $file = $request->cin_image;
                $photo_name = time() . '-' . $file->getClientOriginalName();
                $file->move($path_original, $photo_name);
                $kycData['cin_image'] = $photo_name;
                }
				
				if($request->hasFile('profile_image'))
				{
                $image = $request->file('profile_image');
                $path_original = public_path() . '/admin/uploads/seller/';
                $file = $request->profile_image;
                $photo_name = time() . '-' . $file->getClientOriginalName();
                $file->move($path_original, $photo_name);
                $kycData['profile_image'] = $photo_name;
                }
            //If kyc, update status kyc_completed.
            if ($request->has('account_number') or $request->has('ifsc_code') or  $request->has('account_holder_name')) {
                if($request->verfiy_status ==1){
                    $userData['verify_status'] = 'verified';
                }else{

                    $userData['verify_status'] = 'kyc_completed';
                }
            }
			 $update_data = User::find($user->id)->fill($userData);
             $update_data->update();
			 $pincode = $request->input('delivery_pincode');
             $pinArr = implode(',',$pincode);
             $kycData['delivery_pincode'] = $pinArr;
			  $userKyc=UserKyc::where('user_id', '=',$user->id)->first();
			  $userKyc->update($kycData);
			 }
        // redirect
        Session::flash('success_message', 'Your user has been added successfully');
        //return redirect('/admin/seller/seller-list');
       return redirect(url()->previous());
    }
	
	public function add()
    {
        $country_list= Country::get();
      return view('admin.seller.add')->with('country_list',$country_list);
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
    public function getPincode(Request $request)
    {
        $id=$request->input('id');
        $pincodeList= Pincode::where('city_id',$id)->get();
        return view('admin.seller.deliver_pincode_ajax')->with('pincodeList',$pincodeList);
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
    public function store(Request $request)
    {
        $userData = array(
		'mobile'=>$request->input('mobile'),
	    'email'=>strtolower($request->input('email')),
	    'role_id'=>2,
	    'username'=>$request->input('username'),
	    'delivery_pincode'=>$request->input('delivery_pincode'),
		);
		
        $rules = array(
            'username'  =>'required|unique:users,username',
            'email'     =>'required|email|unique:users',
	        'mobile'    =>'required|unique:users,mobile',
	        'f_name'    =>'required',
	        'l_name'    =>'required',
	        'delivery_pincode'    =>'required',

        );
        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
            return redirect('admin/seller/create-seller')->withInput()->withErrors($validator);
        }else{
			    $password=str_random(3).rand(123,456);
			    $userData['password'] =    Hash::make($password);
			    $userData['simple_pass'] =    $password;
			 $kycData=$request->all();
			 if($request->hasFile('pan_image'))
				{
                $image = $request->file('pan_image');
                $path_original = public_path() . '/admin/uploads/seller/';
                $file = $request->pan_image;
                $photo_name = time() . '-' . $file->getClientOriginalName();
                $file->move($path_original, $photo_name);
                $kycData['pan_image'] = $photo_name;
                }
				
				if($request->hasFile('seller_image'))
				{
                $image = $request->file('seller_image');
                $path_original = public_path() . '/admin/uploads/seller/';
                $file = $request->seller_image;
                $photo_name = time() . '-' . $file->getClientOriginalName();
                $file->move($path_original, $photo_name);
                $kycData['seller_image'] = $photo_name;
                }
				
				if($request->hasFile('cancel_cheque'))
				{
                $image = $request->file('cancel_cheque');
                $path_original = public_path() . '/admin/uploads/seller/';
                $file = $request->cancel_cheque;
                $photo_name = time() . '-' . $file->getClientOriginalName();
                $file->move($path_original, $photo_name);
                $kycData['cancel_cheque'] = $photo_name;
                }
				
				if($request->hasFile('cin_image'))
				{
                $image = $request->file('cin_image');
                $path_original = public_path() . '/admin/uploads/seller/';
                $file = $request->cin_image;
                $photo_name = time() . '-' . $file->getClientOriginalName();
                $file->move($path_original, $photo_name);
                $kycData['cin_image'] = $photo_name;
                }
				
				if($request->hasFile('profile_image'))
				{
                $image = $request->file('profile_image');
                $path_original = public_path() . '/admin/uploads/seller/';
                $file = $request->profile_image;
                $photo_name = time() . '-' . $file->getClientOriginalName();
                $file->move($path_original, $photo_name);
                $kycData['profile_image'] = $photo_name;
                }
            //If kyc, update status kyc_completed.
            if ($request->has('account_number') or $request->has('ifsc_code') or  $request->has('account_holder_name') ) {
                $userData['verify_status'] = 'kyc_completed';
            }
			 $user = new User($userData);
             $user->save();
			 $user_id= $user->id;
			 $kycData['user_id']= $user_id;
             $pincode =  $request->input('delivery_pincode');
             $deliveryPincode = implode(',',$pincode);
			 $kycData['delivery_pincode']= $deliveryPincode;
			 $userKyc = new UserKyc($kycData);
             $userKyc->save();
			 }
           // redirect
		        $mmsg="Hi ".$request->input('f_name').", \n  Welcome to Grocito\n";
                $mmsg.="Your Account has been created successfully. Please login using below email and password \n";
                $mmsg.="Your email address is: ".strtolower($request->input('email'))."\n\n";
                $mmsg.="Your Password is : ".$password."\n\n";
                $mmsg.="Click on Below link to Login As seller \n";
                $mmsg.="http://grocito.com/seller/login\n";
                $mmsg.="\n\n Thanks Grocito";
		         Helper::send_msg($userData['mobile'],$mmsg);
				 
		        $msg="Hi ".$request->input('f_name').", <br><br>   Welcome to Grocito<br><br>";
                $msg.="Your Account has been created successfully. Please login using below email and password<br>";
                $msg.="Your email address is: ".$request->input('email')."<br><br>";
                $msg.="Your Password is : ".$password."<br><br>";
                $msg.="<a href='http://grocito.com/seller/login'>Click</a> on Below link to login <br>";
                $msg.="<a href='http://grocito.com/seller/login'>Login As seller</a><br>";
                $msg.="<br> <br>  Thanks Cartlay";
		   $emailData = array(
                    'to'        => array($request->input('email')),
                    'from'      => 'support@cartlay.com',
                    'subject'   => 'Account Created',
                    'view'      => 'email.verification-email',
                    'content'=>$msg
                );
                Mail::send($emailData['view'], $emailData, function ($message) use ($emailData) {
                    $message
                        ->to($emailData['to'])
                        ->from($emailData['from'])
                        ->subject($emailData['subject']);

                });
        Session::flash('success_message', 'Your user has been added successfully');
        return redirect('/admin/seller/seller-list');
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