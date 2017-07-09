<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;
use App\Models\User;
use App\Traits\AuthorizesRequestsOverLoad;

use Log;

class AuthNicknameExistRequest extends Request
{
    use AuthorizesRequestsOverLoad;

    public function authorize()
    {
        return true;
    }

    /**
     *  @SWG\Definition(
     *   definition="auth/nicknameExist",
     *   type="object",
     *   allOf={
     *       @SWG\Schema(
     *           required={"nickname"},
     *           @SWG\Property(property="nickname", type="string", default="tester"),
     *       )
     *   }
     * )
     */
    public function rules()
    {
        $requiredRule = [
            "nickname" => "required",
        ];
        return $requiredRule;
    }
}
