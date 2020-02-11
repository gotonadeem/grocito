<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Transfer extends Model
{
    protected $table="transfers";
    protected $fillable = [
        'sender', 'receiver', 'coins','type','wallet_type',
    ];
    public function user()
    {
        return $this->belongsTo('App\User','id');
    }
}
?>
