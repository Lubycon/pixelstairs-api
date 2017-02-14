<?php

namespace App\Http\Requests\GiveAccept;

use App\Http\Requests\Request;
use App\Traits\AuthorizesRequestsOverLoad;
use App\Traits\GetUserModelTrait;
use Log;
use App\Models\Review;

class GiveAcceptPostRequest extends Request
{
    use AuthorizesRequestsOverLoad,
        GetUserModelTrait;

    public function authorize()
    {
        $review = Review::findOrFail($this->route()->parameters()['review_id']);
        $user = $this->getUserByTokenOrFail($this->header('x-mitty-token'));

        return $user->id === $review->user_id;
    }

    public function rules()
    {
        $requiredRule = [
            "applyUserId" => "required"
        ];

        return $requiredRule;
    }
}
