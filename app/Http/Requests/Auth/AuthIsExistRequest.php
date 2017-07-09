<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;
use App\Models\User;
use App\Traits\AuthorizesRequestsOverLoad;

use Log;

class AuthIsExistRequest extends Request
{
    use AuthorizesRequestsOverLoad;

    public function authorize()
    {
        return true;
    }

    /**
     *  @SWG\Definition(
     *   definition="auth/isexist",
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
