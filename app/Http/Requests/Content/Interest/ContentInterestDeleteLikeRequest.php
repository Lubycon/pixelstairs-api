<?php

namespace App\Http\Requests\Content\Interest;

use App\Http\Requests\Request;
use App\Models\User;
use App\Traits\AuthorizesRequestsOverLoad;

use Log;

class ContentInterestDeleteLikeRequest extends Request
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
