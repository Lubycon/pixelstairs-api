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
        User::isMyContent($content);
        return User::isUser();
    }

    public function rules()
    {
        $requiredRule = [
        ];
        return $requiredRule;
    }
}
