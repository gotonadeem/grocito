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
use App\Question;
use App\Section;
use Redirect;
use DB;
use URL;
use File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class QuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }
	
    public function index($id,$id2)
    {
		 $data = Faq::findOrFail($id);
		 $data2 = Section::findOrFail($id2);
        return view('admin.questions.index',compact('id','id2'));
    }
 //get list of record of subadmin...........................................................
    public function getQuestionData(Request $request)
    {
       $id=$request->input('id');
       $section_id=$request->input('section_id');
	   $requestData = $request->toArray();
        $columns = array(
            // column index  => database column name
            0 => 'questions.id',
            1 => 'questions.title',
            2 => 'questions.faq_id',
            3 => 'questions.description',
        );

        $totalItems = Question::with('faq')->where('faq_id',$id)->get()->count();
        $totalFiltered = $totalItems;
        $items = Question::with('faq')->where('faq_id',$id)->where('id','!=',0);

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
            $nestedData[] = $item->faq->title;
            $nestedData[] = substr($item->description, 0, 80);
            $nestedData[] = $item->created_at->format('F d, Y');
            if($item->banned==0){ $class="on"; $title="active"; } else { $class="off"; $title="inactive"; }
            $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
            $editLink = '<a href="' . URL::to('/') . '/admin/question/question-edit/'. $item->id .'/'.$section_id.'/'.$id.'" title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>';
            $nestedData[] = $editLink ." | ". $deleteLink;
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

    public function add_question($id,$id2)
    {
		$faq = Faq::findOrFail($id);
		$data = Section::findOrFail($id2);
		$faq_list= Faq::get();
        return view('admin.questions.create',compact('faq','faq_list'));
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
            'faq_id'    => $request->input( 'topic_id'),
        );
        $rules = array(
            'title'     =>   'required',
            'description'    =>   'required',
        )   ;
        $validator = Validator::make($packageData,$rules);
        if ($validator->fails()) {
            return redirect('admin/question/add-question')->withInput()->withErrors($validator);
        }else{
            $faq = new Question($packageData);
            $faq->save();
        }
        // redirect
        Session::flash('success_message', 'Your Question has been added successfully');
        return redirect('admin/question/questions-list/'.$request->input('faq_id')."/".$request->input( 'section_id'));
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
    public function edit_question($id1,$id2,$id3)
    {
        $question = Question::findOrFail($id1);
        $topic = Section::findOrFail($id2);
        $faq = Faq::findOrFail($id3);
        return view('admin.questions.edit')->with(['question'=>$question,'topic'=>$topic,'faq'=>$faq,'id2'=>$id2,'id3'=>$id3]);
    }

    /**
    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function updateQuestion($id,$id2,$id3, Request $request)
    {
        // validate
        $faq = Question::find($id);
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
            return redirect('admin/question/question-edit/'.$id.'/'.$id2.'/'.$id3)->withInput()->withErrors($validator);
        }
        else
        {
            $data=Input::all();
            $faq->fill($data)->save();
            // redirect
            Session::flash('success_message', 'Questions Successfully updated');
            return redirect('admin/question/questions-list/'.$id3.'/'.$id2);
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
        $faq = Question::findOrFail($_POST['id']);
        if(!empty($faq->delete()))
        {
            Session::flash('success_message', 'Question has been deleted successfully!');
        }
        else {
            Session::flash('error_message', 'Unable to delete the Question');
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

}
