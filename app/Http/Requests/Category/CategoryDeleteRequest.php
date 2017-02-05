<?php

namespace App\Http\Requests\Category;

use App\Http\Requests\Request;
use App\Traits\GetUserModelTrait;
use App\Traits\AuthorizesRequestsOverLoad;

use Log;

class CategoryDeleteRequest extends Request
{
    use AuthorizesRequests,
        GetUserModelTrait;

    public function authorize()
    {
        $user = $this->getUserByTokenOrFail($this->header('x-mitty-token'));
        return $user->isAdmin();
    }

    public function rules()
    {
        $requiredRule = [
        ];

        return $requiredRule;
    }
}
