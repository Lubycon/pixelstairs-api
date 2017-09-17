<?php

namespace App\Http\Controllers\Service\Member;

// Global
use Log;
use Abort;
use Carbon\Carbon;
use Auth;

// Models
use App\Models\User;

// Require
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// Class
use App\Classes\FileUpload;

// Requests
use App\Http\Requests\Service\Member\MemberPutRetrieveRequest;

class MemberController extends Controller
{
    public $user;
    public $uploader;

    public function __construct()
    {
        $this->user = Auth::user();
        $this->uploader = new FileUpload();
    }

    /**
     * @SWG\Get(
     *   path="/members/me",
     *   summary="simple",
     *   operationId="simple",
     *   tags={"/Members/User"},
     *     @SWG\Parameter(
     *      type="string",
     *      name="X-pixel-token",
     *      in="header",
     *      default="wQWERQWERQWERQWERQWERQWERQWERQWERQWERQWERQWERQWERQW2",
     *      required=true
     *     ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function getMyRetrieve(Request $request){
        $result = $this->user->getMyInfo();
        return response()->success($result);
    }

    /**
     * @SWG\Put(
     *   path="/members/me",
     *   summary="detail",
     *   operationId="detail",
     *   tags={"/Members/User"},
     *     @SWG\Parameter(
     *      type="string",
     *      name="X-pixel-token",
     *      in="header",
     *      default="wQWERQWERQWERQWERQWERQWERQWERQWERQWERQWERQWERQWERQW2",
     *      required=true
     *     ),
     *     @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     description="Put detail",
     *     required=true,
     *      @SWG\Schema(ref="#/definitions/members/putRetrieve")
     *   ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    public function putMyRetrieve(MemberPutRetrieveRequest $request)
    {
        $result = null;
        $this->user->update([
            "nickname" => $request->nickname,
            "image_id" => $this->uploader->upload(
                $this->user,
                $request->profileImg
            )->getId(),
            "newsletters_accepted" => $request->newsletterAccepted,
        ]);
        $result = $this->user->getMyInfo();
        return response()->success($result);
    }


    /**
     * @SWG\Get(
     *   path="/members/{member_id}",
     *   @SWG\Parameter(
     *     name="member_id",
     *     description="ID of member that needs",
     *     in="path",
     *     required=true,
     *     type="string",
     *     default="2",
     *   ),
     *   summary="detail",
     *   operationId="detail",
     *   tags={"/Members/User"},
     *     @SWG\Parameter(
     *      type="string",
     *      name="X-pixel-token",
     *      in="header",
     *      default="wQWERQWERQWERQWERQWERQWERQWERQWERQWERQWERQWERQWERQW2",
     *      required=true
     *     ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function getPublicRetrieve(Request $request, $user_id)
    {
        $this->user = User::findOrFail($user_id);
        $result = $this->user->getPublicUserInfo();
        return response()->success($result);
    }
}
