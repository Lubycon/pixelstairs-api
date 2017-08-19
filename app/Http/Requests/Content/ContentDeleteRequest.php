<?php

namespace App\Http\Requests\Content;

use App\Http\Requests\Request;
use App\Models\User;
use App\Traits\AuthorizesRequestsOverLoad;

use Log;

class ContentDeleteRequest extends Request
{
    use AuthorizesRequestsOverLoad;

    public function authorize()
    {
        $content = $this->route()->parameters()['content_id'];
        return User::isUser() && User::isActive() && User::isMyContent($content);
    }

    public function rules()
    {
        $requiredRule = [
        ];
        return $requiredRule;
    }
}
