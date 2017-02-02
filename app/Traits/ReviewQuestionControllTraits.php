<?php
namespace App\Traits;

use App\Models\Award;
use App\Models\Order;
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
}
 ?>
