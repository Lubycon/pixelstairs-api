<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Traits\ReviewQuestionControllTraits;

use Log;

class QuestionController extends Controller
{
    use ReviewQuestionControllTraits;

    public $target;
    public $product;
    public $division;
    public $question;
    public $language;

    public function get(Request $request,$target,$target_id)
    {
        $this->language = $request->header('X-mitty-language');
        $this->target = $this->getReviewTarget($target,$target_id);
        $this->product = $this->target->product;
        $this->division = $this->product->division;
        $this->question = isset($this->division->reviewQuestion) ? $this->division->reviewQuestion : [];

        $result = [
            "skuName" => $this->target->option->getTranslateResultByLanguage($this->target->option->translateName,$this->language),
            "q" => [],
        ];
        foreach( $this->question as $value ){
            $result["q"][] = [
                "id" => $value->id,
                "qKey" => $value->getTranslateResultByLanguage($value->translateName,$this->language),
                "description" => $value->description,
            ];
        }
        return response()->success($result);
    }
}
