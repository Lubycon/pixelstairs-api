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
    protected function put(CommentPutRequest $request,$content_id,$comment_id){
        $this->content = Content::findOrFail($content_id);
        $this->comment = $this->content->comments()->findOrFail($comment_id);
        $this->comment->update(["description" => $request->description,]);
        if($this->comment) return response()->success($this->comment);
        Abort::Error('0040');
    }
    protected function delete(CommentDeleteRequest $request,$content_id,$comment_id){
        $this->content = Content::findOrFail($content_id);
        $this->comment = $this->content->comments()->findOrFail($comment_id);
        if($this->comment->delete()) return response()->success($this->comment);
        Abort::Error('0040');
    }
}
