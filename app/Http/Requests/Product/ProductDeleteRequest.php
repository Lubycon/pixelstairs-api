<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\Request;
use App\Traits\AuthorizesRequestsOverLoad;
use App\Traits\GetUserModelTrait;
use Log;

class ProductDeleteRequest extends Request
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
        ];

        return $requiredRule;
    }
}
