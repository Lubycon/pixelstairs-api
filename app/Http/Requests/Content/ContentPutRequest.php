<?php

namespace App\Http\Requests\Content;

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
        User::isMyContent($content);
        return User::isUser();
    }

    /**
     *  @SWG\Definition(
     *   definition="contents/put",
     *   type="object",
     *   allOf={
     *       @SWG\Schema(
     *           required={"title","description","licenseCode","hashTags","image"},
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
            "licenseCode" => "required",
            "hashTags" => "required|array",
            "image" => "required",
        ];
        return $requiredRule;
    }
}
