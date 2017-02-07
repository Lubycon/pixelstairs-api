<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Traits\ReviewQuestionControllTraits;
use App\Traits\TranslateTraits;

use App\Models\Division;
use App\Models\ReviewQuestionKey;

use App\Http\Requests\Question\QuestionKeyPost;

use Log;

class QuestionController extends Controller
{
    use ReviewQuestionControllTraits,TranslateTraits;

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
        $this->question = isset($this->product->reviewQuestion) ? $this->product->reviewQuestion : [];

        $result = [
            "skuName" => $this->target->option->getTranslateResultByLanguage($this->target->option->translateName,$this->language),
            "q" => [],
        ];
        foreach( $this->question as $value ){
            $result["q"][] = [
                "id" => $value->id,
                "qKey" => $value->questionKey->getTranslateResultByLanguage($value->questionKey->translateName,$this->language),
                "description" => $value->getTranslateResultByLanguage($value->translateDescription,$this->language),
            ];
        }
        return response()->success($result);
    }

    public function getKeys(Request $request,$division_id)
    {
        $this->division = Division::findOrFail($division_id);
        $questionKey = $this->division->reviewQuestionKey;
        $commonQuestionKey = ReviewQuestionKey::whereis_common(1)->get();

        $result = [];
        foreach( $commonQuestionKey as $value ){
            $result[] = [
                "id" => $value->id,
                "qKey" => $value->getTranslate($value),
                "isCommon" => true,
            ];
        }
        foreach( $questionKey as $value ){
            $result[] = [
                "id" => $value->id,
                "qKey" => $value->getTranslate($value),
                "isCommon" => false,
            ];
        }
        return response()->success($result);
    }

    public function postKey(QuestionKeyPost $request){
        $questionKey = new ReviewQuestionKey;
        $questionKey->division_id = isset($request['divisionId']) ? $request['divisionId'] : null;
        $questionKey->translate_name_id = $this->createTranslateName($request['qKey'])['id'];
        $questionKey->is_common = $request['isCommon'];

        if ( !$questionKey->save() ) Abort::Error("0040");
        return response()->success($questionKey);
    }
}
