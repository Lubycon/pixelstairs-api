<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword ,SoftDeletes;

    protected $table = 'users';

    protected function rules(){
        // Must be :: without unique value you must be adding unique value to form request required array!!
        return [
            "email" => 'email|max:255',
            "nickname" => 'max:255',
            "password" => 'max:255',
            "grade" => 'in:user',
            'status' => 'in:active,inactive,drop',
            'newsletter' => 'boolean',
            'terms_of_service' => 'boolean',
            'private_policy' => 'boolean',
            'job_id' => 'integer',
            'country_id' => 'integer',
            'profile_img' => 'active_url',
            'description' => 'max:255',
            'company' => 'max:255',
            'city' => 'max:255',
            'mobile' => 'max:255',
            'fax' => 'max:255',
            'web' => 'max:255',
            'emailPublic' => 'in:Public,Private',
            'mobilePublic' => 'in:Public,Private',
            'faxPublic' => 'in:Public,Private',
            'webPublic' => 'in:Public,Private',
            'snsId' => 'integer',
            'snsCode' => 'in:0100,0101,0102',
            'snsToken' => 'max:100'
        ];
    }

    protected $fillable = [
        'nickname',
        'email',
        'password',
        'sns_code',
        'country_id',
        'status',
        'newsletter'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $dates = ['deleted_at'];

    // get reference data
    public function job()
    {
        return $this->hasOne('App\Models\Occupation','id','occupation_id');
    }
    public function country()
    {
        return $this->hasOne('App\Models\Country','id','country_id');
    }
    // get reference data

    // users children table
    public function log()
    {
        return $this->hasMany('App\Models\Log','user_id','id');
    }
    public function career()
    {
        return $this->hasMany('App\Models\Career','user_id','id');
    }
    public function language()
    {
        return $this->hasMany('App\Models\Language','user_id','id');
    }
    public function createOfTheMonth()
    {
        return $this->hasMany('App\Models\CreateOfTheMonth','user_id','id');
    }
    // users children table


    //post
    public function post()
    {
        return $this->belongsTo('App\Models\Post','user_id','id');
    }
    //post


    // action
    public function giveView()
    {
        return $this->hasMany('App\Models\View','give_user_id','id');
    }
    public function takeView()
    {
        return $this->hasMany('App\Models\View','take_user_id','id');
    }

    public function takeComment()
    {
        return $this->hasMany('App\Models\Comment','take_user_id','id');
    }
    public function giveComment()
    {
        return $this->hasMany('App\Models\Comment','give_user_id','id');
    }
    // action
}
