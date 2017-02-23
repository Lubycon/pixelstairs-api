<?php
//
//namespace App\Traits;
///**
// * Created by PhpStorm.
// * User: daniel_zepp
// * Date: 2017. 1. 23.
// * Time: 오후 8:17
// */
//use App\Models\TranslateName;
//use App\Models\TranslateDescription;
//use Abort;
//use Log;
//
//trait TranslateTraits{
//
//    public function relationTranslateName($column){
//        return ['translate_name_id'=>$this->createTranslateName($column)['id']];
//    }
//
//    public function createTranslateName($data){
//        $array = $this->setTranslateArray($data);
//        return TranslateName::firstOrCreate($array);
//    }
//
//    public function createTranslateDescription($data){
//        $array = $this->setTranslateArray($data);
//        return TranslateDescription::create($array);
//    }
//}