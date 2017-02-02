<?php
namespace App\Traits;

use App\Models\Interest;
use Abort;
use Log;

trait InterestControllTraits
{

    public function setNewInterest($interestsData)
    {
        $result = [];
        foreach ($interestsData as $key => $option) {
            $result[] = new Interest([
                "category_id" => $option['categoryId'],
                "division_id" => $option['divisionId'],
                "section_id" => isset($option['sectionId']) ? $option['sectionId'] : NULL,
            ]);
        }
        return $result;
    }

    public function setInterestId($survey,$interests){
        $index = 0;
        foreach ($interests as $key => $option) {
            $columnName = "interest_id_".$index;
            $survey->$columnName = $option->id;
            $index++;
        }
    }

    public function getInterestAll($survey){
        $result = [];
        for($i=0;$i<5;$i++){
            $interest = $survey->interestByIndex($i)->first();
            if( is_null($interest) ) return $result;
            $result[] = [
                "categoryId" => $interest['category_id'],
                "divisionId" => $interest['division_id'],
            ];
        }
        return $result;
    }
}
 ?>
