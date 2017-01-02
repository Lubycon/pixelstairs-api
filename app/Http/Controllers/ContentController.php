<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Content;
use App\Models\Comment;
use App\Models\Board;
use App\Models\User;
use App\Models\ContentTag;
use File;

use Abort;

use App\Traits\InsertArrayToColumn;
use App\Traits\GetUserModelTrait;
use App\Traits\ConvertData;

use Carbon\Carbon;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\CheckContoller;
use App\Http\Controllers\Pager\PageController;

use App\Http\Requests\Content\ContentUploadRequest;

use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\UserActionRecodeJob;

use Log;

class ContentController extends Controller
{
    use InsertArrayToColumn,
        GetUserModelTrait,
        ConvertData;

    public function store(ContentUploadRequest $request,$category){
        $data = $request->json()->all();

        $getToken = $this->getUserToken($request);
        $findUser = $this->getUserByToken($getToken);
        $contents = new Content;

        // for upload data... take code SSARU!
        // $attachedFiles = $data['attachedFiles'];
        // $attachedImg = $data['attachedImg'];
        // $userContent = $data['content'];
        // $contentType = $data['content']['type'] == '0' ? '2d' : '3d';
        // $content2dData = $data['content']['data'];
        $rand_string = str_random(10);
        mkdir(public_path().'/datas/'.$rand_string,0777);
        mkdir(public_path().'/datas/'.$rand_string.'/json',0777);
        $map = File::put(public_path().'/datas/'.$rand_string.'/json/map.json',json_encode($data['content']['data']['map']));
        $model = File::put(public_path().'/datas/'.$rand_string.'/json/model.json',json_encode($data['content']['data']['model']));
        $lights = File::put(public_path().'/datas/'.$rand_string.'/json/lights.json',json_encode($data['content']['data']['lights']));

        $contents->board_id = Board::where('name','=',$category)->value('id');
        $contents->user_id = $findUser->id;
        $contents->license_id = $this->convertLicenseCodeToId($data['setting']['license']);

        $contents->title = $data['setting']['title'];
        $contents->description = $data['setting']['description'];
        $contents->directory = public_path().'/datas/'.$rand_string; //needs s3! go SSARUSSARU
        $contents->is_download = isset($attachedFiles) ? true : false ;

        $contents->save(); //first, contents save

        $tagRender = $this->InsertContentTagName($data['setting']['tags']);
        $contents->tag()->saveMany($tagRender); //second, tags save relationship

        $category = $this->InsertContentCategoryId($data['setting']['category']);
        $contents->categoryKernel()->saveMany($category); //thrid, categorys save relationship

        if($contents->save()){ //check right access
          return response()->success();
        };
        Abort::Error('0040');
    }
    public function getList(Request $request,$category){
        $query = $request->query();
        $controller = new PageController($category,$query);
        $collection = $controller->getCollection();

        $result = (object)array(
            "totalCount" => $controller->totalCount,
            "currentPage" => $controller->currentPage,
            "contents" => []
        );
        foreach($collection as $array){
            $result->contents[] = (object)array(
                "contentData" => (object)array(
                     "id" => $array->id,
                     "title" => $array->title,
                     "category" => Board::find($array->board_id)->name,
                     "image" => "http://lorempixel.com/640/480/", //need edit
                     "license" => $array->license,
                     "bookmark" => false,
                     "like" => $array->like_count,
                     "view" => $array->view_count,
                     "comment" => $array->comment_count,
                     "download" => $array->download_count,
                     "date" => Carbon::instance($array->created_at)->toDateTimeString(),
                ),
                "userData" => (object)array(
                    "id" => $array->user->id,
                    "nickname" => $array->user->nickname,
                    "profile" => $array->user->profile_img
                )
            );
        };

        if(!empty($result->contents)){
            return response()->success($result);
        }else{
            return response()->success();
        }
    }
    public function viewData($category,$board_id){
        $content = Content::findOrFail($board_id);
        return response()->success([
            "contents" => (object)array(
                "2d" => (object)array(
                    "content" => $content->content
                ),
                "3d" => (object)array(
                    "map" => json_decode(File::get($content->directory.'/json/map.json')),
                    "model" => json_decode(File::get($content->directory.'/json/model.json')),
                    "lights" => json_decode(File::get($content->directory.'/json/lights.json')),
                )
            )
        ]);
    }
    public function viewPost(Request $request,$category,$board_id){
         $content = Content::findOrFail($board_id);

         $this->dispatch(new UserActionRecodeJob($request,'view',$content));

         return response()->success([
             "contents" => (object)array(
                 "id" => $content->id,
                 "title" => $content->title,
                 "subCategory" => $this->convertContentCategoryIdToName($content->categoryKernel),
                 "description" => $content->description,
                 "date" => Carbon::instance($content->created_at)->toDateTimeString(),
                 "bookmark" => false,
                 "like" => false,
                 "likeCount" => $content->like_count,
                 "viewCount" => $content->view_count,
                 "downloadCount" => $content->view_count,
                 "filePath" => $content->filePath,
                 "license" => $content->license,
                 "tags" => $content->tag->lists('name')->toArray()
             ),
             "userData" => (object)array(
                 "id" => $content->user_id,
                 "nickname" => $content->user->nickname,
                 "profile" => $content->user->profile_img,
                 "job" => is_null($content->user->job) ? null : $content->user->name,
                 "country" => is_null($content->user->country) ? null : $content->user->country->name,
                 "city" => $content->user->city
             )
         ]);
    }
    public function update(ContentUploadRequest $request,$category,$board_id){
        $data = $request->json()->all();

        $tokenData = CheckContoller::checkToken($request);
        $findUser = User::findOrFail($tokenData->id);
        $contents = Content::findOrFail($board_id);

        if($this->isSameBoard($contents,$category)){
            Abort::Error('0043');
        }
        if($this->isSameUser($findUser,$contents)){
            Abort::Error('0043');
        }

        $contents->tag()->delete();
        $tagRender = $this->InsertContentTagName($data['setting']['tags']);
        $contents->tag()->saveMany($tagRender); //second, tags save relationship

        $contents->categoryKernel()->delete();
        $category = $this->InsertContentCategoryId($data['setting']['category']);
        $contents->categoryKernel()->saveMany($category); //thrid, categorys save relationship

        if($contents->save()){
          return response()->success();
        };

        Abort::Error('0040');
    }
    public function delete(Request $request,$category,$board_id){
        $tokenData = CheckContoller::checkToken($request);
        $findUser = User::findOrFail($tokenData->id);
        $contents = Content::findOrFail($board_id);

        if($this->isSameBoard($contents,$category)){
            Abort::Error('0043');
        }
        if($this->isSameUser($findUser,$contents)){
            Abort::Error('0043');
        }
        $contents->categoryKernel()->delete();
        $contents->tag()->delete();
        if($contents->delete()){
          return response()->success();
        }
    }

    private function isSameUser($findUser,$contents){
        return $findUser->id != $contents->user_id;
    }
    private function isSameBoard($contents,$category){
        return $contents->board_id != Board::where('name','=',$category)->value('id');
    }
}
