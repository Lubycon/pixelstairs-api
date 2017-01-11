<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Controllers\Pager\PageController;
use App\Models\Category;

class CategoryController extends Controller
{
    public $category;

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
                "name" => array(
                    "origin" => $array['original_name'],
                    "zh" => $array['chinese_name'],
                ),
            );
        };

        if(!empty($result->categories)){
            return response()->success($result);
        }else{
            return response()->success();
        }
    }
    public function post(Request $request){
        $this->category = new Category;
        $this->category->original_name = $request['name']['origin'];
        $this->category->chinese_name = $request['name']['zh'];
        if( $this->category->save() ){
            return response()->success($this->category);
        }else{
            Abort::Error('0040');
        }
    }
    public function put(){
    }
    public function delete(){
    }
}
