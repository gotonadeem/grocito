<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use App\Wallet;
use App\User;
use App\UserKyc;
use App\WithdrawWallet;
use App\MerchantCommission;
use App\MerchantWallet;
use DB;
use URL;
use Excel;
use Helper;
use Auth;
use File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
class WalletApiController extends Controller
{
     public function __construct()
      {
	     parent::__construct(); 
      }
      
        /*register user......... .......................................................*/
	  function get_my_wallet(Request $request)
	  {
				 $users = array(
					'user_id'     => $request->input('user_id'),
				);
				$rules = array(
					 'user_id'    =>   'required',
				);
				$validator = Validator::make(Input::all(),$rules);
				if ($validator->fails()) {
					return Response::json(array(
						'status_code' => 0,
						'message' => 'validation error',
						'error_message'=>$validator->errors()->first(),
					), 200);
				}else{
					$wallet_amount= Wallet::where('user_id',$users['user_id'])->where('status','approved')->get()->sum('amount');
					$withdraw_amount= WithdrawWallet::where('user_id',$users['user_id'])->get()->sum('amount');
					$rest_amount= $wallet_amount-$withdraw_amount;
					$sum=0;
					return Response::json(array(
						'status_code' => 1,
						'message' => 'Wallet Sum',
						'wallet_amount' =>$rest_amount,
					), 200);
				}
	  }
	  
	     /*register user......... .......................................................*/
	  function sent_request(Request $request)
	  {
				 $users = array(
					'merchant_id'     => $request->input('merchant_id'),
					'user_id'     => $request->input('user_id'),
					'amount'     => $request->input('amount'),
				);
				$rules = array(
					 'merchant_id' =>   'required',
					 'user_id'     =>   'required',
					 'amount'      =>   'required',
				);
				$validator = Validator::make(Input::all(),$rules);
				if ($validator->fails()) {
					return Response::json(array(
						'status_code' => 0,
						'message' => 'validation error',
						'error_message'=>$validator->errors()->first(),
					), 200);
				}else{
					
					
					
					$checkMerchant=User::where('mobile',$users['merchant_id'])->get()->count();
					if($checkMerchant>0)
					{
						
						$wallet_sum= Wallet::where('merchant_id',$users['merchant_id'])->where('status','approved')->sum('amount');
						$comm_sum=MerchantCommission::where('merchant_id',$users['merchant_id'])->sum('level_amount');
						$wallet_sum= $wallet_sum-$comm_sum;
						$comm=MerchantCommission::where('merchant_id',$users['merchant_id'])->get()->count();
						if($comm+1>5)
						{
							$walletlimit=MerchantWallet::select('value')->where('level',5)->first();
						}
						else
						{
						$walletlimit=MerchantWallet::select('value')->where('level',$comm+1)->first();
						}
						
						if($wallet_sum>=$walletlimit['value'])
						{
						  return Response::json(array(
								'status_code' => 0,
								'message' => "Merchant's Wallet limit has been reached.Please try after sometimes",
								'error_message' =>"Merchant's limit has been reached.Please try after sometimes",
							), 200);
                        }
                        else
						{
							
							$check=Wallet::where('status','pending')->where('user_id',$users['user_id'])->where('merchant_id',$users['merchant_id'])->get()->count();
							if($check>0)
							{
								return Response::json(array(
									'status_code' => 0,
									'message' => 'Your request is already pending',
									'error_message' =>"Your request is already pending",
								), 200);
							}
							else
							{						
								$obj= new Wallet($users);
								$obj->save();
								return Response::json(array(
									'status_code' => 1,
									'message' => 'Your cashback request has been submitted successfully',
									'error_message' =>"Your cashback request has been submitted successfully",
								), 200);
							}
						}
					}
					else
					{
						return Response::json(array(
								'status_code' => 0,
								'message' => 'Invalid Merchant Id',
								'error_message' =>"Invalid Merchant Id",
							), 200);
					}
					
				}
	  }
	  
	     /*register user......... .......................................................*/
	  function get_wallet_history(Request $request)
	  {
				 $users = array(
					'user_id'     => $request->input('user_id'),
				);
				$rules = array(
					 'user_id'    =>   'required',
				);
				$validator = Validator::make(Input::all(),$rules);
				if ($validator->fails()) {
					return Response::json(array(
						'status_code' => 0,
						'message' => 'validation error',
						'error_message'=>$validator->errors()->first(),
					), 200);
				}else{
					$wallet_list= Wallet::with('user')->where('user_id',$users['user_id'])->orderBy('id','desc')->get();
					return Response::json(array(
						'status_code' => 1,
						'message' => 'Wallet List',
						'data' =>$wallet_list,
					), 200);
				}
	  }
	  
	  /*merchant user......... .......................................................*/
	  function get_merchant_wallet_history(Request $request)
	  {
				 $users = array(
					'merchant_id'     => $request->input('merchant_id'),
				);
				$rules = array(
					 'merchant_id'    =>   'required',
				);
				$validator = Validator::make(Input::all(),$rules);
				if ($validator->fails()) {
					return Response::json(array(
						'status_code' => 0,
						'message' => 'validation error',
						'error_message'=>$validator->errors()->first(),
					), 200);
				}else{
					$wallet_list= Wallet::with('user')->where('merchant_id',$users['merchant_id'])->orderBy('id','desc')->get();
					return Response::json(array(
						'status_code' => 1,
						'message' => 'Wallet List',
						'data' =>$wallet_list,
					), 200);
				}
	  }
	  
	     /*register user......... .......................................................*/
	  function update_wallet_request(Request $request)
	  {
				 $users = array(
					'id'     => $request->input('id'),
					'status'     => $request->input('status'),
				);
				$rules = array(
					 'id' =>   'required',
					 'status' =>   'required',
				);
				$validator = Validator::make(Input::all(),$rules);
				if ($validator->fails()) {
					return Response::json(array(
						'status_code' => 0,
						'message' => 'validation error',
						'error_message'=>$validator->errors()->first(),
					), 200);
				}else{	
					DB::table('wallets')->where('id', $users['id'])->update(['status' =>$users['status']]);
					return Response::json(array(
						'status_code' => 1,
						'message' => 'Status has been changed successfully',
						'error_message' =>"Status has been changed successfully",
					), 200);
				}
	  }
	  
	  function get_merchant_wallet(Request $request)
	  {
				 $users = array(
					'merchant_id'     => $request->input('merchant_id'),
				);
				$rules = array(
					 'merchant_id'    =>   'required',
				);
				$validator = Validator::make(Input::all(),$rules);
				if ($validator->fails()) {
					return Response::json(array(
						'status_code' => 0,
						'message' => 'validation error',
						'error_message'=>$validator->errors()->first(),
					), 200);
				}else{
					$wallet_sum= Wallet::where('merchant_id',$users['merchant_id'])->where('status','approved')->sum('amount');
					$comm_sum=MerchantCommission::where('merchant_id',$users['merchant_id'])->sum('level_amount');
					$wallet_sum= $wallet_sum-$comm_sum;
					$comm=MerchantCommission::where('merchant_id',$users['merchant_id'])->get()->count();
					$comm_list=MerchantCommission::where('merchant_id',$users['merchant_id'])->orderBy('id','desc')->get();
					//$wallet_list=Wallet::where('merchant_id',$users['merchant_id'])->where('status','approved')->get();
					if($comm+1>5)
					{
						$walletlimit=MerchantWallet::select('value')->where('level',5)->first();
					}
					else
					{
					$walletlimit=MerchantWallet::select('value')->where('level',$comm+1)->first();
					}
					
					$cashback=($walletlimit['value']>$wallet_sum)?$walletlimit['value']-$wallet_sum:$wallet_sum-$walletlimit['value'];
					
					return Response::json(array(
						'status_code' => 1,
						'wallet_limit' => $walletlimit['value'],
						'message' => 'Wallet Amount',
						'history' => $comm_list,
						'level' => (($comm+1>5)?5:$comm+1),
						'cashback' =>$wallet_sum,
					), 200);
				}
	  }
	  
	  //user Transaction update.........................................................................
	 public function update_merchant_commission_payment(Request $request)
    {

            		 $user = array(
					'merchant_id'    =>$request->input('merchant_id'),
					'transaction_id'    => $request->input('transaction_id'),
					'order_id'    => $request->input('order_id'),
					'commission'    => $request->input('commission'),
					'level'    => $request->input('level'),
					'level_amount'    => $request->input('level_amount'),
				);
				$rules = array(
				   'merchant_id'=>'required',
				   'transaction_id'=>'required',
				   'order_id'=>'required',
				   
					);
				$validator = Validator::make($user,$rules);
				if ($validator->fails()) {
					
					 return Response::json(array(
						'status_code' => 0,
						'message' => 'validation error',
						'error_message'=>$validator->errors()->first(),
					   

					), 200);
					
				}else{
					  DB::table('merchant_commissions')->insert(['order_id'=>$user['order_id'],'transaction_id'=>$user['transaction_id'],'merchant_id'=>$user['merchant_id'],'level_amount'=>$user['level_amount'],'commission'=>$user['commission'],'level'=>$user['level']]);
					  return Response::json(array(
						'status_code' => 1,
						'message' => 'Transaction has been completed successfully',
						'error_message'=>"Transaction has been completed successfully",
					), 200);
				}
			
			
        
    }
	  
}