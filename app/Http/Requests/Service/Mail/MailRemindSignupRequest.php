<?php

namespace App\Http\Requests\Service\Mail;

use App\Http\Requests\Request;
use App\Models\User;
use App\Traits\AuthorizesRequestsOverLoad;

use Log;

class MailRemindSignupRequest extends Request
{
    use AuthorizesRequestsOverLoad;

    public function authorize()
    {
        return User::isUser() && User::isInactive();
    }

    public function rules()
    {
        $requiredRule = [
        ];
        return $requiredRule;
    }
}