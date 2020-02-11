<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
class BankDetail extends Model
{
    protected $table="bank_details";
    protected $fillable = ['bank_name','ifsc','account_holder_name','account_no','branch_name'];
}
?>
