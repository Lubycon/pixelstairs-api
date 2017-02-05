<?php

namespace App\Http\Requests\Order;

use App\Http\Requests\Request;

class OrderPostRequest extends Request
{
    public function authorize()
    {
        return true;
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
