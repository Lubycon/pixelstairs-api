<?php

namespace App\Http\Requests\Review;

use App\Http\Requests\Request;
use App\Traits\AuthorizesRequestsOverLoad;
use App\Traits\GetUserModelTrait;
use Log;

class ReviewPostRequest extends Request
{
    use AuthorizesRequestsOverLoad,
        GetUserModelTrait;

    public function authorize()
    {
        $user = $this->getUserByTokenOrFail($this->header('x-mitty-token'));
        return !is_null($user);
    }

    public function rules()
    {
        $requiredRule = [
            'title' => 'required',
            'answers' => 'required|array',
            'images' => 'required|array',
            'target' => 'required',
        ];

        return $requiredRule;
    }
}
