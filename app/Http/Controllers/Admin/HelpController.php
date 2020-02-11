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
use App\MustSee;
use DB;
use URL;
use File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class HelpController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }
    public function index()
    {
        return view('admin.must_see.index');
    }
 //get list of record of subadmin...........................................................
    public function getHelpData(Request $request)
    {
        $requestData = $request->toArray();
        $columns = array(
            // column index  => database column name
            0 => 'must_see.id',
            1 => 'must_see.link',
            1 => 'must_see.title',
            2 => 'must_see.language',
        );

        $totalItems = MustSee::get()->count();
        $totalFiltered = $totalItems;
        $items = MustSee::where('id','!=',0);

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
            $nestedData[] = $item->language;
			$nestedData[] = '<iframe width="220" height="115" src="https://www.youtube.com/embed/'.$item->link.'"></iframe>';
            $nestedData[] = $item->created_at->format('F d, Y');
            if($item->banned==0){ $class="on"; $title="active"; } else { $class="off"; $title="inactive"; }
            $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
            $editLink = '<a href="' . URL::to('/') . '/admin/help/help-edit/'. $item->id .' " title="Help Section"><i class="glyphicon glyphicon-pencil"></i></a>';
            $activateLink = '<a href="' . URL::to('/') . '/admin/help/update-status/'.$item->id.'" title="'.$title.'"><i class="fa fa-toggle-'.$class.'" aria-hidden="true" ></i></a>';
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

      return view('admin.must_see.create');
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
            'code'    => $request->input( 'link'),
        );
        $rules = array(
            'title'     =>   'required',
            'code'    =>   'required',
        )   ;
        $validator = Validator::make($packageData,$rules);
        if ($validator->fails()) {
            return redirect('admin/help/create-help')->withInput()->withErrors($validator);
        }else{
            $faq = new MustSee($request->all());
            $faq->save();
        }
        // redirect
        Session::flash('success_message', 'Your Faq has been added successfully');
        return redirect('/admin/help/help-list');
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
    public function edit_help($id)
    {
        //$Package = Package::first();
        $help = MustSee::findOrFail($id);
        return view('admin.must_see.edit')->with(['help'=>$help]);
    }

    /**
    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function updateHelp($id, Request $request)
    {
        // validate
        $faq = MustSee::find($id);
        $validator = Validator::make($request->all(),
            [
                'title' => 'required',
                'language' => 'required',
                'link' => 'required'


            ], [
                'title.required' => 'This field is required.',
                'language.required' => 'This field is required.',
                'link.required' => 'This field is required.',


            ]);

        if ($validator->fails())
        {
            return redirect('admin/help/help-edit/'.$id)->withInput()->withErrors($validator);
        }
        else
        {
            $data=Input::all();
            $faq->fill($data)->save();
            // redirect
            Session::flash('success_message', 'Must Successfully updated');
            return redirect('admin/help/help-list');
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
	public function delete_record()
    {
        $faq = MustSee::findOrFail($_POST['id']);
        if(!empty($faq->delete()))
        {
            Session::flash('success_message', 'Help has been deleted successfully!');
        }
        else {
            Session::flash('error_message', 'Unable to delete the help');
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
}
