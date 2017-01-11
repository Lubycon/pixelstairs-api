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
            'position' => 'required',
            'grade' => 'required|in:normal,admin,superAdmin',
        ];

        return $requiredRule;
    }
}
