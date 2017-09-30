<?php

namespace App\Http\Requests\Service\Member;

use App\Http\Requests\Request;
use App\Models\User;
use App\Traits\AuthorizesRequestsOverLoad;

use Log;
use Auth;

class MemberGetMyRetrieveRequest extends Request
{
    use AuthorizesRequestsOverLoad;

    public function authorize()
    {
        return User::isUser();
    }

    public function rules()
    {
        $requiredRule = [
        ];
        return $requiredRule;
    }
}
