<?php
//namespace App\Traits;
//
//use App\Models\Award;
//use App\Models\Order;
//use App\Models\ReviewQuestion;
//use Abort;
//use Log;
//
//trait ReviewQuestionControllTraits
//{
//
//    protected function getReviewTarget($target_name,$target_id){
//        return $target_name == 'award'
//            ? Award::find($target_id)
//            : Order::find($target_id);
//    }
//
//    protected function getReviewTargetByRequest($request,$target_id){
//        return $this->getReviewTarget($request['target'],$target_id);
//    }
//
//    protected function createReviewQuestions($questions){
//        $result = [];
//        foreach ($questions as $question) {
//            if( is_null($question['qKeyId']) ) Abort::Error('0040',"Question Key was NULL");
//            $result[] = $item = new ReviewQuestion();
//            $item->question_key_id = $question['qKeyId'];
//            $item->translate_description_id = $this->createTranslateDescription($question['description'])['id'];
//        }
//        return $result;
//    }
//
//    public function updateReviewQuestions($product,$question){
//        $result = [];
//        if( !is_null($question) ){
//            foreach( $question as $value ){
//                if( isset($value['id']) ){
//                    $result[] = $value['id'];
//                    $product->reviewQuestion()->findOrFail($value['id'])->update([
//                        "question_key_id" => $value['qKeyId'],
//                        "translate_description_id" => $this->createTranslateDescription($value['description'])['id'],
//                    ]);
//                }else{
//                    $result[] = $product->reviewQuestion()->create([
//                        "question_key_id" => $value['qKeyId'],
//                        "translate_description_id" => $this->createTranslateDescription($value['description'])['id'],
//                    ])['id'];
//                }
//            }
//        }
//        $diff = array_diff($product->reviewQuestion->pluck('id')->toArray(),$result);
//        $pure = $product->reviewQuestion()->whereIn('id',$diff)->delete();
//        return $pure;
//    }
//}
// ?>
