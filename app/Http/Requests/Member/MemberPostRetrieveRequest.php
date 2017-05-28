<?php

namespace App\Http\Requests\Member;

use App\Http\Requests\Request;
use App\Models\User;
use App\Traits\AuthorizesRequestsOverLoad;

use Log;

class MemberPostRetrieveRequest extends Request
{
    use AuthorizesRequestsOverLoad;

    public function authorize()
    {
        $user_id = $this->route()->parameters()['id'];
        User::isMyId($user_id);
        return User::isUser();
    }


    /**
     *  @SWG\Definition(
     *   definition="members/postRetrieve",
     *   type="object",
     *   allOf={
     *       @SWG\Schema(
     *           required={"newsletterAccepted"},
     *           @SWG\Property(property="newsletterAccepted", type="string", default=true),
     *           @SWG\Property(property="nickname", type="string", default="user_nickname")
     *       )
     *   }
     * )
     */
    public function rules()
    {
        $requiredRule = [
            "newsletterAccepted" => "required|boolean",
            "nickname" => "string",
        ];
        return $requiredRule;
    }
}
