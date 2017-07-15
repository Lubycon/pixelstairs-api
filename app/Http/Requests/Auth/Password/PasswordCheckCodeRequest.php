<?php

namespace App\Http\Requests\Auth\Password;

use App\Http\Requests\Request;
use App\Models\User;
use App\Traits\AuthorizesRequestsOverLoad;

use Log;

class PasswordCheckCodeRequest extends Request
{
    use AuthorizesRequestsOverLoad;

    public function authorize()
    {
        return User::isGhost();
    }
    /**
     *  @SWG\Definition(
     *   definition="password/checkCode",
     *   type="object",
     *   allOf={
     *       @SWG\Schema(
     *           required={"code"},
     *           @SWG\Property(property="code", type="string", default="write code"),
     *       )
     *   }
     * )
     */
    public function rules()
    {
        $requiredRule = [
            "code" => "required",
        ];
        return $requiredRule;
    }
}
