<?php

namespace App\Http\Controllers\Comment;

// Global
use Log;
use Abort;

// Models
use App\Models\Content;
use App\Models\Comment;
use App\Models\User;

// Require
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// Class
use App\Classes\Pager;

// Request
use App\Http\Requests\Comment\CommentGetListRequest;
use App\Http\Requests\Comment\CommentPostRequest;
use App\Http\Requests\Comment\CommentPutRequest;
use App\Http\Requests\Comment\CommentDeleteRequest;

class CommentController extends Controller
{
    public $user;
    public $content;
    public $comment;
    public $pager;

    public function __construct()
    {
        $this->user = User::class;
        $this->content = Content::class;
        $this->comment = Comment::class;
        $this->pager = new Pager();
    }

    /**
     * @SWG\Get(
     *   path="/contents/1/comments",
     *   summary="contents",
     *   operationId="contents",
     *   tags={"/Contents/Comment"},
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function getList(CommentGetListRequest $request,$content_id){
        $query = "search:contentId:$content_id";
        $collection = $this->pager
            ->search('comment',$query)
            ->getCollection();
        $result = $this->pager->getPageInfo();
        foreach($collection as $comment){
            $result->comments[] = $comment->getCommentWithAuthor();
        };
        if(!empty($result->comments)){
            return response()->success($result);
        }else{
            return response()->success();
        }
    }

    /**
     * @SWG\Post(
     *   path="/contents/1/comments",
     *   summary="detail",
     *   operationId="detail",
     *   tags={"/Contents/Comment"},
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
     *      @SWG\Schema(ref="#/definitions/comments/post")
     *   ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function post(CommentPostRequest $request,$content_id){
        $this->user = User::getAccessUser();
        $this->content = Content::findOrFail($content_id);
        $this->comment = $this->content->comments()->create([
            "user_id" => $this->user->id,
            "description" => $request->description,
        ]);
        if($this->comment){
            return response()->success($this->comment);
        }
        Abort::Error('0040');
    }

    /**
     * @SWG\Put(
     *   path="/contents/1/comments/1",
     *   summary="detail",
     *   operationId="detail",
     *   tags={"/Contents/Comment"},
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
     *      @SWG\Schema(ref="#/definitions/comments/put")
     *   ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function put(CommentPutRequest $request,$content_id,$comment_id){
        $this->content = Content::findOrFail($content_id);
        $this->comment = $this->content->comments()->findOrFail($comment_id);
        $this->comment->update(["description" => $request->description,]);
        if($this->comment) return response()->success($this->comment);
        Abort::Error('0040');
    }

    /**
     * @SWG\Delete(
     *   path="/contents/1/comments/1",
     *   summary="detail",
     *   operationId="detail",
     *   tags={"/Contents/Comment"},
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
    protected function delete(CommentDeleteRequest $request,$content_id,$comment_id){
        $this->content = Content::findOrFail($content_id);
        $this->comment = $this->content->comments()->findOrFail($comment_id);
        if($this->comment->delete()) return response()->success($this->comment);
        Abort::Error('0040');
    }
}
