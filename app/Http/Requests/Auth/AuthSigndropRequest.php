<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;
use App\Models\User;
use App\Traits\AuthorizesRequestsOverLoad;

class AuthSigndropRequest extends Request
{
    use AuthorizesRequestsOverLoad;

    public function authorize()
    {
        return User::isUser();
    }

    public function rules()
    {
        $requiredRule = [
            'reasonCode' => 'required'
        ];

        return $requiredRule;
    }
}
