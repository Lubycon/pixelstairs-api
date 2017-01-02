<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;
use App\Models\User;

class AuthSignupRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $requiredRule = [
            'email' => 'required|unique:users,email',
            'nickname' => 'required|unique:users,nickname',
            'password' => 'required',
            'snsCode' => 'required',
            'country' => 'required',
            'newsletter' => 'required'
        ];
        $validateRule = $this->getModelValidateRule();
        $rule = $this->ruleMapping($requiredRule,$validateRule);

        return $rule;
    }

    public function getModelValidateRule(){
        return User::rules();
    }
}
