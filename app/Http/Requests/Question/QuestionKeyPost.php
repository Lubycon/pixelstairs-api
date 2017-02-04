<?php

namespace App\Http\Requests\Question;

use App\Http\Requests\Request;
use App\Traits\AuthorizesRequestsOverLoad;
use App\Traits\GetUserModelTrait;
use Log;

class QuestionKeyPost extends Request
{
    use AuthorizesRequestsOverLoad,
        GetUserModelTrait;

    public function authorize()
    {
        $user = $this->getUserByTokenOrFail($this->header('x-mitty-token'));
        return $user->isAdmin();
    }

    public function rules()
    {
        $requiredRule = [
            'divisionId' => 'required',
            'qKey' => 'required|array',
            'qKey.origin' => 'required',
            'qKey.zh' => 'required',
            'qKey.ko' => 'required',
            'qKey.en' => 'required',
            'isCommon' => 'required',
        ];

        return $requiredRule;
    }
}
