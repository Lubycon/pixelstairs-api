<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;
use App\Models\User;
use App\Traits\AuthorizesRequestsOverLoad;

use Log;

class AuthSignupRequest extends Request
{
    use AuthorizesRequestsOverLoad;

    public function authorize()
    {
        return User::isGhost();
    }

    public function rules()
    {
        $requiredRule = [
            "email" => "required|unique:users,email|email",
            "nickname" => "required|unique:users,nickname",
            "password" => "required|string",
            "newsletterAccepted" => "required|boolean",
            "termsOfServiceAccepted" => "required|boolean"
        ];
        return $requiredRule;
    }
}
