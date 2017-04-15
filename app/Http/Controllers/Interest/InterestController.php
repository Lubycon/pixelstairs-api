<?php

namespace App\Http\Controllers\Interest;

// Global
use Log;
use Abort;

// Models
use App\Models\Content;

// Require
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InterestController extends Controller
{
    public $content;

    public function __construct()
    {
        $this->content = Content::class;
    }

    protected function likePost(Request $request){
    }
    protected function likeDelete(Request $request){
    }
}
