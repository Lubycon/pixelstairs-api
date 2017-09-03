<?php

namespace App\Http\Controllers\Member;

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
use App\Http\Requests\Member\MemberPutRetrieveRequest;

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
     *   path="/members/simple",
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
    protected function simpleRetrieve(Request $request){
        $result = $this->user->getSimpleInfo();
        return response()->success($result);
    }

    /**
     * @SWG\Get(
     *   path="/members/{member_id}/detail",
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
    protected function getRetrieve(Request $request,$user_id)
    {
        $result = $this->user->getDetailInfo();
        return response()->success($result);
    }

    /**
     * @SWG\Put(
     *   path="/members/{member_id}/detail",
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
    public function putRetrieve(MemberPutRetrieveRequest $request)
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
        $result = $this->user->getDetailInfo();
        return response()->success($result);
    }
}
