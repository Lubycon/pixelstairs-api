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
            "email" => "required|availableEmail|email",
            "nickname" => "required|availableNickname",
            "password" => "required|string",
            "newsletterAccepted" => "required|boolean",
            "termsOfServiceAccepted" => "required|boolean"
        ];
        return $requiredRule;
    }
}
