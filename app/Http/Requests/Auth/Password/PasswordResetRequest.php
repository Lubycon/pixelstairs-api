<?php

namespace App\Http\Requests\Auth\Password;

use App\Http\Requests\Request;
use App\Models\User;
use App\Traits\AuthorizesRequestsOverLoad;

use Log;

class PasswordResetRequest extends Request
{
    use AuthorizesRequestsOverLoad;

    public function authorize()
    {
        return User::isGhost();
    }

    public function rules()
    {
        $requiredRule = [
            "code" => "required",
            "newPassword" => "required",
        ];
        return $requiredRule;
    }
}
