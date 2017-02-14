<?php

namespace App\Http\Requests\GiveApply;

use App\Http\Requests\Request;
use App\Traits\AuthorizesRequestsOverLoad;
use App\Traits\GetUserModelTrait;
use Log;

class GiveApplyGetRequest extends Request
{
    use AuthorizesRequestsOverLoad,
        GetUserModelTrait;

    public function authorize()
    {
        $user = $this->getUserByTokenOrFail($this->header('x-mitty-token'));
        return !is_null($user);
    }

    public function rules()
    {
        $requiredRule = [
        ];

        return $requiredRule;
    }
}
