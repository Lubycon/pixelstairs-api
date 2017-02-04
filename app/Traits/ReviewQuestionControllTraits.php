<?php
namespace App\Traits;

use App\Models\Award;
use App\Models\Order;
use App\Models\ReviewQuestion;
use Abort;
use Log;

trait ReviewQuestionControllTraits
{

    protected function getReviewTarget($target_name,$target_id){
        return $target_name == 'award'
            ? Award::find($target_id)
            : Order::find($target_id);
    }

    protected function getReviewTargetByRequest($request,$target_id){
        return $this->getReviewTarget($request['target'],$target_id);
    }

    protected function createReviewQuestions($questions){
        $result = [];
        foreach ($questions as $question) {
            $result[] = $item = new ReviewQuestion();
            $item->question_key_id = $question['qKeyId'];
            $item->translate_description_id = $this->createTranslateDescription($question['description'])['id'];
        }
        return $result;
    }
}
 ?>
