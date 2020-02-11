<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class UserNotice extends Model
{
    protected $table="user_notices";
    protected $fillable=['user_id','notice_id'];
}
?>
