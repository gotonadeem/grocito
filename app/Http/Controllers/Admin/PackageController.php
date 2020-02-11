<?php
namespace App\Http\Controllers\Admin; //admin add
use App\Http\Requests;
use App\Http\Controllers\Controller;   // using controller class
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\GeneralSetting;
use App\PaymentGatwaySetting;
use App\Package;
use DB;
use URL;
use File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class PackageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }
    public function index()
    {
        return view('admin.packages.index');
    }
 //get list of record of subadmin...........................................................
    public function getPackageData(Request $request)
    {
        $requestData = $request->toArray();
        $columns = array(
            // column index  => database column name
            0 => 'packages.id',
            1 => 'packages.package_name',
            2 => 'packages.invested_amount_from',
            3 => 'packages.invested_amount_to',
            4 => 'packages.daily_roi',
            5 => 'packages.referral_income',
            6 => 'packages.reword_bonus',
            7 => 'packages.days_on_roi',

        );

        $totalItems = Package::get()->count();
        $totalFiltered = $totalItems;
        $items = Package::where('id','!=',0);

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
            $amount='$'.$item->invested_amount_from.' - $'.$item->invested_amount_to;
            $nestedData[] = $i;
            $nestedData[] = $item->package_name;
            $nestedData[] = $amount;
            $nestedData[] = $item->daily_roi.'%';
            $nestedData[] = $item->referral_income.'%';
            $nestedData[] = $item->reword_bonus.'%';
            $nestedData[] = $item->days_on_roi.' Days';


            //$nestedData[] = $item->created_at->format('F d, Y');
           // if($item->status==1){ $class="on"; $title="active"; } else { $class="off"; $title="inactive"; }
           // $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
           $editLink = '<a href="' . URL::to('/') . '/admin/packages/edit/'. $item->id .' " title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>';
            //$activateLink = '<a href="' . URL::to('/') . '/admin/packages/update-status/'.$item->id.'" title="'.$title.'"><i class="fa fa-toggle-'.$class.'" aria-hidden="true" ></i></a>';
           // $nestedData[] = $editLink ." | ". $deleteLink." | ".$activateLink;
            $nestedData[] = $editLink;
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


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */

    public function add_package()
    {

      return view('admin.packages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {

        $packageData = array(
            'package_name'     => $request->input( 'package_name'),
            'price'    => $request->input( 'price'),
            'description'     =>$request->input( 'description'),
        );
        $rules = array(
            'package_name'     =>   'required',
            'price'    =>   'required|numeric',
            'description'  =>  'required',
        )   ;
        $validator = Validator::make($packageData,$rules);
        if ($validator->fails()) {
            return redirect('admin/packages/add-package')->withInput()->withErrors($validator);
        }else{
            $package = new Package($request->all());
            $package->save();
        }
        // redirect
        Session::flash('success_message', 'Your Package has been added successfully');
        return redirect('/admin/packages/package-list');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {

        // get the testimonial
        $plans = $this->model->findOrFail($id);


        // show the view and pass the nerd to it
        return view('admin.plans.show')
            ->with('plans', $plans);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        //$Package = Package::first();
        $Package = Package::find($id);
        return view('admin.packages.edit')->with(['packages'=>$Package]);
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
        $package = Package::find($id);
        $validator = Validator::make($request->all(),
            [
                'package_name' => 'required',
                'invested_amount_from' => 'required',
                'invested_amount_to' => 'required',
                'daily_roi' => 'required',
                'referral_income' => 'required',
                'reword_bonus' => 'required',
                'days_on_roi' => 'required'

            ], [
                'package_name.required' => 'This field is required.',
                'invested_amount_from.required' => 'This field is required.',
                'invested_amount_to.required' => 'This field is required.',
                'daily_roi.required' => 'This field is required.',
                'referral_income.required' => 'This field is required.',
                'reword_bonus.required' => 'This field is required.',
                'days_on_roi.required' => 'This field is required.',

            ]);

        if ($validator->fails())
        {
            return redirect('admin/packages/edit/'.$id)->withInput()->withErrors($validator);
        }
        else
        {
            $data=Input::all();
            $package->fill($data)->save();
            // redirect
            Session::flash('success_message', 'Package Successfully updated');
            return redirect('admin/packages/package-list');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        if($plans = $this->model->find($id)){
            $plans->delete();
            $data =  response('deleted',200);
        }else{
            $data = response('some_thing_is_wrong',500);
        }
        return $data;

    }

    /**
     * @return mixed
     */

    public function anyData(Request $request)
    {
        //$data = $this->model->select()->orderBy('created_at', 'desc');
        $data = $this->model->with('city')->select();
        $start = $request->get('start');
        return Datatables::of($data)
            ->addColumn('action', function ($data) {
                $activeIcon = ($data->status == 1) ? 'fa fa-toggle-on' : 'fa fa-toggle-off';
                $activeLink = '<a href="javascript:void(0);" onclick="statusChange(' . $data->id . ',this)" title="Update Status"><span class="' . $activeIcon . '""/></span></a>';
                return '<a href="javascript:void(0);" onclick="deleteRow('. $data->id .',this)" title="Delete"><i class="fa fa-trash-o"></i></a> | <a href="' . route('admin.plans.edit',$data->id) .'" title="Edit"><i class="fa fa-pencil-square-o"></i></a> | <a href="' . route('admin.plans.show',$data->id) .'" title="View"><i class="fa fa-eye"></i></a>|'.$activeLink ;
            })
            ->editColumn('category', function ($data) {
                if($data->category==1){return 'Top Ranking';}else{return 'Virtual Tour';}

            })
            ->editColumn('startdate', function ($data) {
                return date('m-d-Y', strtotime($data->startdate));
            })
            ->editColumn('enddate', function ($data) {
                return date('m-d-Y', strtotime($data->enddate));
            })
            ->editColumn('created_at', function ($data) {
                return date('m-d-Y', strtotime($data->created_at));
            })
            ->editColumn('id', function($data) use (&$start) {
                $start=  $start+1;
                return $start;
            })

            ->make(true);
    }
 

}
