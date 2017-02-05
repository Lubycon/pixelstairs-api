<?php

namespace App\Http\Requests\Survey;

use App\Http\Requests\Request;
use App\Traits\AuthorizesRequestsOverLoad;
use App\Traits\GetUserModelTrait;
use Log;

class SurveyPostRequest extends Request
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
            "user" => "array|required",
            'user.name' => 'required|unique:users,name',
            'user.email' => 'required|unique:users,email|email',
            'user.gender' => 'required',
            'user.birthday' => 'required',
            'user.location' => 'array|required',
            'user.location.city' => 'string|required',
            'user.location.address1' => 'string|required',
            'user.location.address2' => 'string|required',
            'user.location.postCode' => 'string|required',
            'likeCategory' => 'array|required',
            'likeCategory.categoryId' => 'integer|required',
            'likeCategory.divisionId' => 'integer|required',
            "survey" => "array|required",
            "survey.purchasingFactor" => "required",
            "survey.majorStore" => "required",
            "survey.favoriteBrand" => "required",
            "survey.connectionPath" => "required",
        ];

        return $requiredRule;
    }
}
