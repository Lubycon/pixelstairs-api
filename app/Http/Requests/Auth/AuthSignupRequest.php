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
            'grade' => 'required|in:normal,admin,superAdmin',
            'gender' => 'required|in:1,2',
            'birthday' => 'required|date',
            'countryId' => 'required|int',
            'city' => 'required',
            'address1' => 'required',
            'address2' => 'required',
            'post_code' => 'required',
//            'profile' => 'required|int',
        ];

        return $requiredRule;
    }
}
