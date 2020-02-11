<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class SellerPenalty extends Model
{

      public function order()
      {
        return $this->belongsTo('App\Order');
      }
	  
	  function order_meta()
	  {
		  return $this->belongsTo('App\OrderMeta');
	  }
	 
}
?>
