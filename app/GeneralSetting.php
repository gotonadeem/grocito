<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class GeneralSetting extends Model
{
    protected $table="general_settings";
    protected $fillable = ['site_url','site_logo','site_name','popular_image','app_hindi_video','app_english_video','video_hindi_title','video_english_title','app_version','admin_email','admin_name','customer_support_no','contact_email','smtp_username',
	'smtp_password','smtp_host','special_image','popular_image2','deal_of_the_day_image','more_image','notification_status','saleplus_commission','wallet_deduction','message'];
}
?>
