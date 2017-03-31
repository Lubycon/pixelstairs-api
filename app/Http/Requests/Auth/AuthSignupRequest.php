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
            "email" => "required|unique:users,email|email",
            "nickname" => "required|unique:users,nickname",
            "password" => "required|string",
            "newsletterAccepted" => "required|boolean",
            "termsOfServiceAccepted" => "required|boolean"
        ];
        return $requiredRule;
    }
}
