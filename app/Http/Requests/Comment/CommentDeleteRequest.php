<?php

namespace App\Http\Requests\Comment;

use App\Http\Requests\Request;
use App\Models\User;
use App\Traits\AuthorizesRequestsOverLoad;

use Log;

class CommentDeleteRequest extends Request
{
    use AuthorizesRequestsOverLoad;

    public function authorize()
    {
        $comment = $this->route()->parameters()['comment_id'];
        return User::isUser() && User::isActive() && User::isMyComment($comment);
    }

    public function rules()
    {
        $requiredRule = [
        ];
        return $requiredRule;
    }
}
