<?php

namespace App\Http\Requests\Service\Auth\Password;

use App\Http\Requests\Request;
use App\Models\User;
use App\Traits\AuthorizesRequestsOverLoad;

use Log;

class PasswordResetRequest extends Request
{
    use AuthorizesRequestsOverLoad;

    public function authorize()
    {
        return true;
    }

    /**
     *  @SWG\Definition(
     *   definition="password/reset",
     *   type="object",
     *   allOf={
     *       @SWG\Schema(
     *           required={"code","newPassword"},
     *           @SWG\Property(property="code", type="string", default="write code"),
     *           @SWG\Property(property="newPassword", type="string", default="12341234"),
     *       )
     *   }
     * )
     */
    public function rules()
    {
        $requiredRule = [
            "code" => "required",
            "newPassword" => "required",
        ];
        return $requiredRule;
    }
}
