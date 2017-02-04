<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\Request;
use App\Traits\AuthorizesRequestsOverLoad;
use App\Traits\GetUserModelTrait;
use Log;

class ProductPutRequest extends Request
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
            'marketProductId' => 'required|unique:users,name',
            'marketId' => 'unique:users,nickname',

            'title' => 'required|array',
            'title.origin' => 'required',
            'title.zh' => 'required',
            'title.ko' => 'required',
            'title.en' => 'required',

            'brand' => 'array|required',
            'brand.origin' => 'required',
            'brand.zh' => 'required',
            'brand.ko' => 'required',
            'brand.en' => 'required',

            'description' => 'array|required',
            'description.origin' => 'required',
            'description.zh' => 'required',
            'description.ko' => 'required',
            'description.en' => 'required',

            'weight' => "required",

            "priceInfo" => "array|required",
            "priceInfo.price" => "required",
            "priceInfo.lowestPrice" => "required",
            "priceInfo.unit" => "required",
            "deliveryPrice" => "required",
            "isFreeDelivery" => "required|boolean",

            "thumbnailUrl" => "array|required",
            "thumbnailUrl.file" => "required",
            "thumbnailUrl.index" => "required",

            "url" => "required",
            "safeStock" => "required|integer",
            'categoryId' => 'integer|required',
            'divisionId' => 'integer|required',
            "sections" => "array|required",
            "sections.0" => "required|integer",

            'optionKeys' => "array|required",
            "options" => "array|required",

            "isLimited" => "boolean",
            "endDate" => "required",
            "seller" => "required|array",
            "seller.name" => "required",
            "seller.rate" => "required",

            'productGender' => 'required',
            'manufacturerCountryId' => 'required',
            'questions' => 'required|array',
        ];

        return $requiredRule;
    }
}
