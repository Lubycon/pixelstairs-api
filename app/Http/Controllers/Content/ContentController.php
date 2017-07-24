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
use App\Http\Requests\Image\ImagePostRequest;

// for image test
use Intervention;

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

    /**
     * @SWG\Get(
     *   path="/contents",
     *   summary="contents",
     *   operationId="contents",
     *   tags={"/Contents"},
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function getList(Request $request){
        $this->user = User::getAccessUserOrNot();
        $collection = $this->pager
            ->search(new $this->content,$request->query())
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

    /**
     * @SWG\Get(
     *   path="/contents/{content_id}",
     *   @SWG\Parameter(
     *     name="content_id",
     *     description="ID of content that needs",
     *     in="path",
     *     required=true,
     *     type="string",
     *     default="1",
     *   ),
     *   summary="contents",
     *   operationId="contents",
     *   tags={"/Contents"},
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function get(Request $request,$content_id){
        $this->content = Content::findOrFail($content_id);
        $this->user = User::getAccessUserOrNot();
        $this->content->viewIt($this->user);
        $result = $this->content->getContentInfoWithAuthor($this->user);
        return response()->success($result);
    }

    /**
     * @SWG\Post(
     *   path="/contents",
     *   summary="detail",
     *   operationId="detail",
     *   tags={"/Contents"},
     *     @SWG\Parameter(
     *      type="string",
     *      name="X-pixel-token",
     *      in="header",
     *      default="wtesttesttesttesttesttesttestte2",
     *      required=true
     *     ),
     *     @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     description="Post detail",
     *     required=true,
     *      @SWG\Schema(ref="#/definitions/contents/post")
     *   ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function post(ContentPostRequest $request){
        $this->user = User::getAccessUser();
        //return $request->image;
        try {
            $this->content = $this->user->contents()->create([
                "title" => $request->title,
                "description" => $request->description,
                "license_code" => $request->licenseCode,
                "hash_tags" => json_encode($request->hashTags),
            ]);
            // $this->content->update([
            //     "image_group_id" => $this->uploader->upload(
            //         $this->content,$request->image,true
            //     )->getId(),
            // ]);
        } catch (\Exception $e){
            $this->content->delete();
            Abort::Error('0040');
        }
        return response()->success($this->content);
    }

    protected function uploadImage(Request $request,$content_id){
        $this->content = Content::findOrFail($content_id);
        
        // $this->user = User::getAccessUser();
        try{
            $this->content->update([
                "image_group_id" =>
                    $this->uploader->uploadByFile($this->content,$request->image,true)->getId()
            ]);
        }catch (\Exception $e){
            $this->content->delete();
            Abort::Error('0040');
        }
        return response()->success($this->content);
    }

    /**
     * @SWG\Put(
     *   path="/contents/{content_id}",
     *   @SWG\Parameter(
     *     name="content_id",
     *     description="ID of content that needs",
     *     in="path",
     *     required=true,
     *     type="string",
     *     default="1",
     *   ),
     *   summary="detail",
     *   operationId="detail",
     *   tags={"/Contents"},
     *     @SWG\Parameter(
     *      type="string",
     *      name="X-pixel-token",
     *      in="header",
     *      default="wtesttesttesttesttesttesttestte2",
     *      required=true
     *     ),
     *     @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     description="Post detail",
     *     required=true,
     *      @SWG\Schema(ref="#/definitions/contents/put")
     *   ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function put(ContentPutRequest $request,$content_id){
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
        }catch (\Exception $e){
            $this->content->delete();
            Abort::Error('0040');
        }
        return response()->success($this->content);
    }

    /**
     * @SWG\Delete(
     *   path="/contents/{content_id}",
     *   @SWG\Parameter(
     *     name="content_id",
     *     description="ID of content that needs",
     *     in="path",
     *     required=true,
     *     type="string",
     *     default="1",
     *   ),
     *   summary="detail",
     *   operationId="detail",
     *   tags={"/Contents"},
     *     @SWG\Parameter(
     *      type="string",
     *      name="X-pixel-token",
     *      in="header",
     *      default="wtesttesttesttesttesttesttestte2",
     *      required=true
     *     ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function delete(ContentDeleteRequest $request,$content_id){
        $this->content = Content::findOrFail($content_id);
        $this->content->delete();
        return response()->success(true);
    }
}
