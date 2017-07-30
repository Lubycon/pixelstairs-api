<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;
use App\Models\User;
use App\Traits\AuthorizesRequestsOverLoad;

class AuthSigndropRequest extends Request
{
    use AuthorizesRequestsOverLoad;

    public function authorize()
    {
        return User::isUser();
    }

    /**
     *  @SWG\Definition(
     *   definition="auth/signdrop",
     *   type="object",
     *   allOf={
     *       @SWG\Schema(
     *           required={"reasonCode"},
     *           @SWG\Property(property="reasonCode", type="string", default="0303"),
     *           @SWG\Property(property="reason", type="string", default="이게뭐냐 맘에안듬~~"),
     *       )
     *   }
     * )
     */
    public function rules()
    {
        $requiredRule = [
            'reason' => 'required'
        ];

        return $requiredRule;
    }
}
