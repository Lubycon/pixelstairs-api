<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;
use App\Models\User;

class AuthRetrieveRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $requiredRule = [
            'name' => 'required',
            'nickname' => 'required',
            'email' => 'required',
            'password' => 'required',
            'position' => 'required',
            'grade' => 'required|in:normal,admin,super_admin',
        ];

        return $requiredRule;
    }
}
