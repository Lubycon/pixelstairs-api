<?php

namespace App\Http\Requests\FreeGift;

use App\Http\Requests\Request;
use App\Traits\AuthorizesRequestsOverLoad;
use App\Traits\GetUserModelTrait;
use Log;
use App\Models\Review;

class FreeGiftWinnerPostRequest extends Request
{
    use AuthorizesRequestsOverLoad,
        GetUserModelTrait;

    public function authorize()
    {
        $user = $this->getUserByTokenOrFail($this->header('x-mitty-token'));
        return $user->isAdmin();
    }

    public function rules()
    {
        $requiredRule = [
            "productId" => "required",
            "freeGiftOptionId" => "required",
            "stock" => "required|integer",
            "target" => "required|in:buy,survey,give",
        ];

        return $requiredRule;
    }
}
