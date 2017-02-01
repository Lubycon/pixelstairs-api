<?php
namespace App\Traits;

use App\Models\ReviewAnswer;
use Abort;
use Log;

trait ReviewAnswerControllTraits
{
    public function setNewReviewAnswer($interestsData){
        $result = [];
        foreach ($interestsData as $key => $option) {
            $result[] = new ReviewAnswer([
                "question_id" => $option['qid'],
                "description" => $option['description'],
                "score" => $option['score'],
            ]);
        }
        return $result;
    }

    public function getQnA($answer){
        $result = [];
        foreach( $answer as $value ){
            $result[] = [
                "q" => [
                    "id" => $value->question->id,
                    "description" => $value->question->description,
                ],
                "a" => [
                    "id" => $value->id,
                    "description" => $value->description,
                    "score" => $value->score,
                ]
            ];
        }
        return $result;
    }

    public function updateAnswer($review,$answer){
        $result = [];
        foreach( $answer as $value ){
            if( isset($value['aid']) ){
                $result[] = $value['aid'];
                $review->answer->find($value['aid'])->update([
                    "question_id" => $value['qid'],
                    "description" => $value['description'],
                    "score" => $value['score'],
                ]);
            }else{
                $result[] = $review->answer()->create([
                    "question_id" => $value['qid'],
                    "description" => $value['description'],
                    "score" => $value['score'],
                ])['id'];
            }
        }
        $diff = array_diff($review->answer->pluck('id')->toArray(),$result);
        $pure = $review->answer()->whereIn('id',$diff)->delete();
        return $pure;
    }
}
 ?>
