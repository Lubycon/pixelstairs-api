<?php

namespace App\Http\Requests\Comment;

use App\Http\Requests\Request;
use App\Models\User;
use App\Traits\AuthorizesRequestsOverLoad;

use Log;

class CommentPostRequest extends Request
{
    use AuthorizesRequestsOverLoad;

    public function authorize()
    {
        User::isActive();
        return User::isUser();
    }

    /**
     *  @SWG\Definition(
     *   definition="comments/post",
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
            "description" => "required",
        ];
        return $requiredRule;
    }
}
