<?php

namespace App\Http\Requests\Content;

use App\Http\Requests\Request;
use App\Models\User;
use App\Traits\AuthorizesRequestsOverLoad;

use Log;

class ContentPostRequest extends Request
{
    use AuthorizesRequestsOverLoad;

    public function authorize()
    {
        return User::isUser();
    }

    public function rules()
    {
        $requiredRule = [
            "title" => "required",
            "description" => "required",
            "licenseCode" => "required",
            "hashTags" => "required|array",
            "images" => "required",
        ];
        return $requiredRule;
    }
}
