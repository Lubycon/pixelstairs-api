<?php

namespace App\Http\Controllers;

use App\Models\SectionMarketInfo;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Controllers\Pager\PageController;
use App\Models\Section;
use Log;
use App\Traits\TranslateTraits;

class SectionController extends Controller
{
    use TranslateTraits;

    public $section;

    public function getList(Request $request){
        $query = $request->query();
        $controller = new PageController('section',$query);
        $collection = $controller->getCollection();

        $result = (object)array(
            "totalCount" => $controller->totalCount,
            "currentPage" => $controller->currentPage,
        );

        foreach($collection as $array){
            $result->sections[] = (object)array(
                "id" => $array["id"],
                "name" => $array->getTranslate($array),
                "parentId" => $array['parent_id'],
                "marketId" => $array->sectionMarketInfo['market_id'],
                "marketCategoryId" => $array->sectionMarketInfo['market_category_id'],
            );
        };

        if(!empty($result->sections)){
            return response()->success($result);
        }else{
            return response()->success();
        }
    }
    public function post(Request $request){
        $result = [];
        foreach( $request['name'] as $key => $value ){
            $section = Section::firstOrCreate(array(
                "translate_name_id" => $this->createTranslateName($value)['id'],
                "parent_id" => $request['parentId'],
            ))['id'];
            SectionMarketInfo::firstOrCreate(array(
                "section_id" => $section,
                "market_id" => $request['marketId'],
                "market_category_id" => $request['marketCategoryId'],
            ));
            $result[] = $section;
        }
        return response()->success($result);
    }
    public function put(Request $request,$id){
        $this->section = Section::findOrFail($id);
        $this->section->translate_name_id = $this->createTranslateName($request['name'])['id'];
        $this->section->parent_id = $request['parentId'];
        $this->section->sectionMarketInfo->market_id = $request['marketId'];
        $this->section->sectionMarketInfo->market_category_id = $request['marketCategoryId'];
        if( $this->section->save() ){
            return response()->success($this->section);
        }else {
            Abort::Error('0040');
        }
    }
    public function delete(Request $request,$id){
        $this->section = Section::findOrFail($id);
        if($this->section->delete()){
            return response()->success();
        }else {
            Abort::Error('0040');
        }
    }
}