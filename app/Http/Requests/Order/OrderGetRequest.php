<?php

namespace App\Http\Requests\Order;

use App\Http\Requests\Request;
use App\Traits\AuthorizesRequestsOverLoad;
use App\Traits\GetUserModelTrait;
use Log;

class OrderGetRequest extends Request
{
    use AuthorizesRequests,
        GetUserModelTrait;

    public function authorize()
    {
        $user = $this->getUserByTokenOrFail($this->header('x-mitty-token'));
        return $user->isAdmin();
    }

    public function rules()
    {
        $requiredRule = [
            'haitaoOrderId' => 'required|unique:orders,haitao_order_id',
            'haitaoUserId' => 'required',
            'quantity' => 'required',
            'sku' => 'required',
            'orderDate' => 'required|date',
        ];

        return $requiredRule;
    }
}
