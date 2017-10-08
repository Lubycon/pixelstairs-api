<?php

namespace App\Http\Requests\Service\Content\Interest;

use App\Http\Requests\Request;
use App\Models\User;
use App\Traits\AuthorizesRequestsOverLoad;

use Log;

class ContentInterestPostLikeRequest extends Request
{
    use AuthorizesRequestsOverLoad;

    public function authorize()
    {
        return User::isUser() && User::isActive();
    }

    public function rules()
    {
        $requiredRule = [
        ];
        return $requiredRule;
    }
}