<?php

namespace App\Http\Requests\Award;

use App\Http\Requests\Request;
use App\Traits\AuthorizesRequestsOverLoad;
use App\Traits\GetUserModelTrait;
use Log;
use App\Models\Review;

class AwardGetUserRequest extends Request
{
    use AuthorizesRequestsOverLoad,
        GetUserModelTrait;

    public function authorize()
    {
        $user_id = $this->route()->parameters()['user_id'];
        $user = $this->getUserByTokenOrFail($this->header('x-mitty-token'));

        return $user->id === $user_id;
    }

    public function rules()
    {
        $requiredRule = [
        ];

        return $requiredRule;
    }
}
