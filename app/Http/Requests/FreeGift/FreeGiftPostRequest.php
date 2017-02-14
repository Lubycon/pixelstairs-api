<?php

namespace App\Http\Requests\FreeGift;

use App\Http\Requests\Request;
use App\Traits\AuthorizesRequestsOverLoad;
use App\Traits\GetUserModelTrait;
use Log;
use App\Models\Review;

class FreeGiftPostRequest extends Request
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
            "options" => "required|array",
            "stockPerEach" => "required|integer",
            "firstDeployCount" => "required|integer",
        ];

        return $requiredRule;
    }
}
