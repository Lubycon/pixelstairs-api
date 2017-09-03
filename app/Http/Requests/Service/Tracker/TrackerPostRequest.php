<?php

namespace App\Http\Requests\Service\Tracker;

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

    /**
     *  @SWG\Definition(
     *   definition="tracker",
     *   type="object",
     *   allOf={
     *       @SWG\Schema(
     *           required={"uuid","current_url","prev_url","action"},
     *           @SWG\Property(property="uuid", type="string", default="47328915fjd"),
     *           @SWG\Property(property="currentUrl", type="string", default="http://current.com"),
     *           @SWG\Property(property="prevUrl", type="string", default="http://prev.com"),
     *           @SWG\Property(property="action", type="string", default="1"),
     *       )
     *   }
     * )
     */
    public function rules()
    {
        $requiredRule = [
            "uuid" => "required",
            "currentUrl" => "required",
            "prevUrl" => "required",
            "action" => "required"
        ];
        return $requiredRule;
    }
}
