<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Log;

class User extends BaseModel implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword ,SoftDeletes;

    protected $table = 'users';

    protected $casts = [
        'id' => 'string',
    ];

    protected function rules(){
        // Must be :: without unique value you must be adding unique value to form request required array!!
        return [
            "email" => 'email|max:255',
            "name" => 'max:255',
            "password" => 'max:255',
            "grade" => 'in:user,admin,super_admin',
            "position" => "max:255",
        ];
    }

    protected $fillable = [
        'email',
        'name',
        'nickname',
        'password',
        'grade',
        'position'
    ];

    protected $hidden = ['password', 'remember_token'];
}
