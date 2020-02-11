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
use App\Faq;
use DB;
use URL;
use File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class FaqController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }
    public function index()
    {
        return view('admin.faq.index');
    }
 //get list of record of subadmin...........................................................
    public function getFaqData(Request $request)
    {
        $requestData = $request->toArray();
        $columns = array(
            // column index  => database column name
            0 => 'faq.id',
            1 => 'faq.title',
            2 => 'faq.description',
        );

        $totalItems = Faq::get()->count();
        $totalFiltered = $totalItems;
        $items = Faq::where('id','!=',0);

        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value']))
        {
            $items->whereRaw("title LIKE '%" . $searchString . "%'");
        }

        $orderColumn = $columns[$requestData['order'][0]['column']];
        $orderColumnDir = $requestData['order'][0]['dir'];
        $limit = $requestData['length'];
        $offset = $requestData['start'];

        $items=$items->offset($offset)->limit($limit)->orderBy($orderColumn, $orderColumnDir)->get();
        $data = array();
        $i = $offset;
        foreach ($items as $item) {
            $i++;
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = $item->title;
            $nestedData[] = substr($item->description, 0, 80);
            $nestedData[] = $item->created_at->format('F d, Y');
            if($item->banned==0){ $class="on"; $title="active"; } else { $class="off"; $title="inactive"; }
            $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
            $editLink = '<a href="' . URL::to('/') . '/admin/faq/faq-edit/'. $item->id .' " title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>';
            $activateLink = '<a href="' . URL::to('/') . '/admin/faq/update-status/'.$item->id.'" title="'.$title.'"><i class="fa fa-toggle-'.$class.'" aria-hidden="true" ></i></a>';
            $nestedData[] = $editLink ." | ". $deleteLink." | ".$activateLink;
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

    public function add_faq()
    {

      return view('admin.faq.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {

        $packageData = array(
            'title'     => $request->input( 'title'),
            'description'    => $request->input( 'description'),
        );
        $rules = array(
            'title'     =>   'required',
            'description'    =>   'required',
        )   ;
        $validator = Validator::make($packageData,$rules);
        if ($validator->fails()) {
            return redirect('admin/faq/add-faq')->withInput()->withErrors($validator);
        }else{
            $faq = new Faq($request->all());
            $faq->save();
        }
        // redirect
        Session::flash('success_message', 'Your Faq has been added successfully');
        return redirect('/admin/faq/faq-list');
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
    public function edit_faq($id)
    {
        //$Package = Package::first();
        $faq = Faq::find($id);
        return view('admin.faq.edit')->with(['faq'=>$faq]);
    }

    /**
    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function updateFaq($id, Request $request)
    {
        // validate
        $faq = Faq::find($id);
        $validator = Validator::make($request->all(),
            [
                'title' => 'required',
                'description' => 'required'


            ], [
                'title.required' => 'This field is required.',
                'description.required' => 'This field is required.',


            ]);

        if ($validator->fails())
        {
            return redirect('admin/faq/faq-edit/'.$id)->withInput()->withErrors($validator);
        }
        else
        {
            $data=Input::all();
            $faq->fill($data)->save();
            // redirect
            Session::flash('success_message', 'Faq Successfully updated');
            return redirect('admin/faq/faq-list');
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
    public function delete()
    {
        $faq = Faq::findOrFail($_POST['id']);
        if(!empty($faq->delete()))
        {
            Session::flash('success_message', 'Faq has been deleted successfully!');
        }
        else {
            Session::flash('error_message', 'Unable to delete the Faq');
        }
    }
    function update_status($id=null)
    {
        $response=DB::statement("UPDATE faq SET banned =(CASE WHEN (banned = 1) THEN '0' ELSE '1' END) where id = $id");
        if($response) {
            Session::flash('success_message', 'status has been updated successfully!');
        }
        else {
            Session::flash('error_message', 'Unable to update status');
        }
        return redirect('/admin/faq/faq-list');
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
