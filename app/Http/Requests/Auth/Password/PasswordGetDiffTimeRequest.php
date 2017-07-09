<?php

namespace App\Http\Requests\Auth\Password;

use App\Http\Requests\Request;
use App\Models\User;
use App\Traits\AuthorizesRequestsOverLoad;

use Log;

class PasswordGetDiffTimeRequest extends Request
{
    use AuthorizesRequestsOverLoad;

    public function authorize()
    {
        return User::isGhost();
    }

    /**
     *  @SWG\Definition(
     *   definition="password/getDiffTime",
     *   type="object",
     *   allOf={
     *       @SWG\Schema(
     *           required={"email"},
     *           @SWG\Property(property="email", type="string", default="test@pixelstairs.com"),
     *       )
     *   }
     * )
     */
    public function rules()
    {
        $requiredRule = [
            "email" => "required|email",
        ];
        return $requiredRule;
    }
}
