<?php

namespace App\Http\Controllers\Service\Content;

// Global
use Faker\Provider\File;
use Log;
use Abort;
use Auth;

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
use App\Http\Requests\Service\Content\ContentDeleteRequest;
use App\Http\Requests\Service\Content\ContentPutRequest;
use App\Http\Requests\Service\Content\ContentPostRequest;
use App\Http\Requests\Service\Content\Image\ContentImagePostRequest;

// for image test
use Intervention;

class ContentController extends Controller
{
    public $content;
    public $user;
    public $uploader;

    public function __construct()
    {
        $this->user = Auth::user();
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
        $eagerLoad = ['user','user.image','imageGroup','imageGroup.image'];
        if(User::isUser()) $eagerLoad[] = 'myLike';
        $modeling = new Pager( Content::with($eagerLoad) );
        $collection = $modeling
            ->setQueryObject($request->query())
            ->setQuery()
            ->getCollection();
        $result = $modeling->getPageInfo();
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
    protected function get(Request $request, $content_id){
        $this->content = Content::findOrFail($content_id);
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
     *      name="Authorization",
     *      in="header",
     *      default="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIyIiwiaXNzIjoiaHR0cDovL2FwaWxvY2FsLnBpeGVsc3RhaXJzLmNvbTo4MDgwL3YxL21lbWJlcnMvc2lnbmluIiwiaWF0IjoxNTA2MjQyNzU2LCJleHAiOjI0OTc3OTA1MTcwMTA5ODg3NTYsIm5iZiI6MTUwNjI0Mjc1NiwianRpIjoiNGFGVDV5VEtlaTdiVDVtWiJ9.AcYrVZBkvIepPi66IGUG0RMHDiv2b93kEEet3Ie0loU",
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
        $this->content = $this->user->content()->create([
            "title" => $request->title,
            "description" => $request->description,
            "license_code" => $request->licenseCode,
            "hash_tags" => json_encode($request->hashTags),
        ]);
        return response()->success($this->content);
    }

    /**
     * @SWG\Post(
     *   path="/contents/{content_id}/image",
     *   @SWG\Parameter(
     *     name="content_id",
     *     description="ID of content that needs",
     *     in="path",
     *     required=true,
     *     type="string",
     *     default="1",
     *   ),
     *   summary="image upload",
     *   operationId="image upload",
     *   tags={"/Contents/Image"},
     *     @SWG\Parameter(
     *      type="string",
     *      name="Authorization",
     *      in="header",
     *      default="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIyIiwiaXNzIjoiaHR0cDovL2FwaWxvY2FsLnBpeGVsc3RhaXJzLmNvbTo4MDgwL3YxL21lbWJlcnMvc2lnbmluIiwiaWF0IjoxNTA2MjQyNzU2LCJleHAiOjI0OTc3OTA1MTcwMTA5ODg3NTYsIm5iZiI6MTUwNjI0Mjc1NiwianRpIjoiNGFGVDV5VEtlaTdiVDVtWiJ9.AcYrVZBkvIepPi66IGUG0RMHDiv2b93kEEet3Ie0loU",
     *      required=true
     *     ),
     *     @SWG\Parameter(
     *         description="file to upload",
     *         in="formData",
     *         name="file",
     *         required=true,
     *         type="file"
     *     ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function uploadImage(ContentImagePostRequest $request,$content_id){
        $this->content = Content::findOrFail($content_id);
        if( $this->content->isImageUploaded() ) return Abort::Error('0015');

        $this->uploader = new FileUpload();
        $this->content->update([
            "image_group_id" => $this->uploader->upload(
                $this->content,$request->file('file'),true
            )->getId(),
        ]);
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
     *      name="Authorization",
     *      in="header",
     *      default="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIyIiwiaXNzIjoiaHR0cDovL2FwaWxvY2FsLnBpeGVsc3RhaXJzLmNvbTo4MDgwL3YxL21lbWJlcnMvc2lnbmluIiwiaWF0IjoxNTA2MjQyNzU2LCJleHAiOjI0OTc3OTA1MTcwMTA5ODg3NTYsIm5iZiI6MTUwNjI0Mjc1NiwianRpIjoiNGFGVDV5VEtlaTdiVDVtWiJ9.AcYrVZBkvIepPi66IGUG0RMHDiv2b93kEEet3Ie0loU",
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
        $this->uploader = new FileUpload();
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
     *      name="Authorization",
     *      in="header",
     *      default="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIyIiwiaXNzIjoiaHR0cDovL2FwaWxvY2FsLnBpeGVsc3RhaXJzLmNvbTo4MDgwL3YxL21lbWJlcnMvc2lnbmluIiwiaWF0IjoxNTA2MjQyNzU2LCJleHAiOjI0OTc3OTA1MTcwMTA5ODg3NTYsIm5iZiI6MTUwNjI0Mjc1NiwianRpIjoiNGFGVDV5VEtlaTdiVDVtWiJ9.AcYrVZBkvIepPi66IGUG0RMHDiv2b93kEEet3Ie0loU",
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
