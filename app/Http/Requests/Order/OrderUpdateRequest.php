<?php

namespace App\Http\Requests\Order;

use App\Http\Requests\Request;

class OrderUpdateRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $requiredRule = [
            'haitaoOrderId' => 'required|unique:orders,haitao_order_id',
        ];

        return $requiredRule;
    }
}
