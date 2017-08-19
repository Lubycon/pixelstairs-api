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
        return User::isActive() && User::isUser();
    }

    /**
     *  @SWG\Definition(
     *   definition="contents/post",
     *   type="object",
     *   allOf={
     *       @SWG\Schema(
     *           required={"title","description","licenseCode","hashTags"},
     *           @SWG\Property(property="title", type="string", default="test title~~"),
     *           @SWG\Property(property="description", type="string", default="lorammmmmm"),
     *           @SWG\Property(property="licenseCode", type="string", default="0101"),
     *           @SWG\Property(property="hashTags", type="string", default={"array","need"}),
     *       )
     *   }
     * )
     */
    public function rules()
    {
        $requiredRule = [
            "title" => "required",
            "description" => "required",
            "licenseCode" => "required|string",
            "hashTags" => "required|array",
        ];
        return $requiredRule;
    }
}
