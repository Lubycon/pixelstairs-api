<?php

namespace App\Http\Requests\Tracker;

use App\Http\Requests\Request;
use Log;

class TrackerCreateRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $requiredRule = [
            'uuid' => 'required|string',
            'currentUrl' => 'required|string',
            'prevUrl' => 'required|string',
            'action' => 'required|numeric'
        ];

        return $requiredRule;
    }
}
