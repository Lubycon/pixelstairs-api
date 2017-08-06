<?php

namespace App\Http\Controllers\Survey;

// Global
use Log;
use Abort;

// Models
use App\Models\SigndropAnswer;
use App\Models\SigndropQuestion;

// Require
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


// Request

class SigndropSurveyController extends Controller
{
    public $question;
    public $answer;
    public function __construct()
    {
        $this->question = SigndropQuestion::class;
        $this->answer = SigndropAnswer::class;
    }

    /**
     * @SWG\Get(
     *   path="/members/signdrop/survey/list",
     *   summary="signdrop survey",
     *   operationId="signdrop survey",
     *   tags={"/Members/User/Signdrop/Survey"},
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function getList(Request $request){
        $this->question = SigndropQuestion::all();
        $result = [];

        foreach( $this->question as $question ){
            $result[] = [
                "question_id" => $question->id,
                "question" => $question->getQuestion(),
                "answer" => $question->signdropAnswer->map(function($value) {
                    return $value->getAnswer();
                }),
            ];
        }

        return response()->success($result);
    }
}
