<?php

namespace App\Http\Requests\Service\Comment;

use App\Http\Requests\Request;
use App\Models\User;
use App\Traits\AuthorizesRequestsOverLoad;

use Log;

class CommentPutRequest extends Request
{
    use AuthorizesRequestsOverLoad;

    public function authorize()
    {
        $comment = $this->route()->parameters()['comment_id'];
        return User::isUser() && User::isActive() && User::isMyComment($comment);
    }

    /**
     *  @SWG\Definition(
     *   definition="comments/put",
     *   type="object",
     *   allOf={
     *       @SWG\Schema(
     *           required={"description"},
     *           @SWG\Property(property="description", type="string", default="lorammmmmm"),
     *       )
     *   }
     * )
     */
    public function rules()
    {
        $requiredRule = [
            "description" => "required|max:65500",

        ];
        return $requiredRule;
    }
}
