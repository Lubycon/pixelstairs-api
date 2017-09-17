<?php

namespace App\Http\Requests\Service\Member;

use App\Http\Requests\Request;
use App\Models\User;
use App\Traits\AuthorizesRequestsOverLoad;

use Log;
use Auth;

class MemberPutRetrieveRequest extends Request
{
    use AuthorizesRequestsOverLoad;

    public function authorize()
    {
        return User::isUser() && User::isActive();
    }

    /**
     *  @SWG\Definition(
     *   definition="members/putRetrieve",
     *   type="object",
     *   allOf={
     *       @SWG\Schema(
     *           required={"newsletterAccepted","nickname","profileImg"},
     *           @SWG\Property(property="newsletterAccepted", type="string", default=true),
     *           @SWG\Property(property="nickname", type="string", default="user_nickname")
     *       )
     *   }
     * )
     */
    public function rules()
    {
        // If same before nickname and new nickname
        // Do not check unique nickname in database
        $nicknameRule = Auth::user()->nickname === app('request')->nickname
            ? "required"
            : "required|availableNickname";

        $requiredRule = [
            "nickname" => $nicknameRule,
            "profileImg" => "array",
        ];
        return $requiredRule;
    }
}
