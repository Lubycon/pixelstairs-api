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
            'email' => 'required',
            'password' => 'required',
            'snsCode' => 'required'
        ];
        $validateRule = $this->getModelValidateRule();
        $rule = $this->ruleMapping($requiredRule,$validateRule);

        return $rule;
    }

    public function getModelValidateRule(){
        return User::rules();
    }
}
