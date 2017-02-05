<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;
use App\Models\User;

class AuthSigninRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $requiredRule = [
            'id' => 'required',
            'password' => 'required'
        ];
        return $requiredRule;
    }

    public function getModelValidateRule(){
        return User::rules();
    }
}
