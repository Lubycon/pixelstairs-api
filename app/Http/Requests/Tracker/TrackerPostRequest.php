<?php

namespace App\Http\Requests\Tracker;

use App\Http\Requests\Request;
use App\Models\User;
use App\Traits\AuthorizesRequestsOverLoad;

use Log;

class TrackerPostRequest extends Request
{
    use AuthorizesRequestsOverLoad;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $requiredRule = [
            "uuid" => "required",
            "current_url" => "required",
            "prev_url" => "required",
            "action" => "required"
        ];
        return $requiredRule;
    }
}
