<?php

namespace App\Http\Requests\Member;

use App\Http\Requests\Request;
use App\Models\User;
use App\Traits\AuthorizesRequestsOverLoad;

use Log;

class MemberPutRetrieveRequest extends Request
{
    use AuthorizesRequestsOverLoad;

    public function authorize()
    {
        User::isActive();
        $user_id = $this->route()->parameters()['id'];
        $this->isMyId = User::isMyId($user_id);
        return User::isUser();
    }

    /**
     *  @SWG\Definition(
     *   definition="members/putRetrieve",
     *   type="object",
     *   allOf={
     *       @SWG\Schema(
     *           required={"birthday","gender","newsletterAccepted","nickname","profileImg"},
     *           @SWG\Property(property="birthday", type="string", default="2017-05-28T22:59:53.000Z"),
     *           @SWG\Property(property="gender", type="string", default="male"),
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
        $nicknameRule = User::getAccessUser()->nickname === app('request')->nickname
            ? "required"
            : "required|unique:users,nickname";

        $requiredRule = [
            "birthday" => "required",
            "gender" => "required", // enum
//            "newsletterAccepted" => "required|boolean",
            "nickname" => $nicknameRule,
            "profileImg" => "array",
//            "profileImg.file" => "required"
        ];
        return $requiredRule;
    }
}
