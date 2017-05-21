<?php

namespace App\Http\Controllers\Member;

// Global
use Log;
use Abort;

// Models
use App\Models\User;

// Require
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// Class
use App\Classes\FileUpload;

// Requests
use App\Http\Requests\Member\MemberPostRetrieveRequest;

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
     *   path="/members/2/detail",
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
        return response()->success([
            "id" => $this->user->id,
            "email" => $this->user->email,
            "nickname" => $this->user->nickname,
            "profileImg" => $this->user->getImageObject(),
            "gender" => $this->user->gender,
            "newsletterAccepted" => $this->user->newsletters_accepted,
        ]);
    }

    /**
     * @SWG\Post(
     *   path="/members/2/detail",
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
     *     description="Post detail",
     *     required=true,
     *      @SWG\Schema(ref="#/definitions/members/postRetrieve")
     *   ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    public function postRetrieve(MemberPostRetrieveRequest $request)
    {
        $this->user = User::getAccessUser();
        try{
            $this->user->update([
                "nickname" => $request->nickname,
                "image_id" => $this->uploader->upload(
                    $this->user,
                    $request->profileImg
                )->getId(),
                "newsletters_accepted" => $request->newsletterAccepted,
            ]);
        }catch (\Exception $e){
            Abort::Error('0040');
        }
        return response()->success($this->user);
    }
}
