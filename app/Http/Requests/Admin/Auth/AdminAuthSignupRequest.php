<?php

namespace App\Http\Requests\Admin\Auth;

use App\Http\Requests\Request;
use App\Models\User;
use App\Traits\AuthorizesRequestsOverLoad;

use Log;

class AuthSignupRequest extends Request
{
    use AuthorizesRequestsOverLoad;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $requiredRule = [
            "email" => "required|availableEmail|email|max:255",
            "nickname" => "required|availableNickname|max:20|min:3",
            "password" => "required|string|availablePassword|max:1000",
            "newsletterAccepted" => "required|boolean",
            "termsOfServiceAccepted" => "required|boolean"
        ];
        return $requiredRule;
    }
}
