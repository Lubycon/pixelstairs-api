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
     *           required={"answerId"},
     *           @SWG\Property(property="answerIds", type="array", default={1,7}),
     *       )
     *   }
     * )
     */
    public function rules()
    {
        $requiredRule = [
            'answerIds' => 'required|array'
        ];

        return $requiredRule;
    }
}
