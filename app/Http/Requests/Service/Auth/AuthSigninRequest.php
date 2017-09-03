<?php

namespace App\Http\Requests\Service\Auth;

use App\Http\Requests\Request;
use App\Models\User;
use App\Traits\AuthorizesRequestsOverLoad;
use Log;

class AuthSigninRequest extends Request
{
    use AuthorizesRequestsOverLoad;

    public function authorize()
    {
        return User::isGhost();
    }

    /**
     *  @SWG\Definition(
     *   definition="auth/signin",
     *   type="object",
     *   allOf={
     *       @SWG\Schema(
     *           required={"email","password"},
     *           @SWG\Property(property="email", type="string", default="test@pixelstairs.com"),
     *           @SWG\Property(property="password", type="string", default="password"),
     *       )
     *   }
     * )
     */
    public function rules()
    {
        $requiredRule = [
            'email' => 'required',
            'password' => 'required',
            // TODO :: sns code later...
        ];
        return $requiredRule;
    }
}
