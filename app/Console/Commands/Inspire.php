<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Helper;
use App\OrderMeta;
use App\Order;
use App\OrderTracking;
class Inspire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inspire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display an inspiring quote';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
	 
	  public function update_order_status_dtdc($dock_no)
	  {
		    $data = array (
				   'DocketNo' => $dock_no,
				);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL,"https://instacom.dotzot.in/RestService/DocketTrackingService.svc/GetDocketTrackingDetails");
				curl_setopt($ch, CURLOPT_POST, 1);    
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
				curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$response = curl_exec ($ch);
				curl_close ($ch);
				return json_decode($response);
				
	  }	

	 public function update_order_status_delivery($dock_no)
	 {
				 $curl = curl_init();

				curl_setopt_array($curl, array(
				  CURLOPT_URL => "https://track.delhivery.com/api/packages/json/?token=236d8546e58918e5f1c5d296357a79fca288af0d&waybill=$dock_no&verbose=2",
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => "",
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 30,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => "GET",
				  CURLOPT_POSTFIELDS => "",
				  CURLOPT_HTTPHEADER => array(
					"Cache-Control: no-cache",
					"Content-Type: application/x-www-form-urlencoded",
					"Postman-Token: ef04c8f0-99c2-4604-a356-119b2f5bf67f",
					"content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW"
				  ),
				));

				$response = curl_exec($curl);
				$err = curl_error($curl);

				curl_close($curl);

				if ($err) {
					return false;
				} else {
				 $data= json_decode($response);
				 return $data;
				}
				
	 }		

	 
	 public function update_order_status()
	{

		$orders = OrderMeta::with('order')->where('status','ready_to_ship')->orWhere('status','dispatched')->groupBy('order_id')->orderBy('created_at','desc')->get();
		foreach($orders as $vs)
		{
           if($vs->order->id>1095)
			{			  
			  if($vs->order->shipped_by=="Dotzot")
				{
					
					 $data=$this->update_order_status_dtdc($vs->order->dock_no);
					 $reason=$data[0]->CURRENT_STATUS;
					 if($data[0]->CURRENT_STATUS=="Delivered")
					{
						  $trackData['order_id']=$vs->order->id;
						  $trackData['reason']=$reason;
						  $trackData['date']=date('Y-m-d h:i:s');
						  $trackData['type']="delivered";
						  $trackObj= new OrderTracking($trackData);
						  $trackObj->save();
										  
					   $date=date("Y-m-d h:i:s");
					  \DB::table('orders')->where('id', '=',$vs->order->id)->where('seller_id', '=',$vs->order->seller_id)->update(['shipped_date'=>$date]);
					  \DB::table('order_metas')->where('order_id', '=',$vs->order->id)->where('seller_id', '=',$vs->order->seller_id)->update(['status'=>'shipped']);
					}
					else
					{
						\DB::table('orders')->where('id', '=',$vs->order->id)->where('seller_id', '=',$vs->order->seller_id)->update(['reason'=>$reason]);
						 
                          $track=OrderTracking::where('reason',$reason)->first();
						  if(!count($track)>0)
						  {
							  $trackData['order_id']=$vs->order->id;
							  $trackData['reason']=$reason;
							  $trackData['date']=date('Y-m-d h:i:s');
							  $trackData['type']="other";
							  $trackObj= new OrderTracking($trackData);
							  $trackObj->save();
					      }
					}
				}
				elseif($vs->order->shipped_by=="Delhivery")
				{
					 $data=$this->update_order_status_delivery($vs->order->dock_no);
					  if(!is_null($data))
					  {
						  if(!is_null($data->ShipmentData))
						  {
							 $reason=$data->ShipmentData[0]->Shipment->Status->Instructions;
							 if($data->ShipmentData[0]->Shipment->Status->Status=="Delivered")
							 {
								   $date=date('Y-m-d', strtotime($data->ShipmentData[0]->Shipment->Status->StatusDateTime));
								  \DB::table('orders')->where('id', '=',$vs->order->id)->where('seller_id', '=',$vs->order->seller_id)->update(['shipped_date'=>$date]);
								  \DB::table('order_metas')->where('order_id', '=',$vs->order->id)->where('seller_id', '=',$vs->order->seller_id)->update(['status'=>'shipped']);
								          $trackData['order_id']=$vs->order->id;
										  $trackData['reason']=$reason;
										  $trackData['date']=date('Y-m-d h:i:s');
										  $trackData['type']="delivered";
										  $trackObj= new OrderTracking($trackData);
										  $trackObj->save();
							 }
							 else
							 {
									\DB::table('orders')->where('id', '=',$vs->order->id)->where('seller_id', '=',$vs->order->seller_id)->update(['reason'=>$reason]);
									      $track=OrderTracking::where('reason',$reason)->first();
										  if(!count($track)>0)
										  {
											  $trackData['order_id']=$vs->order->id;
											  $trackData['reason']=$reason;
											  $trackData['date']=date('Y-m-d h:i:s');
											  $trackData['type']="other";
											  $trackObj= new OrderTracking($trackData);
											  $trackObj->save();
										  }
							 }
						  }
					  }
				}
			}
				
				
		}
	}
	
	 public function update_dispatched_status()
	{
		
		$orders = OrderMeta::with('order')->where('status','ready_to_ship')->groupBy('order_id')->orderBy('created_at','desc')->get();
		foreach($orders as $vs)
		{
			  if($vs->order->id>1095)
			 {	 
				if($vs->order->shipped_by=="Dotzot")
				{
					$data=$this->update_order_status_dtdc($vs->order->dock_no);
					 $reason=$data[0]->CURRENT_STATUS;
					 if($data[0]->TRACKING_CODE!="DOU")
						{
							//print_r($data);
						   $date=date("Y-m-d h:i:s");
						  \DB::table('order_metas')->where('order_id', '=',$vs->order->id)->where('seller_id', '=',$vs->order->seller_id)->update(['status'=>'dispatched']);
						  $trackData['order_id']=$vs->order->id;
						  $trackData['reason']=$reason;
						  $trackData['date']=date('Y-m-d h:i:s');
						  $trackData['type']="shipped";
						  $trackObj= new OrderTracking($trackData);
						  $trackObj->save();
						}
				}
				elseif($vs->order->shipped_by=="Delhivery")
				{
					$data=$this->update_order_status_delivery($vs->order->dock_no);
					  if(!is_null($data))
					  {
						 if(!is_null($data->ShipmentData))
						  {
							//Dispatched
							 $reason=$data->ShipmentData[0]->Shipment->Status->Instructions;
							 if($data->ShipmentData[0]->Shipment->Status->Status=="Dispatched")
							 {
								 //print_r($data);
								   $date=date("Y-m-d h:i:s");
								  \DB::table('order_metas')->where('order_id', '=',$vs->order->id)->where('seller_id', '=',$vs->order->seller_id)->update(['status'=>'dispatched']);
								  
								  $trackData['order_id']=$vs->order->id;
								  $trackData['reason']=$reason;
								  $trackData['date']=date('Y-m-d h:i:s');
								  $trackData['type']="shipped";
								  $trackObj= new OrderTracking($trackData);
								  $trackObj->save();
							 }
						  }
					  }
				}
			 }
				
				
				
		}
	}
	
    public function handle()
    {
		   $this->update_order_status();
		   $this->update_dispatched_status();	 					
    }
	
	
	
	
	
}
