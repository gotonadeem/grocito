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
use App\Section;
use DB;
use URL;
use File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class SectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }
    public function index()
    {
        return view('admin.section.index');
    }
    //get list of record of subadmin...........................................................
    public function getSectionData(Request $request)
    {
		
		
        $requestData = $request->toArray();
        $columns = array(
            // column index  => database column name
            0 => 'sections.id',
            1 => 'sections.language',
            2 => 'sections.title',
            3 => 'sections.description',
        );

        $totalItems = Section::get()->count();
        $totalFiltered = $totalItems;
        $items = Section::where('id','!=',0);

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
            $nestedData[] = $item->created_at->format('d-m-Y');
            if($item->banned==0){ $class="on"; $title="active"; } else { $class="off"; $title="inactive"; }
            $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
            $editLink = '<a href="' . URL::to('/') . '/admin/section/section-edit/'. $item->id .' " title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>';
            $viewLink = '<a href="' . URL::to('/') . '/admin/section/section-view/'. $item->id .' " title="View"><i class="glyphicon glyphicon-eye-open"></i></a>';
            $faqLink = '<a href="' . URL::to('/') . '/admin/faq/add-faq/'.$item->id.'" title="Add Faq">Add Topic</a>';
            $faqView = '<a href="' . URL::to('/') . '/admin/faq/faq-list/'.$item->id.'" title="View Faq">View Topic</a>';
            $nestedData[] = $viewLink." | ".$editLink ." | ". $deleteLink." | ".$faqLink." | ".$faqView;
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

    public function create()
    {

      return view('admin.section.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {  
        $packageData = array(
            'title'     => $request->input('title'),
            'language'     => $request->input('language'),
        );
        $rules = array(
            'title'     =>   'required',
            'language'    =>   'required',
        );
        $validator = Validator::make($packageData,$rules);
        if ($validator->fails()) {
            return redirect('admin/section/create-section')->withInput()->withErrors($validator);
        }else{
			    $section = new Section($request->all());
			    $image = $request->file('image');
				if($image) {
					$path_original = public_path() . '/admin/uploads/section';
					$file = $request->image;
					$photo_name = time() . '-' . $file->getClientOriginalName();
					$file->move($path_original, $photo_name);
					$section->image = $photo_name;
				}
            $section->save();
        }
        // redirect
        Session::flash('success_message', 'Your Section has been added successfully');
        return redirect('/admin/section/section-list');
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
        $data = Section::findOrFail($id);


        // show the view and pass the nerd to it
        return view('admin.section.view',compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $section = Section::find($id);
        return view('admin.section.edit')->with(['section'=>$section]);
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
        $faq = Section::find($id);
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
            return redirect('admin/section/section-edit/'.$id)->withInput()->withErrors($validator);
        }
        else
        {
            $data=Input::all();
            $faq->fill($data)->save();
            // redirect
            Session::flash('success_message', 'Section Successfully updated');
            return redirect('admin/section/section-list');
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
        $faq = Section::findOrFail($_POST['id']);
        if(!empty($faq->delete()))
        {
            Session::flash('success_message', 'Section has been deleted successfully!');
        }
        else {
            Session::flash('error_message', 'Unable to delete the Section');
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
