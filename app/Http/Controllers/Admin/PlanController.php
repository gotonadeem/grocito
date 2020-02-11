<?php
namespace App\Http\Controllers\Admin; //admin add
use App\Http\Requests;
use App\Http\Controllers\Controller;   // using controller class
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\SponsorPlan;
use DB;
use URL;
use Excel;
use File;
use Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
class PlanController  extends Controller
{

    public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }

    public function index()
    {
        return view("admin.plan.index");
    }

    //get list of record of subadmin...........................................................
    public function getPlanData(Request $request)
    {
        $requestData = $_REQUEST;
        $columns = array(
            // column index  => database column name
            0 => 'sponsor_plans.id',
            1 => 'sponsor_plans.plan_details',
            2 => 'sponsor_plans.price',
            3 => 'sponsor_plans.created_at',

        );
        $totalUsers = SponsorPlan::get()->count();
        $totalFiltered = $totalUsers;
        $users = SponsorPlan::orderBy('id', 'asc');
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value'])) {
           
		    $users->where('heading', 'LIKE', '%' . $searchString . '%');
            $totalFiltered = Notice::where('heading', 'LIKE', '%' . $searchString . '%')
                ->get()->count();
        }
        $orderColumn = $columns[$requestData['order'][0]['column']];
        $orderColumnDir = $requestData['order'][0]['dir'];
        $limit = $requestData['length'];
        $offset = $requestData['start'];
        $users = $users->offset($offset)->limit($limit)->orderBy($orderColumn, $orderColumnDir)->get();
        $data = array();
        $i = $offset;
        foreach ($users as $item) {
            $i++;
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = $item->plan_details;
            $nestedData[] = $item->price;
            $date = strtotime($item->created_at);
            $nestedData[] = date('d-m-Y', $date);
            $editLink = '<a href="' . URL::to('/') . '/admin/plan/edit/' . $item->id . ' " title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>';
            //$viewLink = '<a href="' . URL::to('/') . '/admin/notice/view/' . $item->id . ' " title="View"><i class="glyphicon glyphicon-eye-open"></i></a>';
            $nestedData[] = $editLink;
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

   
    public function edit($id)
    {
        $plan = SponsorPlan::findOrFail($id);
        return view('admin.plan.edit')->with(['plan'=>$plan]);
    }
	 public function update($id, Request $request)
    {
        // validate
        $package = SponsorPlan::find($id);
        $validator = Validator::make($request->all(),
            [
                'price' => 'required',
            ], [
                'price' => 'Price is required.',
            ]);

        if ($validator->fails())
        {
            return redirect('admin/plan/edit/'.$id)->withInput()->withErrors($validator);
        }
        else
        {
            $data=Input::all();
            $package->fill($data)->save();
            // redirect
            Session::flash('success_message', 'Plan Successfully updated');
            return redirect('admin/plan/plan-list');
        }

    }
	
	function update_status($id=null)
    {
        $response=DB::statement("UPDATE notices SET status =(CASE WHEN (status = 1) THEN '0' ELSE '1' END) where id = $id");
        if($response) {
            Session::flash('success_message', 'status has been updated successfully!');
        }
        else {
            Session::flash('error_message', 'Unable to update status');
        }
        return redirect('/admin/notice/notice-list');
    }
	///delete user...................
    public function delete()
    {
        $notice = Notice::findOrFail($_POST['id']);
        if(!empty($notice->delete()))
        {
            Session::flash('success_message', 'Notice has been deleted successfully!');
        }
        else {
            Session::flash('error_message', 'Unable to delete the notice');
        }
    }
	
}