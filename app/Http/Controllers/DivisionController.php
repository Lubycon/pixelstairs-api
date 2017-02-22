<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Controllers\Pager\PageController;
use App\Models\Division;
use App\Traits\TranslateTraits;

use App\Http\Requests\Division\DivisionPostRequest;
use App\Http\Requests\Division\DivisionPutRequest;
use App\Http\Requests\Division\DivisionDeleteRequest;

class DivisionController extends Controller
{
    use TranslateTraits;

    public $division;
    public $language;

    public function getList(Request $request){
        $this->language = $request->header('X-mitty-language');
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
                "name" => $array->getTranslateResultByLanguage($array,$this->language),
                "parentId" => $array['parent_id'],
            );
        };

        if(!empty($result->divisions)){
            return response()->success($result);
        }else{
            return response()->success();
        }
    }
//    public function post(DivisionPostRequest $request){
//        $data = [
//            "translate_name_id" => $this->createTranslateName($request['name'])['id'],
//            "parent_id" => $request['parentId'],
//        ];
//        if( $hello = Division::firstOrCreate($data) ){
//            return response()->success($hello);
//        }else{
//            Abort::Error('0040');
//        }
//    }
//    public function put(DivisionPutRequest $request,$id){
//        $this->division = Division::findOrFail($id);
//        $this->division->translate_name_id = $this->createTranslateName($request['name'])['id'];
//        if( $this->division->save() ){
//            return response()->success($this->division);
//        }else {
//            Abort::Error('0040');
//        }
//    }
//    public function delete(DivisionDeleteRequest $request,$id){
//        $this->division = Division::findOrFail($id);
//        if($this->division->delete()){
//            return response()->success();
//        }else {
//            Abort::Error('0040');
//        }
//    }
}
