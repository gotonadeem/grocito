<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Agreement extends Model
{
    protected $table="agreements";
    protected $fillable = ['id','description'];
}
?>
