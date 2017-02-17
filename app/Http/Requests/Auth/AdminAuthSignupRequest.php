<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;
use App\Traits\AuthorizesRequestsOverLoad;
use App\Traits\GetUserModelTrait;
use Log;

class AdminAuthSignupRequest extends Request
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
            'haitaoUserId' => "integer",
            'name' => 'required|unique:users,name',
            'nickname' => 'unique:users,nickname',
            'email' => 'unique:users,email|email',
            'phone' => 'required|unique:users,phone',
            'password' => 'string',
            'position' => "string"
        ];

        return $requiredRule;
    }
}
