<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;
use App\Models\User;
use App\Traits\AuthorizesRequestsOverLoad;

use Log;

class AuthSignupRequest extends Request
{
    use AuthorizesRequestsOverLoad;

    public function authorize()
    {
        return User::isGhost();
    }

    /**
     *  @SWG\Definition(
     *   definition="auth/signup",
     *   type="object",
     *   allOf={
     *       @SWG\Schema(
     *           required={"email","password","nickname","newsletterAccepted","termsOfServiceAccepted"},
     *           @SWG\Property(property="email", type="string", default="test@pixelstairs.com"),
     *           @SWG\Property(property="password", type="string", default="password"),
     *           @SWG\Property(property="nickname", type="string", default="usernick"),
     *           @SWG\Property(property="newsletterAccepted", type="boolean"),
     *           @SWG\Property(property="termsOfServiceAccepted", type="boolean"),
     *       )
     *   }
     * )
     */
    public function rules()
    {
        $requiredRule = [
            "email" => "required|unique:users,email|email",
            "nickname" => "required|unique:users,nickname",
            "password" => "required|string",
            "birthday" => "required",
            "newsletterAccepted" => "required|boolean",
            "termsOfServiceAccepted" => "required|boolean"
        ];
        return $requiredRule;
    }
}
