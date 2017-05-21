<?php

namespace App\Http\Controllers\Content;

// Global
use Log;
use Abort;

// Models
use App\Models\User;
use App\Models\Content;
use App\Models\Like;

// Require
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// Request
use App\Http\Requests\Content\Interest\ContentInterestPostLikeRequest;
use App\Http\Requests\Content\Interest\ContentInterestDeleteLikeRequest;

class InterestController extends Controller
{
    public $content;
    public $user;
    public $like;

    public function __construct()
    {
        $this->content = Content::class;
        $this->user = User::class;
        $this->like = Like::class;
    }

    /**
     * @SWG\Post(
     *   path="/contents/1/like",
     *   summary="detail",
     *   operationId="detail",
     *   tags={"/Contents/like"},
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
    protected function postLike(ContentInterestPostLikeRequest $request,$content_id){
        $this->content = Content::findOrFail($content_id);
        $this->user = User::getAccessUser();
        $this->content->likeIt($this->user);
        return response()->success();
    }

    /**
     * @SWG\Delete(
     *   path="/contents/1/like",
     *   summary="detail",
     *   operationId="detail",
     *   tags={"/Contents/like"},
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
    protected function deleteLike(ContentInterestDeleteLikeRequest $request,$content_id){
        $this->content = Content::findOrFail($content_id);
        $this->user = User::getAccessUser();
        $this->content->dislikeIt($this->user);
        return response()->success();
    }
}
