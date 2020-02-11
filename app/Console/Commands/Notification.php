<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Helper;
use App\OrderMeta;
use App\Order;
class Notification extends Command
{
	
	var $FIREBASE_API_KEY="AAAA_YtrgDM:APA91bHGwuMXAqYx9630IBtWm2LcGrEu9VOyZZd4-Pzd2fNmfcQENhFUPLyU5ZiKHkDVSFOYwboLhD-otKdTWqCB6GuwYirAM9fL6P5LRoT-jyRBxGsN7iVId_7_DFfsPb_SYiSup437";
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display an notifications quote';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
	 
	 public function new_push_notification($data, array $device_tokens)
	{
		    	// prep the bundle
				$msg = array
				(
					'title'  => 'Cartlay',
					'name' => $data['name'],
					'description' => $data['description'],
					'image' => "https://seller.cartlay.com/public/uploads/seller/catalog/".$data['image'],
					'vibrate' => 1,
					'sound'  => 1,
					'largeIcon' => 'larg_icon',
					'smallIcon' => 'small_icon',
					'catalog'=>$data,
					
				);
				$fields = array
				(
					'registration_ids'  => $device_tokens,
					'data'   => $msg
				);

				$headers = array
				(
					'Authorization: key=' . $this->FIREBASE_API_KEY,
					'Content-Type: application/json'
				);

				$ch = curl_init();
				curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
				curl_setopt( $ch,CURLOPT_POST, true );
				curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
				curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
				curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
				curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields)  );
				$result[] = curl_exec($ch );
				$info = curl_getinfo($ch);
				curl_close( $ch );
				return $fields;
	}
	
	
	  public function handle()
      {
				

            $colname = date("Y-m-d");
              $query = \DB::table('products')
						 ->join('product_sponsors', 'products.id', '=', 'product_sponsors.product_id')
						 ->join('product_images', 'products.id', '=', 'product_images.product_id')
						 ->select("products.*",'product_images.image as image')
						 ->whereRaw('FIND_IN_SET(?,product_sponsors.date)', [$colname])
						 ->where('product_sponsors.admin_status', 1);
				    $product_list=$query->get();
					
	
					foreach($product_list as $vs)
					{
  						 $json=array();
						 $json['id']=$vs->id;
						 $json['name']=$vs->name;
						 $json['description'] = $vs->description;
						 $json['price'] = $vs->starting_price;
						 $json['sell_price'] = $vs->sell_price;
						 $json['image'] = $vs->image;
						 $json['catalog_images'] = Helper::get_catalog_images($vs->id);
						 $jsonData= $json;
						 ///////
						 $deviceToken = \DB::table('category_notifications')
						 ->join('users', 'users.id', '=', 'category_notifications.customer_id')
						 ->select("users.device_token")
						 //->where("users.id",1184)
						 //->whereIn('users.id', [7326, 1184])
						 ->where('category_notifications.special_status', 1)->get();
						 $deviceArray=array();
							foreach($deviceToken as $vs)
							{
								if($vs->device_token!="")
								{
								$deviceArray[]= $vs->device_token;
								}
							}
						
						$this->new_push_notification($jsonData,$deviceArray);
						
					}			
	  }
	  
	  
}