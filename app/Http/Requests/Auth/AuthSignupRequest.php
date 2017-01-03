<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;
use App\Models\User;

use Log;

class AuthSignupRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $requiredRule = [
            'email' => 'required|unique:users,email|email',
            'name' => 'required|unique:users,name',
            'nickname' => 'required|unique:users,nickname',
            'password' => 'required',
            'position' => 'required',
            'grade' => 'required|in:user,admin,super_admin',
        ];

        return $requiredRule;
    }

    public function getModelValidateRule(){
        return User::rules();
    }
}
