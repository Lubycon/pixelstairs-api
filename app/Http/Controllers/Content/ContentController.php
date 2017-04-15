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
        $collection = $this->pager
                        ->search('content',$request->query())
                        ->getCollection();

        $result = (object)array(
            "totalCount" => $this->pager->totalCount,
            "currentPage" => $this->pager->currentPage,
        );

//1	컨텐츠 정보	content	object	O
//1-1	코드	id	int	O
//1-2	제목	title	string	O
//1-3	설명	description	string	O
//1-4	이미지	thumbnailImg	file object	O
//1-5	라이센스	licenseCode	string	O
//1-6	라이크 여부	myLike	boolean	O
//1-7	컨텐츠 카운트 정보	counts	object	O
//1-7-1	라이크 카운트	like	int	O
//1-7-2	뷰 카운트	view	int	O
//1-8	해쉬태그 정보	hashTags	array -> string	O
//2	멤버데이터	user	object	O
//2-1	코드	id	string	O
//2-2	이름	nickname	string	O
//2-3	프로필사진	profileImg	file object	O

        foreach($collection as $content){
            $result->contents[] = (object)array(
                "id" => $content->id,
                "title" => $content->title,
                "description" => $content->description,
                "thumbnailImg" => '',
                "licenseCode" => $content->licence_code,
                "myLike" => "",
                "counts" => $content->getCounts(),
                "hashTags" => $content->getHashTags(),
                "user" => $content->user->getSimpleInfo(),
            );
        };

        if(!empty($result->contents)){
            return response()->success($result);
        }else{
            return response()->success();
        }
    }
    protected function get(Request $request,$content_id){
        $this->content = Content::findOrFail($content_id);
        $result = [
            "id" => $this->content->id,
            "title" => $this->content->title,
            "description" => $this->content->description,
            "thumbnailImg" => '',
            "licenseCode" => $this->content->licence_code,
            "myLike" => "",
            "counts" => $this->content->getCounts(),
            "hashTags" => $this->content->getHashTags(),
            "user" => $this->content->user->getSimpleInfo(),
        ];
        return response()->success($result);
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
    protected function put(Request $request,$content_id){
        $this->content = Content::findOrFail($content_id);
        try{
            $this->content->update([
                "title" => $request->title,
                "description" => $request->description,
                "licence_code" => $request->licenseCode,
                "hash_tags" => json_encode($request->hashTags),
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
    protected function delete(Request $request,$content_id){
        $this->content = Content::findOrFail($content_id);
        $this->content->delete();
        return response()->success(true);
    }
}
