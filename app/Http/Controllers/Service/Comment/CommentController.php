<?php

namespace App\Http\Controllers\Comment\Service;

// Global
use Log;
use Abort;
use Auth;

// Models
use App\Models\Content;
use App\Models\Comment;
use App\Models\User;

// Require
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// Class
use App\Classes\Pager\Pager;

// Request
use App\Http\Requests\Service\Comment\CommentGetListRequest;
use App\Http\Requests\Service\Comment\CommentPostRequest;
use App\Http\Requests\Service\Comment\CommentPutRequest;
use App\Http\Requests\Service\Comment\CommentDeleteRequest;

class CommentController extends Controller
{
    public $user;
    public $content;
    public $comment;

    public function __construct()
    {
        $this->user = Auth::user();
        $this->content = Content::class;
        $this->comment = Comment::class;
    }

    /**
     * @SWG\Get(
     *   path="/contents/{content_id}/comments",
     *   @SWG\Parameter(
     *     name="content_id",
     *     description="ID of content that needs",
     *     in="path",
     *     required=true,
     *     type="string",
     *     default="1",
     *   ),
     *   summary="contents",
     *   operationId="contents",
     *   tags={"/Contents/Comment"},
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function getList(CommentGetListRequest $request,$content_id){
        // TODO :: inject pager
//        $query = [
//            "filter" => "contentId:$content_id"
//        ];
//        $collection = $this->pager
//            ->search(new $this->comment,$query)
//            ->getCollection();
//        $result = $this->pager->getPageInfo();
//        foreach($collection as $comment){
//            $result->comments[] = $comment->getCommentWithAuthor();
//        };
//        if(!empty($result->comments)){
//            return response()->success($result);
//        }else{
//            return response()->success();
//        }
    }

    /**
     * @SWG\Post(
     *   path="/contents/{content_id}/comments",
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
     *   tags={"/Contents/Comment"},
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
     *     description="Post detail",
     *     required=true,
     *      @SWG\Schema(ref="#/definitions/comments/post")
     *   ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function post(CommentPostRequest $request,$content_id){
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
     *   path="/contents/{content_id}/comments/{comment_id}",
     *   @SWG\Parameter(
     *     name="content_id",
     *     description="ID of content that needs",
     *     in="path",
     *     required=true,
     *     type="string",
     *     default="1",
     *   ),
     *   @SWG\Parameter(
     *     name="comment_id",
     *     description="ID of comment that needs",
     *     in="path",
     *     required=true,
     *     type="string",
     *     default="1",
     *   ),
     *   summary="detail",
     *   operationId="detail",
     *   tags={"/Contents/Comment"},
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
        return Abort::Error('0040');
    }

    /**
     * @SWG\Delete(
     *   path="/contents/{content_id}/comments/{comment_id}",
     *   @SWG\Parameter(
     *     name="content_id",
     *     description="ID of content that needs",
     *     in="path",
     *     required=true,
     *     type="string",
     *     default="1",
     *   ),
     *   @SWG\Parameter(
     *     name="comment_id",
     *     description="ID of comment that needs",
     *     in="path",
     *     required=true,
     *     type="string",
     *     default="1",
     *   ),
     *   summary="detail",
     *   operationId="detail",
     *   tags={"/Contents/Comment"},
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
    protected function delete(CommentDeleteRequest $request,$content_id,$comment_id){
        $this->content = Content::findOrFail($content_id);
        $this->comment = $this->content->comments()->findOrFail($comment_id);
        if($this->comment->delete()) return response()->success($this->comment);
        return Abort::Error('0040');
    }
}
