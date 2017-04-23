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

// Request
use App\Http\Requests\Content\ContentDeleteRequest;
use App\Http\Requests\Content\ContentPutRequest;
use App\Http\Requests\Content\ContentPostRequest;

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
        $this->user = User::getAccessUserOrNot();
        $collection = $this->pager
            ->search('content',$request->query())
            ->getCollection();
        $result = $this->pager->getPageInfo();
        foreach($collection as $content){
            $result->contents[] = $content->getContentInfoWithAuthor($this->user);
        };

        if(!empty($result->contents)){
            return response()->success($result);
        }else{
            return response()->success();
        }
    }
    protected function get(Request $request,$content_id){
        $this->content = Content::findOrFail($content_id);
        $this->user = User::getAccessUserOrNot();
        $result = $this->content->getContentInfoWithAuthor($this->user);
        if( !is_null($this->user) ){
            $this->content->viewIt($this->user);
        }
        return response()->success($result);
    }
    protected function post(ContentPostRequest $request){
        $this->user = User::getAccessUser();

        try{
            $this->content = $this->user->contents()->create([
                "title" => $request->title,
                "description" => $request->description,
                "licence_code" => '11',
                "hash_tags" => json_encode($request->hashTags),
            ]);
            $this->content->update([
                "image_group_id" => $this->uploader->upload(
                    $this->content,$request->image,true
                )->getId(),
            ]);
        }catch (\Exception $e){
            $this->content->delete();
            Abort::Error('0040');
        }
        return response()->success($this->content);
    }
    protected function put(ContentPutRequest $request,$content_id){
        $this->content = Content::findOrFail($content_id);
        try{
            $this->content->update([
                "title" => $request->title,
                "description" => $request->description,
                "licence_code" => $request->licenseCode,
                "hash_tags" => json_encode($request->hashTags),
                "image_group_id" => $this->uploader->upload(
                    $this->content,$request->image,true
                )->getId(),
            ]);
        }catch (\Exception $e){
            $this->content->delete();
            Abort::Error('0040');
        }
        return response()->success($this->content);
    }
    protected function delete(ContentDeleteRequest $request,$content_id){
        $this->content = Content::findOrFail($content_id);
        $this->content->delete();
        return response()->success(true);
    }
}
