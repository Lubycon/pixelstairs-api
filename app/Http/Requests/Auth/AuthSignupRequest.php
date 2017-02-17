<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;
use App\Traits\AuthorizesRequestsOverLoad;
use App\Traits\GetUserModelTrait;
use Log;

class AuthSignupRequest extends Request
{
    use AuthorizesRequestsOverLoad,
        GetUserModelTrait;

    public function authorize()
    {
        $user = $this->getUserByTokenOrFail($this->header('x-mitty-token'));
        return $user->isAdmin();
    }

    public function rules()
    {
        $requiredRule = [
            'name' => 'required|unique:users,name',
            'email' => 'required|unique:users,email|email',
            'phone' => 'required|unique:users,phone',
            'password' => 'required|string',
            'position' => "required|string",
            'birthday' => "required",
            'gender' => "required",
        ];

        return $requiredRule;
    }
}
