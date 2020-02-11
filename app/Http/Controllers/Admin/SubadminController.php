<?php
/**
 * Created by PhpStorm.
 * User: wingstud
 * Date: 10/8/17
 * Time: 11:35 AM
 */
namespace App\Http\Controllers\Admin; //admin add
use App\Http\Requests;
use App\Http\Controllers\Controller;   // using controller class
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\User;
use App\Admin;
use App\SubadminAccess;
use DB;
use URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
class SubadminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }

    //get list of record of subadmin...........................................................
    public function getSubadminData(Request $request)
    {
        $requestData = $request->toArray();
        $columns = array(
            // column index  => database column name
            0 => 'admins.id',
            1 => 'admin.username',
            2 => 'admins.email',
            3 => 'admins.created_at',
        );

        $totalItems = Admin::where('role',2)->get()->count();
        $totalFiltered = $totalItems;
        $items = Admin::where('role',2)->where('id','!=',0);

        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value']))
        {
            $items->whereRaw("username LIKE '%" . $searchString . "%'")->orWhereRaw("email LIKE '%" . $searchString . "%'");
            $totalFiltered = Admin::whereRaw("username LIKE '%" . $searchString . "%'")->orWhereRaw("email LIKE '%" . $searchString . "%'")->get()->count();
        }

        $orderColumn = $columns[$requestData['order'][0]['column']];
        $orderColumnDir = $requestData['order'][0]['dir'];
        $limit = $requestData['length'];
        $offset = $requestData['start'];

        $items=$items->offset($offset)->limit($limit)->orderBy('created_at','desc')->get();
        //dd($users->toSql(), $users->getBindings());
        $data = array();
        $i = $offset;
        foreach ($items as $item) {
            $i++;
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = htmlspecialchars($item->username);
            $nestedData[] = $item->email;
            $nestedData[] = "<b id=".'password_'.$item->id.">".$item->simple_pass."</b>";
            $nestedData[] = date('d-m-Y',strtotime($item->created_at));

            if($item->active==1){ $class="on"; $title="active"; } else { $class="off"; $title="inactive"; }
            $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
            $activateLink = '<a href="' . URL::to('/') . '/admin/subadmin/edit/'. $item->id .' " title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>';
            $editLink = '<a href="' . URL::to('/') . '/admin/subadmin/update-status/'.$item->id.'" title="'.$title.'"><i class="fa fa-toggle-'.$class.'" aria-hidden="true" ></i></a>';
            $viewLink = '<a href="' . URL::to('/') . '/admin/subadmin/view/'.$item->id.'" title="View Permission"><i class="fa fa-eye" aria-hidden="true" ></i></a>';
            $passLink = '<a href="javascript:void(0)" onclick="change_password(this.id)" id='.$item->id.' title="Change Password"><i class="fa fa-key"></i></a>';
             $nestedData[] =  $viewLink." | ".$editLink ." | ". $deleteLink." | ".$activateLink." | ".$passLink;
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


    public function index()
    {
        return view("admin.sub_admin.index");
    }

    public function add()
    {
       return view('admin.sub_admin.add');
    }

  ///store subadmin...........................................................
    public function store(Request $request)
    {
        $subadminData = array(
            'username'     => $request->input( 'name'),
            'email'    => $request->input( 'email'),
            'role'   => 2,
            'active'   => 1,
            'password'     =>$request->input('password'),
            'password_confirmation' =>$request->input( 'password_confirmation'),
        );
        $rules = array(
            'username'     =>   'required|max:20|unique:admins,username',
            'email'    =>   'required|email|unique:users,email',
            'password'  =>  'required|min:6|confirmed',
            'password_confirmation'=>'required|between:6,20',
                )   ;
        $validator = Validator::make($subadminData,$rules);
        if ($validator->fails()) {
            return redirect('admin/subadmin/add-subadmin')->withInput()->withErrors($validator);
        }else{
				$subadminData['password'] =    Hash::make($subadminData['password']);
				$subadminData['simple_pass'] =   $request->input('password');
				unset($subadminData['password_confirmation']);
				$user = new Admin($subadminData);
				$user->save();
				//$permission['user_id']= $user->id;
				 $permission=$request->input('permission');
		         $action=$request->input('action');
				 foreach($permission as $vs)
				 {
					 $data['access_permission']=$vs;
					 $data['action']=implode(",",$action[$vs]);
					 $data['user_id']= $user->id;
					 $obj= new SubadminAccess($data);
				     $obj->save();
				 }
            }
            // redirect
            Session::flash('success_message', 'Your Account has been added successfully');
            return redirect('/admin/subadmin/view-all-subadmin');
        }
    //end.....................

    public function edit($id)
    {
        $users = Admin::with('subadmin_access')->findOrFail($id);
	   return view('admin.sub_admin.edit')->with(['users'=> $users]);
    }

   public function update(Request $request, $id)
    {
        // validate
        $user = Admin::findOrFail($id);
        $validator = Validator::make($request->all(),
            [
                'email' => 'required|max:100|unique:users,email,'.$id.'',
                'name' => 'required|max:50'
            ], [
                'name.required' => 'This field is required.',
                'email.unique' => 'Email already exists.',
                'email.max' => 'Email cannot be longer than 100 characters.',
                'name.max' => 'Name can not be longer than 50 characters',
            ]);

        if ($validator->fails())
        {
            return redirect('admin/subadmin/edit/'.$id)->withInput()->withErrors($validator);
        }
        else
        {
            $data=Input::all();
            $user->fill($data)->save();
			DB::table('subadmin_access')->where('user_id',$request->input('user_id'))->delete();
				 $permission=$request->input('permission');
		         $action=$request->input('action');
				 foreach($permission as $vs)
				 {
					 $data['access_permission']=$vs;
					 $data['action']=implode(",",$action[$vs]);
					 $data['user_id']= $request->input('user_id');
					 $obj= new SubadminAccess($data);
				     $obj->save();
				 }
            Session::flash('success_message', 'Successfully updated sub-admin!');
            return redirect('admin/subadmin/view-all-subadmin');
        }
    }


    public function view($id)
    {
        $users = Admin::with('subadmin_access')->findOrFail($id);
        $permission = SubadminAccess::where('user_id',$id)->get();
        return view('admin.sub_admin.view')->with(['users'=> $users,'permission'=>$permission]);

    }

    ///delete subadmin...................
    public function delete()
    {
        $user = Admin::with('subadmin_access')->findOrFail($_POST['id']);
        if(!empty($user->delete()))
        {
            Session::flash('success_message', 'Subadmin has been deleted successfully!');
        }
        else {
            Session::flash('error_message', 'Unable to delete the subadmin');
        }
   }

    ///change subadmin status...................
    function update_status($id=null)
    {
             $response=DB::statement("UPDATE admins SET active =(CASE WHEN (active = 1) THEN '0' ELSE '1' END) where id = $id");
             if($response) {
                 Session::flash('success_message', 'status has been updated successfully!');
                 }
                else {
                    Session::flash('error_message', 'Unable to update status');
                  }
              return redirect('/admin/subadmin/view-all-subadmin');
    }
	
	function change_password(Request $request)
	{
		 $user_id= $request->input('user_id');
		 $data['simple_pass']= $request->input('new_password');
		 $data['password'] =    Hash::make($data['simple_pass']);		
		 $user = Admin::findOrFail($user_id);
		 if($user->fill($data)->save())
				{
				    echo json_encode(array('status'=>true,'message'=>'Password has been changed successfully'));
				}
				else
				{
					echo json_encode(array('status'=>false,'message'=>'Please try again'));
				}
	}
}