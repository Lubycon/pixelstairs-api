<?php

namespace App\Http\Requests\Auth\Password;

use App\Http\Requests\Request;
use App\Models\User;
use App\Traits\AuthorizesRequestsOverLoad;
use Illuminate\Support\Facades\Auth;

use Log;

class PasswordChangeRequest extends Request
{
    use AuthorizesRequestsOverLoad;

    public function authorize()
    {
        $user = User::getAccessUser();
        $credentials = [
            'email'    => $user->email,
            'password' => $this->oldPassword
        ];
        return User::isUser() && Auth::once($credentials);
    }

    /**
     *  @SWG\Definition(
     *   definition="password/change",
     *   type="object",
     *   allOf={
     *       @SWG\Schema(
     *           required={"oldPassword","newPassword"},
     *           @SWG\Property(property="oldPassword", type="string", default="12341234"),
     *           @SWG\Property(property="newPassword", type="string", default="12341234"),
     *       )
     *   }
     * )
     */
    public function rules()
    {
        $requiredRule = [
            "oldPassword" => "required",
            "newPassword" => "required",
        ];
        return $requiredRule;
    }
}
