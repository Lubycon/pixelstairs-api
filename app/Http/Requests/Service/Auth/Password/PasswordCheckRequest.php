<?php

namespace App\Http\Requests\Service\Auth\Password;

use App\Http\Requests\Request;
use App\Models\User;
use App\Traits\AuthorizesRequestsOverLoad;

use Log;

class PasswordCheckRequest extends Request
{
    use AuthorizesRequestsOverLoad;

    public function authorize()
    {
        return User::isUser();
    }

    /**
     *  @SWG\Definition(
     *   definition="password/checkPassword",
     *   type="object",
     *   allOf={
     *       @SWG\Schema(
     *           required={"password"},
     *           @SWG\Property(property="password", type="string", default="12341234"),
     *       )
     *   }
     * )
     */
    public function rules()
    {
        $requiredRule = [
            "password" => "required",
        ];
        return $requiredRule;
    }
}
