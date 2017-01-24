<?php

namespace App\Traits;

trait SectionTrait{

    public function setSectionGroup($array,$parent_id){
        $result = [];
        $result['parent_id'] = $parent_id;
        foreach($array as $key => $value){
            $result['section_id_'.$key] = $value;
        }
        return $result;
    }
}