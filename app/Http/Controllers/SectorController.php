<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Controllers\Pager\PageController;
use App\Models\Sector;

class SectorController extends Controller
{
    public $sector;

    public function getList(Request $request){
        $query = $request->query();
        $controller = new PageController('sector',$query);
        $collection = $controller->getCollection();

        $result = (object)array(
            "totalCount" => $controller->totalCount,
            "currentPage" => $controller->currentPage,
        );
        foreach($collection as $array){
            $result->sectors[] = (object)array(
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

        if(!empty($result->sectors)){
            return response()->success($result);
        }else{
            return response()->success();
        }
    }
    public function post(Request $request){
        $this->sector = new sector;
        $array = [];
        foreach( $request['name'] as $key => $value ){
            $array[] = Sector::firstOrCreate(array(
                "original_name" => $value['origin'],
                "chinese_name" => $value['zh'],
                "market_id" => $request['marketId'],
                "market_category_id" => $request['marketCategoryId'],
                "parent_id" => $request['parentId'],
            ))['id'];
        }
        return response()->success($array);
    }
    public function put(Request $request,$id){
        $this->sector = Sector::findOrFail($id);
        $this->sector->original_name = $request['name']['origin'];
        $this->sector->chinese_name = $request['name']['zh'];
        $this->sector->parent_id = $request['parentId'];
        $this->sector->market_id = $request['marketId'];
        $this->sector->market_category_id = $request['marketCategoryId'];
        if( $this->sector->save() ){
            return response()->success($this->sector);
        }else {
            Abort::Error('0040');
        }
    }
    public function delete(Request $request,$id){
        $this->sector = Sector::findOrFail($id);
        if($this->sector->delete()){
            return response()->success();
        }else {
            Abort::Error('0040');
        }
    }
}
