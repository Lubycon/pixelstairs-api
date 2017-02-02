<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Controllers\Pager\PageController;
use App\Models\Category;

use App\Traits\TranslateTraits;
use Log;

class CategoryController extends Controller
{
    use TranslateTraits;

    public $category;

//    /**
//     * @SWG\Get(
//     *     path="/categories",
//     *     summary="Get Categroy List",
//     *     description="category list get",
//     *     produces={"application/json"},
//     *     tags={"category"},
//     *     @SWG\Parameter(
//     *         name="pageIndex",
//     *         default="1",
//     *         in="query",
//     *         description="조회할 페이지 번호, 최초페이지는 1페이지",
//     *         required=false,
//     *         type="integer"
//     *     ),
//     *     @SWG\Parameter(
//     *         name="pageSize",
//     *         in="query",
//     *         description="한 페이지당 컨텐츠 수 기본 20 최대 100",
//     *         required=false,
//     *         type="integer",
//     *     ),
//     *     @SWG\Parameter(
//     *         name="sort",
//     *         in="query",
//     *         description="기본 id순 역 정렬 | 문법 id:asc,createDate:desc",
//     *         required=false,
//     *         type="string",
//     *     ),
//     *     @SWG\Parameter(
//     *         name="filter",
//     *         in="query",
//     *         description="문법 id:1,createDate:2017-10-10~2017-10-11,price:10000~11000",
//     *         required=false,
//     *         type="string",
//     *     ),
//     *     @SWG\Response(
//     *         response=200,
//     *         description="successful operation",
//     *     ),
//     *     @SWG\Response(
//     *         response="400",
//     *         description="Unexpected data value",
//     *     )
//     * )
//     */

    public function getList(Request $request){
        $query = $request->query();
        $controller = new PageController('category',$query);
        $collection = $controller->getCollection();

        $result = (object)array(
            "totalCount" => $controller->totalCount,
            "currentPage" => $controller->currentPage,
        );
        foreach($collection as $array){
            $result->categories[] = (object)array(
                "id" => $array["id"],
                "name" => $array->getTranslate($array),
            );
        };

        if(!empty($result->categories)){
            return response()->success($result);
        }else{
            return response()->success();
        }
    }
//    /**
//     * @SWG\Post(
//     *     path="/categories",
//     *     summary="카테고리 만들기",
//     *     description="새로운 카테고리를 만든다",
//     *     produces={"application/json"},
//     *     consumes={"string"},
//     *     tags={"category"},
//     *     @SWG\Parameter(
//     *         name="name",
//     *         in="body",
//     *         description="카테고리 이름을 번역하여 보내야함",
//     *         required=true,
//     *         type="array",
//     *
//     *
//     *
//     *
//     *
//     *     @SWG\Schema(
//     *
//     *       @SWG\Property(
//     *         property="name",
//     *         type="object",
//     *
//     *           @SWG\Property(
//     *             property="origin",
//     *             type="string",
//     *             default="ffff",
//     *             example="dddd"
//     *           ),
//     *           @SWG\Property(
//     *             property="zh",
//     *             type="string",
//     *             default="ffff",
//     *             example="dddd"
//     *           ),
//     *       )
//     *
//     *     )
//     *
//     *
//     *
//     *
//     *
//     *
//     *
//     *
//     *
//     *     ),
//     *     @SWG\Property(property="use_this", x={
//     *          "data": {
//     *              "id": 1,
//     *              "email": "joe@doe.com"
//     *          }
//     *     }),
//     *     @SWG\Response(
//     *         response=200,
//     *
//     *         description="successful operation",
//     *     ),
//     *     @SWG\Response(
//     *         response="400",
//     *         description="Unexpected data value",
//     *     )
//     * )
//     */
    public function post(Request $request){
        $data = [
            "translate_name_id" => $this->createTranslateName($request['name'])['id'],
        ];

        if( $cate = Category::firstOrCreate($data) ){
            return response()->success($cate);
        }else{
            Abort::Error('0040');
        }
    }
    public function put(Request $request,$id){
        $this->category = Category::findOrFail($id);
        $this->category->translate_name_id = $this->createTranslateName($request['name'])['id'];
        if( $this->category->save() ){
            return response()->success($this->category);
        }else {
            Abort::Error('0040');
        }
    }
    public function delete(Request $request,$id){
        $this->category = Category::findOrFail($id);
        if($this->category->delete()){
            return response()->success();
        }else {
            Abort::Error('0040');
        }
    }
}
