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
use App\Enquiry;
use DB;
use URL;
use Excel;
use File;
use Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class EnquiryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }

    public function index()
    {
        return view("admin.enquiry.index");
    }

    //get list of record of subadmin...........................................................
    public function getEnquiryData(Request $request)
    {
        $requestData = $_REQUEST;
        $columns = array(
            // column index  => database column name
            0 => 'user_id',
            1 => 'property',
            2 => 'property_type',
            3 => 'accommodation',
            4 => 'minBudget',
            5 => 'maxBudget',
            6 => 'locationCity',
            7 => 'created_at',
            8 => 'updated_at',
        );
        $totalUsers = Enquiry:: with('user_name')->Join('users', 'enquiries.user_id', '=', 'users.id')->get()->count();
        $totalFiltered = $totalUsers;
        $users = Enquiry::with('user_name')->select('enquiries.*')->orderBy('enquiries.id', 'desc');
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value']))
        {
            $users->where('username','LIKE','%'.$searchString.'%')->orWhereHas('activation_wallet', function ($query) use ($searchString)
            {
                $query->whereRaw("activation_wallets.coins  LIKE '%" . $searchString . "%'");
            });
            $totalFiltered = User::with('user_profile,activation_wallet,user_kyc')->where('username','LIKE','%'.$searchString.'%')->orWhereHas('activation_wallet', function ($query) use ($searchString)
            {
                $query->whereRaw("activation_wallets.coins LIKE '%" . $searchString . "%'");
            })
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
             $nestedData[] = "fsfdsfs";
             $nestedData[] = $item->property;
             $nestedData[] = $item->property_type;
             $nestedData[] = $item->accommodation;
             $nestedData[] = $item->minBudget;
             $nestedData[] = $item->maxBudget;
             $nestedData[] = $item->locationCity;
             $date = strtotime($item->created_at);
             $nestedData[] = date('d-m-Y', $date);
             $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
             $nestedData[] =  $deleteLink;
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

        // $total_amount=ActivationWallet::where('user_id',$id)->sum('coins');
        //$total=Transfer::where('receiver',$id)->where('wallet_type','working_to_workin')->sum('coins');

        //working wallet.............
        // $total_amount_w=WorkingWallet::where('user_id',$id)->sum('income');
        //$total_amount_withdraw=WorkingWallet::where('user_id',$id)->where('type','withdrawal')->sum('coins');
        //$level_amount=LevelIncome::where('user_id',$id)->sum('income');
        //$reward_amount=WorkingWallet::where('user_id',$id)->where('type','reward_bonus')->where('status','1')->sum('coins');
        //$reward_amount=RewardBonus::where('user_id',$id)->sum('bonus_amount');
        //$totalDepositAmount=$total_amount_w + $reward_amount+ $level_amount;
        // $totalworkingAmount=$totalDepositAmount - $total_amount_withdraw;
        //$transfer_rec=Transfer::where('receiver',$id)->where('wallet_type','working_to_working')->sum('coins');
        //$transfer_send=Transfer::where('sender',$id)->where('wallet_type','working_to_working')->sum('coins');

        //$working_sum=$totalworkingAmount+$transfer_rec;
        // $rest_working= $working_sum- $transfer_send;
        // get the user details
        $user = UserKyc::with('user')->where('user_id', $id)->first();
        // show the view and pass the nerd to it
        return view('admin.user.view')->with('user', $user);
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
}

?>