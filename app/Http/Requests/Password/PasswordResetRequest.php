<?php

namespace App\Http\Requests\Password;

use App\Http\Requests\Request;

class PasswordResetRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $requiredRule = [
            'code' => 'required',
            'newPassword' => 'required'
        ];
        return $requiredRule;
    }
}
