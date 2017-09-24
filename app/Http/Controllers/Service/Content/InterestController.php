<?php

namespace App\Http\Controllers\Service\Content;

// Global
use Log;
use Abort;
use Auth;

// Models
use App\Models\User;
use App\Models\Content;
use App\Models\Like;

// Require
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// Request
use App\Http\Requests\Service\Content\Interest\ContentInterestPostLikeRequest;
use App\Http\Requests\Service\Content\Interest\ContentInterestDeleteLikeRequest;

class InterestController extends Controller
{
    public $content;
    public $user;
    public $like;

    public function __construct()
    {
        $this->user = Auth::user();
        $this->content = Content::class;
        $this->like = Like::class;
    }

    /**
     * @SWG\Post(
     *   path="/contents/{content_id}/like",
     *   @SWG\Parameter(
     *     name="content_id",
     *     description="ID of content that needs",
     *     in="path",
     *     required=true,
     *     type="string",
     *     default="1",
     *   ),
     *   summary="detail",
     *   operationId="detail",
     *   tags={"/Contents/like"},
     *     @SWG\Parameter(
     *      type="string",
     *      name="Authorization",
     *      in="header",
     *      default="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIyIiwiaXNzIjoiaHR0cDovL2FwaWxvY2FsLnBpeGVsc3RhaXJzLmNvbTo4MDgwL3YxL21lbWJlcnMvc2lnbmluIiwiaWF0IjoxNTA2MjQyNzU2LCJleHAiOjI0OTc3OTA1MTcwMTA5ODg3NTYsIm5iZiI6MTUwNjI0Mjc1NiwianRpIjoiNGFGVDV5VEtlaTdiVDVtWiJ9.AcYrVZBkvIepPi66IGUG0RMHDiv2b93kEEet3Ie0loU",
     *      required=true
     *     ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function postLike(ContentInterestPostLikeRequest $request,$content_id){
        $this->content = Content::findOrFail($content_id);
        $this->content->likeIt($this->user);
        return response()->success();
    }

    /**
     * @SWG\Delete(
     *   path="/contents/{content_id}/like",
     *   @SWG\Parameter(
     *     name="content_id",
     *     description="ID of content that needs",
     *     in="path",
     *     required=true,
     *     type="string",
     *     default="1",
     *   ),
     *   summary="detail",
     *   operationId="detail",
     *   tags={"/Contents/like"},
     *     @SWG\Parameter(
     *      type="string",
     *      name="Authorization",
     *      in="header",
     *      default="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIyIiwiaXNzIjoiaHR0cDovL2FwaWxvY2FsLnBpeGVsc3RhaXJzLmNvbTo4MDgwL3YxL21lbWJlcnMvc2lnbmluIiwiaWF0IjoxNTA2MjQyNzU2LCJleHAiOjI0OTc3OTA1MTcwMTA5ODg3NTYsIm5iZiI6MTUwNjI0Mjc1NiwianRpIjoiNGFGVDV5VEtlaTdiVDVtWiJ9.AcYrVZBkvIepPi66IGUG0RMHDiv2b93kEEet3Ie0loU",
     *      required=true
     *     ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function deleteLike(ContentInterestDeleteLikeRequest $request,$content_id){
        $this->content = Content::findOrFail($content_id);
        $this->content->dislikeIt($this->user);
        return response()->success();
    }
}
