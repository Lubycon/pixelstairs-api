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

    protected function postLike(Request $request,$content_id){
        $this->content = Content::findOrFail($content_id);
        $this->user = User::getAccessUser();
        $this->content->likeIt($this->user);
        return response()->success();
    }
    protected function deleteLike(Request $request,$content_id){
        $this->content = Content::findOrFail($content_id);
        $this->user = User::getAccessUser();
        $this->content->dislikeIt($this->user);
        return response()->success();
    }
}
