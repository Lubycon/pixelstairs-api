<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;
use App\Models\User;

use Log;

class ServiceAuthSignupRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $requiredRule = [
            'name' => 'required|unique:users,name',
            'email' => 'required|unique:users,email|email',
            'phone' => 'required|unique:users,phone',
            'password' => 'required|string',
            'gender' => "required",
            'birthday' => "required",
            'location.city' => "required|string",
            'location.address1' => "required|string",
            'location.address2' => "required|string",
            'location.postCode' => "required",
        ];
        return $requiredRule;
    }
}
