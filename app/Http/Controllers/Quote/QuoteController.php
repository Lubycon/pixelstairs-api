<?php

namespace App\Http\Controllers\Quote;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Quote;

class QuoteController extends Controller
{
    public $quote;
    public $category;

    public function __construct()
    {
        $this->quote = Quote::class;
    }

    /**
     * @SWG\Get(
     *   path="/quotes/{category}",
     *   @SWG\Parameter(
     *     name="category",
     *     description="Quote category",
     *     in="path",
     *     required=true,
     *     type="string",
     *     enum={"success", "mistake"},
     *     default="success",
     *   ),
     *   summary="success",
     *   operationId="success",
     *   tags={"/Quotes/data"},
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    public function get(Request $request,$category){
        // get random quote
        $this->quote = Quote::getRandomQuoteByCategory($category);
        $result = $this->quote->getData();

        return response()->success($result);
    }
}
