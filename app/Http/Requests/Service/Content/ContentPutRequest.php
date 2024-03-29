<?php

namespace App\Http\Requests\Service\Content;

use App\Http\Requests\Request;
use App\Models\User;
use App\Traits\AuthorizesRequestsOverLoad;

use Log;

class ContentPutRequest extends Request
{
    use AuthorizesRequestsOverLoad;

    public function authorize()
    {
        $content = $this->route()->parameters()['content_id'];
        return User::isUser() && User::isActive() && User::isMyContent($content);
    }

    /**
     *  @SWG\Definition(
     *   definition="contents/put",
     *   type="object",
     *   allOf={
     *       @SWG\Schema(
     *           required={"title","licenseCode","hashTags"},
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
            "title" => "required|max:255",
            "description" => "max:65500",
            "licenseCode" => "required",
            "hashTags" => "required|array",
        ];
        return $requiredRule;
    }
}
