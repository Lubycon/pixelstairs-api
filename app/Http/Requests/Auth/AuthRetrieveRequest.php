<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;
use App\Models\User;

class AuthRetrieveRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $requiredRule = [
            'name' => 'required',
            'nickname' => 'required',
            'email' => 'required',
            'password' => 'required',
            'position' => 'required',
            'location' => 'array|required',
            'location.city' => 'string|required',
            'location.address1' => 'string|required',
            'location.address2' => 'string|required',
            'location.postCode' => 'string|required',
            'likeCategory' => 'array|required',
            'likeCategory.categoryId' => 'integer|required',
            'likeCategory.divisionId' => 'integer|required',
            'profileImg' => 'required',
            'profileImg.id' => 'string',
            'profileImg.file' => 'required',
            'profileImg.index' => 'integer',
        ];

        return $requiredRule;
    }
}
