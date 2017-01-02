<?php

namespace App\Http\Requests\Certs;

use App\Http\Requests\Request;

class CertsPasswordLimitTimeRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $requiredRule = [
            'email' => 'email|required'
        ];
        return $requiredRule;
    }
}
