<?php

namespace App\Http\Requests\Content\Image;

use App\Http\Requests\Request;
use App\Models\User;
use App\Traits\AuthorizesRequestsOverLoad;

use Log;

class ContentImagePostRequest extends Request
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
