<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class SellerCommission extends Model
{
    protected $table="seller_commissions";
    protected $fillable = ['seller_id','commission'];
	
	
	 
}
?>
