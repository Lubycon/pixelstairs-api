<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;
use App\Models\User;

class AuthSigndropRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $requiredRule = [
            'reasonCode' => 'required',
            'reason' => 'max:255'
        ];

        return $requiredRule;
    }
}
