<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Auth;
use URL;
use App\Order;
use App\OrderMeta;
use App\UserSender;
use App\Cart;
use Helper;
use DB;
use DNS1D;
use DNS2D;

use PDF;
use Session;
use App\User;
class CancellationRiskController extends Controller
{
    public function __construct()
    {     
	parent::__construct();
	}

	public function index()
    {
		 if(Auth::user())
		{
			
			$orders = OrderMeta::with('order')->where('seller_id',Auth::user()->id)->where('status','pending')->WhereHas('order', function ($query)
            {
				$date = \Carbon\Carbon::today()->subDays(5);
				$query->where('orders.created_at', '<', $date);
				
            })->groupBy('order_id')->orderBy('created_at','desc')->get();
			return view('seller.cancellation_risk.index',compact('orders'));
		}
		else
		{
			return redirect("/login");
		}	
    }
}