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
use App\Classes\Pager\Pager;

// Request

class AdminContentController extends Controller {
    public $content;
    public $user;
    public $uploader;

    public function __construct() {
        $this->content = Content::class;
        $this->user = User::class;
        $this->uploader = new FileUpload;
    }

    protected function getList(Request $request) {
        $modeling = new Pager( Content::with(['user','user.image','imageGroup','image']) );
        $collection = $modeling
            ->setQueryObject($request->query())
            ->setQuery()
            ->withTrashed()
            ->getCollection();
        $result = $modeling->getPageInfo();
        foreach($collection as $content){
            $result->contents[] = $content->getContentInfoWithAuthorByAdmin();
        };

        if(!empty($result->contents)) {
            return response()->success($result);
        } else{
            return response()->success();
        }
    }

    protected function get(Request $request, $content_id) {
        $this->content = Content::withTrashed()->findOrFail($content_id);
        $result = $this->content->getContentInfoWithAuthorByAdmin();
        return response()->success($result);
    }

    protected function put(Request $request, $content_id){
        $this->content = Content::withTrashed()->findOrFail($content_id);
        try{
            $this->content->update([
                "title" => $request->title,
                "description" => $request->description,
                "license_code" => $request->licenseCode,
                "hash_tags" => json_encode($request->hashTags)
            ]);
        } catch (\Exception $e){
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
