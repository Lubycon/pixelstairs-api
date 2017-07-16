<?php

namespace App\Http\Controllers\Admin\Content;

// Global
use Log;
use Abort;

// Models
use App\Models\Content;
use App\Models\User;

// Require
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// Class
use App\Classes\FileUpload;
use App\Classes\Pager;

// Request
use App\Http\Requests\Content\ContentDeleteRequest;
use App\Http\Requests\Content\ContentPutRequest;
use App\Http\Requests\Content\ContentPostRequest;

// for image test
use Intervention;

class AdminContentController extends Controller {
    public $content;
    public $user;
    public $uploader;
    public $pager;

    public function __construct() {
        $this->content = Content::class;
        $this->user = User::class;
        $this->uploader = new FileUpload;
        $this->pager = new Pager;
    }

    protected function put(Request $request, $content_id){
        $this->content = Content::findOrFail($content_id);
        try{
            $this->content->update([
                "title" => $request->title,
                "description" => $request->description,
                "license_code" => $request->licenseCode,
                "hash_tags" => json_encode($request->hashTags),
                "image_group_id" => $this->uploader->upload(
                    $this->content,$request->image,true
                )->getId(),
            ]);
        } catch (\Exception $e){
            $this->content->delete();
            Abort::Error('0040');
        }
        return response()->success($this->content);
    }

    protected function delete(Request $request, $content_id){
        $this->content = Content::findOrFail($content_id);
        $this->content->delete();
        return response()->success(true);
    }
}
