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
use File;
use Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }

    public function index()
    {  
        return view("admin.user.index");
    }

    //get list of record of subadmin...........................................................
    public function getUserData(Request $request)
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
        $totalUsers = User::get()->where('role_id',2)->count();
        $totalFiltered = $totalUsers;
        $users = User::with('user_kyc','user_kyc.city')->where('role_id',2)->orderBy('users.id', 'desc');
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
            $totalFiltered = User::where('username','LIKE','%'.$searchString.'%')->orWhere('email','LIKE','%'.$searchString.'%')->orWhere('mobile','LIKE','%'.$searchString.'%')
                ->get()->count();
        }

        $orderColumn = $columns[$requestData['order'][0]['column']];
        $orderColumnDir = $requestData['order'][0]['dir'];
        $limit = $requestData['length'];
        $offset = $requestData['start'];
        $users = $users->offset($offset)->limit($limit)->orderBy($orderColumn, $orderColumnDir)->get();
        //$users = $users->Join('user_kyc', 'user_kyc.user_id', '=', 'users.id')->offset($offset)->limit($limit)->orderBy($orderColumn, $orderColumnDir)->get();
        $data = array();
        $i = $offset;
        foreach ($users as $item) {
            $i++;
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = $item->username;
            $nestedData[] = $item->email;
            $nestedData[] = $item->mobile;
            $nestedData[] = $item->user_kyc->city ? $item->user_kyc->city->name :'';
            $date = strtotime($item->created_at);
            $nestedData[] = date('d-m-Y', $date); 
             if($item->banned==0){ $class="on"; $title="active"; } else { $class="off"; $title="inactive"; }
              $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
              $ViewLink = '<a href="' . URL::to('/') . '/admin/user/view/'. $item->id .' " title="View"><i class="glyphicon glyphicon-eye-open"></i></a>';
			  //$editLink = '<a href="' . URL::to('/') . '/admin/user/edit/'. $item->id .' " title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>';
             // $activateLink = '<a href="' . URL::to('/') . '/admin/user/update-status/'.$item->id.'" title="'.$title.'"><i class="fa fa-toggle-'.$class.'" aria-hidden="true" ></i></a>';
              $nestedData[] = $ViewLink." | ".$deleteLink;
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
      
        $user = UserKyc::with('user','country','state','city')->where('user_id', $id)->first();
        return view('admin.user.view')->with('user', $user);
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
        return redirect('/admin/user/user-list');
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
		
	public function edit($id)
    { 
        //$Package = Package::first();
        $user = User::find($id);
        return view('admin.user.edit')->with(['user'=>$user]);
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
        // validate
        $user = User::find($id);
        $validator = Validator::make($request->all(),
            [
                'username' => 'required',
				'email' => 'required',
				'mobile' => 'required',

            ], [
                'username.required' => 'This field is required.',
				'email.required' => 'This field is required.',
				'mobile.required' => 'This field is required.',
            ]);

        if ($validator->fails())
        {
            return redirect('admin/user/edit/'.$id)->withInput()->withErrors($validator);
        }
        else
        {
            $obj=new User();
            $obj->title=$request->input('title');
            $setting = User::first();

            if($user) {
                $data =$request->all();
                if ($request->hasFile('images'))
                {
                    $path_original=public_path() . '/admin/uploads/user_image';
                    $file = $request->images;

                    $photo_name = time() . '-' . $file->getClientOriginalName();
                    $file->move($path_original, $photo_name);
                    $data['old_images'] = $photo_name;
                    if ($request->old_img != '') {
                        try {
                            unlink($path_original . $request->old_img);

                        } catch (\Exception $e) {
                        }
                    }
                }
                $update_data = User::find($user->id)->fill($data);
                if($request->images){$update_data->images=$photo_name;}
                $update_data->update();
            }
            else
            {
                if ($file = $request->hasFile('images')) {
                    $file = $request->file('images');
                    $fileName = $file->getClientOriginalName();
                    $destinationPath = public_path() . '/admin/uploads/slider_image';
                    $file->move($destinationPath, $fileName);
                    $obj->images = $fileName;
                }
                $obj->save();
            }


            // redirect
            Session::flash('success_message', 'User Successfully updated');
            return redirect('admin/user/user-list');
        }

    }
	
	public function add()
    {
      return view('admin.user.add');
    }
    public function store(Request $request)
    {
        $sliderData = array(
            'username'     =>$request->input( 'username'),
            'email'    =>$request->input( 'email'),
			'password'     =>$request->input( 'password'),
            'mobile'    =>$request->input( 'mobile'),

        );
        $rules = array(
            'username'=>'required',
            'email'=>'required',
			'password'=>'required',
            'mobile'=>'required',

        )   ;
        $validator = Validator::make($sliderData,$rules);
        if ($validator->fails()) {
            return redirect('admin/user/add-user')->withInput()->withErrors($validator);
        }else{
            $user = new User($request->all());
            }
            $user->save();

        // redirect
        Session::flash('success_message', 'Your user has been added successfully');
        return redirect('/admin/user/user-list');
    }
}

?>