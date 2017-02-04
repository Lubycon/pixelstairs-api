<?php

namespace App\Http\Requests\Review;

use App\Http\Requests\Request;

use App\Traits\GetUserModelTrait;
use App\Traits\AuthorizesRequestsOverLoad;

use Log;
use Abort;

use App\Models\Review;

class ReviewPutRequest extends Request
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
            'title' => 'required',
            'answers' => 'required|array',
            'images' => 'required|array',
        ];

        return $requiredRule;
    }
}
