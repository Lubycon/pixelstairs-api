<?php

namespace App\Http\Requests\Service\Cert;

use App\Http\Requests\Request;
use App\Models\User;
use App\Traits\AuthorizesRequestsOverLoad;

use Log;

class CertGetDiffTimeRequest extends Request
{
    use AuthorizesRequestsOverLoad;

    public function authorize()
    {
        return User::isInactive() && User::isUser();
    }

    public function rules()
    {
        $requiredRule = [
        ];
        return $requiredRule;
    }
}
