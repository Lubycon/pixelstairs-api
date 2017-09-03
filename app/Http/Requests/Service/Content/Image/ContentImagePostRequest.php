<?php

namespace App\Http\Requests\Service\Content\Image;

use App\Http\Requests\Request;
use App\Models\User;
use App\Traits\AuthorizesRequestsOverLoad;

use Log;

class ContentImagePostRequest extends Request
{
    use AuthorizesRequestsOverLoad;

    public function authorize()
    {
        $content_id = $this->route()->parameters()['content_id'];
        return User::isActive() && User::isUser() && User::isMyContent($content_id);
    }

    /**
     *  @SWG\Definition(
     *   definition="contents/image/post",
     *   type="object",
     *   allOf={
     *       @SWG\Schema(
     *           required={"file"},
     *           @SWG\Property(property="file", type="file", default="a image file"),
     *       )
     *   }
     * )
     */
    public function rules()
    {
        $requiredRule = [
            "file" => "required|image|mimes:jpeg,png,jpg|max:10240",
        ];
        return $requiredRule;
    }
}
