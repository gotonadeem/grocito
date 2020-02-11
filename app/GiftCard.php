<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class GiftCard extends Model
{
    protected $table="gift_cards";
    protected $fillable = [
        'type', 'title', 'card_value','description','image1','image2','image3','status'];
}