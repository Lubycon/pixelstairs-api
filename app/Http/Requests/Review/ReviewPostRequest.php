<?php

namespace App\Http\Requests\Review;

use App\Http\Requests\Request;
use App\Traits\AuthorizesRequestsOverLoad;
use App\Traits\GetUserModelTrait;
use Log;

use App\Models\Award;

class ReviewPostRequest extends Request
{
    use AuthorizesRequestsOverLoad,
        GetUserModelTrait;

    public function authorize()
    {
        $award = Award::findOrFail($this->route()->parameters()['award_id']);
        $user = $this->getUserByTokenOrFail($this->header('x-mitty-token'));

        return $user->id === $award->user_id;
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
