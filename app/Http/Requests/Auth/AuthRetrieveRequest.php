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
            'password' => 'required',
            'position' => 'required',
            'grade' => 'required|in:user,admin,super_admin',
        ];

        return $requiredRule;
    }
}
