<?php

namespace App\Http\Requests\Certs;

use App\Http\Requests\Request;

class CertsSignupTokenRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $requiredRule = [
            'code' => 'required'
        ];
        return $requiredRule;
    }
}
