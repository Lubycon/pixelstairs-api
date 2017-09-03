<?php

namespace App\Http\Requests\Service\Cert;

use App\Http\Requests\Request;
use App\Models\User;
use App\Traits\AuthorizesRequestsOverLoad;

use Log;

class CertCheckCodeRequest extends Request
{
    use AuthorizesRequestsOverLoad;

    public function authorize()
    {
        $result = true;
        if( User::isUser() ) $result = User::isInactive();
        return $result;
    }

    /**
     *  @SWG\Definition(
     *   definition="certs/signup/checkCode",
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
            "code" => "required|string",
        ];
        return $requiredRule;
    }
}
