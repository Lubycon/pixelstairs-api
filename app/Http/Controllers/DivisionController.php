<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Controllers\Pager\PageController;
use App\Models\Division;

class DivisionController extends Controller
{
    public $division;

    public function getList(Request $request){
        $query = $request->query();
        $controller = new PageController('division',$query);
        $collection = $controller->getCollection();

        $result = (object)array(
            "totalCount" => $controller->totalCount,
            "currentPage" => $controller->currentPage,
        );
        foreach($collection as $array){
            $result->divisions[] = (object)array(
                "id" => $array["id"],
                "name" => array(
                    "origin" => $array["original_name"],
                    "zh" => $array['chinese_name'],
                ),
                "parentId" => $array['parent_id'],
                "marketId" => $array['market_id'],
                "marketCategoryId" => $array['market_category_id'],
            );
        };

        if(!empty($result->divisions)){
            return response()->success($result);
        }else{
            return response()->success();
        }
    }
    public function post(Request $request){
        $this->division = new Division;
        $this->division->original_name = $request['name']['origin'];
        $this->division->chinese_name = $request['name']['zh'];
        $this->division->parent_id = $request['parentId'];
        $this->division->market_id = $request['marketId'];
        $this->division->market_category_id = $request['marketCategoryId'];

        if( $this->division->save() ){
            return response()->success($this->division);
        }else{
            Abort::Error('0040');
        }
    }
    public function put(Request $request,$id){
        $this->division = Division::findOrFail($id);
        $this->division->original_name = $request['name']['origin'];
        $this->division->chinese_name = $request['name']['zh'];
        $this->division->parent_id = $request['parentId'];
        $this->division->market_id = $request['marketId'];
        $this->division->market_category_id = $request['marketCategoryId'];
        if( $this->division->save() ){
            return response()->success($this->division);
        }else {
            Abort::Error('0040');
        }
    }
    public function delete(Request $request,$id){
        $this->division = Division::findOrFail($id);
        if($this->division->delete()){
            return response()->success();
        }else {
            Abort::Error('0040');
        }
    }
}
