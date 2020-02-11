<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
   /* protected $redirectTo = 'admin/dashboard';
    protected $linkRequestView = 'admin.passwords.email';
    protected $resetView = 'admin.passwords.reset';
    protected $guard = 'admin';*/
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'simple_pass','password','active','role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
	
	function subadmin_access()
	{
        return $this->hasOne('App\SubadminAccess',"user_id");
	}
}
