<?php

namespace App\Http\Requests\Post;

use App\Http\Requests\Request;
use Log;

class PostUploadRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $requiredRule = [
            'attachedFiles' => 'array',
            'title' => 'required',
            'content' => 'required',
        ];

        return $requiredRule;
    }
}
