<?php

namespace App\Http\Controllers\Content;

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

class ContentController extends Controller
{
    public $content;
    public $user;
    public $uploader;
    public $pager;

    public function __construct()
    {
        $this->content = Content::class;
        $this->user = User::class;
        $this->uploader = new FileUpload();
        $this->pager = new Pager();
    }

    protected function getList(Request $request){
    }
    protected function get(Request $request){
    }
    protected function post(Request $request){
        $this->user = User::getAccessUser();
        try{
            $this->content = $this->user->contents()->create([
                "title" => $request->title,
                "description" => $request->description,
                "licence_code" => $request->licenseCode,
                "hash_tags" => json_encode($request->hashTags),
            ]);
            $this->content->update([
                "thumbnail_image_id" => $this->uploader->upload(
                    $this->content,
                    $request->thumbnailImg
                )->getId(),
                "image_group_id" => $this->uploader->upload(
                    $this->content,
                    $request->images
                )->getId(),
            ]);
        }catch (\Exception $e){
            $this->content->delete();
            Abort::Error('0040');
        }
        return response()->success($this->content);
    }
    protected function put(Request $request){
    }
    protected function delete(Request $request){
    }
}
