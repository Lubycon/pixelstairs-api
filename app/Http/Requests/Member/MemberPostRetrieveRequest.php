<?php

namespace App\Http\Requests\Member;

use App\Http\Requests\Request;
use App\Models\User;
use App\Traits\AuthorizesRequestsOverLoad;

use Log;

class MemberPostRetrieveRequest extends Request
{
    use AuthorizesRequestsOverLoad;

    public function authorize()
    {
        $user_id = $this->route()->parameters()['id'];
        User::isMyId($user_id);
        return User::isUser();
    }

    public function rules()
    {
        $requiredRule = [
            "newsletterAccepted" => "required|boolean"
        ];
        return $requiredRule;
    }
}
