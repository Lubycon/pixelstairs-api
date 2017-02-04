<?php

namespace App\Http\Requests\Division;

use App\Http\Requests\Request;
use App\Traits\AuthorizesRequestsOverLoad;
use App\Traits\GetUserModelTrait;
use Log;

class DivisionPutRequest extends Request
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
            "name" => "array|required",
            "name.origin" => "required",
            "name.zh" => "required",
            "name.ko" => "required",
            "name.en" => "required",
            "parentId" => "required",
        ];

        return $requiredRule;
    }
}
