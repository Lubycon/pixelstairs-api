<?php

namespace App\Http\Requests\Cert;

use App\Http\Requests\Request;
use App\Models\User;
use App\Traits\AuthorizesRequestsOverLoad;

use Log;

class CertCheckAccessRequest extends Request
{
    use AuthorizesRequestsOverLoad;

    public function authorize()
    {
        User::isInactive();
        return User::isUser();
    }

    public function rules()
    {
        $requiredRule = [
        ];
        return $requiredRule;
    }
}
