<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;
use App\Traits\GetUserModelTrait;
use App\Traits\AuthorizesRequestsOverLoad;
use Log;

class AuthPostRetrieveRequest extends Request
{
    use AuthorizesRequestsOverLoad,
        GetUserModelTrait;

    public function authorize()
    {
        $routeParam = $this->route()->parameters()['id'];
        $user = $this->getUserByTokenOrFail($this->header('x-mitty-token'));

        return $user->id === $routeParam;
    }

    public function rules()
    {
        $requiredRule = [
            'name' => 'required|unique:users,name',
            'nickname' => 'unique:users,nickname',
            'email' => 'unique:users,email|email',
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
