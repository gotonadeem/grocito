<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class UserNote extends Model
{
    protected $table="user_notes";
    protected $fillable=['user_id','message','heading'];
}
?>
