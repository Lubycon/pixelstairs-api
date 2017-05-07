<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;
use App\Models\User;
use App\Traits\AuthorizesRequestsOverLoad;
use Log;

class AuthSigninRequest extends Request
{
    use AuthorizesRequestsOverLoad;

    public function authorize()
    {
        return User::isGhost();
    }

    public function rules()
    {
        $requiredRule = [
            'email' => 'required',
            'password' => 'required',
            // TODO :: sns code later...
        ];
        return $requiredRule;
    }
}
