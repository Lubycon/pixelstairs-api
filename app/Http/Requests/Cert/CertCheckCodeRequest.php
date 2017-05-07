<?php

namespace App\Http\Requests\Cert;

use App\Http\Requests\Request;
use App\Models\User;
use App\Traits\AuthorizesRequestsOverLoad;

use Log;

class CertCheckCodeRequest extends Request
{
    use AuthorizesRequestsOverLoad;

    public function authorize()
    {
        return User::isUser();
    }

    public function rules()
    {
        $requiredRule = [
            "code" => "required",
        ];
        return $requiredRule;
    }
}
