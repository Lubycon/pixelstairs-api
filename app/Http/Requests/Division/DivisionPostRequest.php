<?php

namespace App\Http\Requests\Division;

use App\Http\Requests\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Traits\GetUserModelTrait;
use Log;

class DivisionPostRequest extends Request
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
