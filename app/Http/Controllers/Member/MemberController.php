<?php

namespace App\Http\Controllers\Member;

// Global
use Log;
use Abort;
use Carbon\Carbon;

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
        $this->user = User::class;
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
     *      default="wtesttesttesttesttesttesttestte2",
     *      required=true
     *     ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function simpleRetrieve(Request $request){
        $this->user = User::getAccessUser();
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
     *      default="wtesttesttesttesttesttesttestte2",
     *      required=true
     *     ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function getRetrieve(Request $request,$user_id)
    {
        $this->user = User::findOrFail($user_id);
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
     *      default="wtesttesttesttesttesttesttestte2",
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
        $this->user = User::getAccessUser();
        $result = null;
        
//        try {
            $this->user->update([
                "birthday" => Carbon::parse($request->birthday)->timezone(config('app.timezone'))->toDateTimeString(),
                "gender" => $request->gender,
                "nickname" => $request->nickname,
                "image_id" => $this->uploader->upload(
                    $this->user,
                    $request->profileImg
                )->getId(),
                "newsletters_accepted" => $request->newsletterAccepted,
            ]);
            $result = $this->user->getDetailInfo();
//        } catch (\Exception $e){
//            Abort::Error('0040');
//        }
        return response()->success($result);
    }
}
