<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Controllers\Pager\PageController;
use App\Models\Division;
use App\Traits\TranslateTraits;

class DivisionController extends Controller
{
    use TranslateTraits;

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
                "name" => $array->getTranslate($array),
                "parentId" => $array['parent_id'],
            );
        };

        if(!empty($result->divisions)){
            return response()->success($result);
        }else{
            return response()->success();
        }
    }
    public function post(Request $request){
        $data = [
            "translate_name_id" => $this->createTranslateName($request['name'])['id'],
            "parentId" => $request['parentId'],
        ];
        if( $hello = Division::firstOrCreate($data) ){
            return response()->success($hello);
        }else{
            Abort::Error('0040');
        }
    }
    public function put(Request $request,$id){
        $this->division = Division::findOrFail($id);
        $this->division->translate_name_id = $this->createTranslateName($request['name'])['id'];
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
