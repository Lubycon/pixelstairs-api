<?php

namespace App\Http\Requests\Content;

use App\Http\Requests\Request;
use Log;

class ContentUploadRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $requiredRule = [
            'attachedFiles' => 'array',
            'attachedImg' => 'array',
            'content' => 'required|array',
            'content.type' => 'required|numeric',
            'content.data' => 'required_if:content.type,0|string', // 2d
            'content.data' => 'required_if:content.type,1|array', // 3d
            'content.data.map' => 'required_if:content.type,1|json', // 3d
            'content.data.model' => 'required_if:content.type,1|json', // 3d
            'content.data.lights' => 'required_if:content.type,1|json', // 3d
            'setting.title' => 'required|string',
            'setting.description' => 'required|string',
            'setting.thumbnail' => 'required|string',
            'setting.category' => 'required|array|max:3',
            'setting.tags' => 'array',
            'setting.license' => 'required|array',
            'setting.license.by' => 'required|boolean',
            'setting.license.nc' => 'required|boolean',
            'setting.license.nd' => 'required|boolean',
            'setting.license.sa' => 'required|boolean',
        ];

        $tagObj = array_key_exists('tags',$this->setting) ? $this->setting['tags'] : [] ;
        $categoryObj = array_key_exists('category',$this->setting) ? $this->setting['category'] : [] ;

        foreach($tagObj as $key => $value){
            $requiredRule['setting.tags.'.$key] = 'required';
        }
        foreach($categoryObj as $key => $value){
            $requiredRule['setting.category.'.$key] = 'required';
        }


        return $requiredRule;
    }
}
