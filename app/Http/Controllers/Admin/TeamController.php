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
use App\Team;
use DB;
use URL;
use File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class TeamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }
    public function index()
    {
        return view('admin.team.index');
    }
 //get list of record of subadmin...........................................................
    public function getTeamData(Request $request)
    {
        $requestData = $request->toArray();
        $columns = array(
            // column index  => database column name
            0 => 'our_team.id',
            1 => 'our_team.name',
            2 => 'our_team.position',
            3 => 'our_team.description',
            4 => 'our_team.image',
        );

        $totalItems = Team::get()->count();
        $totalFiltered = $totalItems;
        $items = Team::where('id','!=',0);

        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value']))
        {
            $items->whereRaw("name LIKE '%" . $searchString . "%'");
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
            $nestedData[] = $item->name;
            $nestedData[] = $item->position;
            $img=empty($item->image) ? '':$item->image;
            $nestedData[] = '<img src="' . URL::to('/') . '/public/admin/uploads/team_image/'.$img.'" height="100" width="150">';

            $nestedData[] = substr($item->description, 0, 80);
            $nestedData[] = $item->created_at->format('F d, Y');
            if($item->banned==0){ $class="on"; $title="active"; } else { $class="off"; $title="inactive"; }
            $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
            $editLink = '<a href="' . URL::to('/') . '/admin/team/team-edit/'. $item->id .' " title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>';
            $activateLink = '<a href="' . URL::to('/') . '/admin/team/update-status/'.$item->id.'" title="'.$title.'"><i class="fa fa-toggle-'.$class.'" aria-hidden="true" ></i></a>';
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

    public function add_team()
    {

      return view('admin.team.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $packageData = array(
            'name'     => $request->input( 'name'),
            'position'    => $request->input( 'position'),
            'description'    => $request->input( 'description'),
        );
        $rules = array(
            'name'     =>   'required',
            'position'    =>   'required',
            'description'    =>   'required',
            'images'=>'image|mimes:jpeg,png,jpg',
        )   ;
        $validator = Validator::make($packageData,$rules);
        if ($validator->fails()) {
            return redirect('admin/team/add-team')->withInput()->withErrors($validator);
        }else{
            $team = new Team($request->all());
            //Upload Image
            $image = $request->file('image');
            $path_original=public_path() . '/admin/uploads/team_image';
            $file = $request->images;

            $photo_name = time() . '-' . $file->getClientOriginalName();
            $file->move($path_original, $photo_name);
            $team->image = $photo_name;
            $team->save();
        }
        // redirect
        Session::flash('success_message', 'Your Team has been added successfully');
        return redirect('/admin/team/team-list');
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
    public function edit_team($id)
    {
        //$Package = Package::first();
        $faq = Team::find($id);
        return view('admin.team.edit')->with(['team'=>$faq]);
    }

    /**
    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function updateTeam($id, Request $request)
    {
        // validate
        $team = Team::find($id);
        $validator = Validator::make($request->all(),
            [
                'name' => 'required',
                'position' => 'required',
                'description' => 'required',


            ], [
                'name.required' => 'This field is required.',
                'position.required' => 'This field is required.',
                'description.required' => 'This field is required.',


            ]);


        if ($validator->fails())
        {
            return redirect('admin/team/team-edit/'.$id)->withInput()->withErrors($validator);
        }
        else
        {
            $obj=new Team();
            $obj->name=$request->input('name');
            $obj->position=$request->input('position');
            $obj->description=$request->input('description');
            //$setting = Team::first();

            if($team) {
                $data =$request->all();
                if ($request->hasFile('image'))
                {
                    $path_original=public_path() . '/admin/uploads/team_image';
                    $file = $request->image;

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
                $update_data = Team::find($team->id)->fill($data);
                if($request->image){$update_data->image=$photo_name;}
                $update_data->update();
            }
            else
            {
                if ($file = $request->hasFile('image')) {
                    $file = $request->file('image');
                    $fileName = $file->getClientOriginalName();
                    $destinationPath = public_path() . '/admin/uploads/team_image';
                    $file->move($destinationPath, $fileName);
                    $obj->image = $fileName;
                }
                $obj->save();
            }


            // redirect
            Session::flash('success_message', 'Team Successfully updated');
            return redirect('admin/team/team-list');
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
        $team = Team::findOrFail($_POST['id']);
        if(!empty($team->delete()))
        {
            Session::flash('success_message', 'Team has been deleted successfully!');
        }
        else {
            Session::flash('error_message', 'Unable to delete the Team');
        }
    }
    function update_status($id=null)
    {
        $response=DB::statement("UPDATE our_team SET banned =(CASE WHEN (banned = 1) THEN '0' ELSE '1' END) where id = $id");
        if($response) {
            Session::flash('success_message', 'status has been updated successfully!');
        }
        else {
            Session::flash('error_message', 'Unable to update status');
        }
        return redirect('/admin/team/team-list');
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
