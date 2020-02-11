<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class BankDetails extends Model
{
    protected $table="bank_details";
    
    protected $fillable = ['account_no,bank_name,ifsc,account_holder_name'];
}
?>
