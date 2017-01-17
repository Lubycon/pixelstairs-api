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
            'order_id' => 'required|unique:orders,haitao_order_id',
            'user_id' => 'required',
            'quantity' => 'required',
            'sku' => 'required',
        ];

        return $requiredRule;
    }
}
