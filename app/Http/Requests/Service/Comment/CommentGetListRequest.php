<?php

namespace App\Http\Requests\Service\Comment;

use App\Http\Requests\Request;
use App\Models\User;
use App\Traits\AuthorizesRequestsOverLoad;

use Log;

class CommentGetListRequest extends Request
{
    use AuthorizesRequestsOverLoad;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $requiredRule = [
        ];
        return $requiredRule;
    }
}
