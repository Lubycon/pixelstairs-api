<?php

namespace App\Http\Controllers\Content;

// Global
use Log;
use Abort;

// Models
use App\Models\Content;

// Require
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// Class
use App\Classes\FileUpload;

class ContentController extends Controller
{
    public $content;
    public $uploader;

    public function __construct()
    {
        $this->content = Content::class;
        $this->uploader = new Fileupload();
    }

    protected function getList(Request $request){
    }
    protected function get(Request $request){
    }
    protected function post(Request $request){
    }
    protected function put(Request $request){
    }
    protected function delete(Request $request){
    }
}
