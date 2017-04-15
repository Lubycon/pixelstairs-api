<?php

namespace App\Http\Controllers\Comment;

// Global
use Log;
use Abort;

// Models
use App\Models\Content;
use App\Models\Comment;

// Require
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    public $content;
    public $comment;

    public function __construct()
    {
        $this->content = Content::class;
        $this->comment = Comment::class;
    }

    protected function getList(Request $request){
    }
    protected function post(Request $request){
    }
    protected function put(Request $request){
    }
    protected function delete(Request $request){
    }
}
