<?php
namespace App\Traits;

use App\Models\ContentTag;
use App\Models\ContentCategory;
use App\Models\ContentCategoryKernel;

use Abort;

trait InsertArrayToColumn{
    //array is fure array, dataName is database culumne name
    function InsertContentTagName($array){
        $newArray=[];
        foreach($array as $key => $value){
            $newArray[] = new ContentTag(['name'=>$value]);
        }
        return $newArray;
    }
    function InsertContentCategoryId($array){
        $newArray=[];
        foreach($array as $key => $value){
            $categoryId = ContentCategory::where('name','=',$value)->value('id');
            if( $categoryId == null ){
                Abort::Error('0040','Unallowed category id');
            }
            $newArray[] = new ContentCategoryKernel(['category_id'=>$categoryId]);
        }
        return $newArray;
    }
}
 ?>
