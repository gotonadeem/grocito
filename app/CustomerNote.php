<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class CustomerNote extends Model
{
    protected $table="order_notes";
    protected $fillable=['order_id','message','heading'];
}
?>
