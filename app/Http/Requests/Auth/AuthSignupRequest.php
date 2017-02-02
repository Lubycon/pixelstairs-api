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
            'email' => 'unique:users,email|email',
            'phone' => 'required|unique:users,phone',
            'name' => 'required|unique:users,name',
            'nickname' => 'unique:users,nickname',
        ];

        return $requiredRule;
    }
}
