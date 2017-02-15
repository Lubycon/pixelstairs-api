<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Pager\PageController;

use App\Models\GiveProduct;
use App\Models\Review;
use App\Models\User;

use App\Traits\GetUserModelTrait;

use Log;
use Abort;

use App\Http\Requests\GiveAccept\GiveAcceptGetRequest;
use App\Http\Requests\GiveAccept\GiveAcceptPostRequest;

class GiveAcceptController extends Controller
{
    use GetUserModelTrait;

    protected $review;
    protected $user;
    protected $language;

    public function getList(GiveAcceptGetRequest $request, $review_id){
        $this->user = $this->getUserByTokenRequestOrFail($request);
        $this->language = $request->header('X-mitty-language');
        $query = $request->query();
        $query['search'] = "acceptUserId:".$this->user->id."||reviewId:".$review_id;

        $controller = new PageController('give_product',$query);
        $collection = $controller->getCollection();

        $result = (object)array(
            "totalCount" => $controller->totalCount,
            "currentPage" => $controller->currentPage,
        );
        foreach($collection as $array){
            $result->accepts[] = (object)array(
                "id" => $array["id"],
                "applyUser" => [
                    "id" => $array->review->user->id,
                    "name" => $array->review->user->name,
                    "profileImg" => $array->review->user->image->getObject()
                ],
                "giveStock" => $array->review->give_stock,
            );
        };

        if(!empty($result->accepts)){
            return response()->success($result);
        }else{
            return response()->success();
        }
    }

    public function post( GiveAcceptPostRequest $request , $review_id ){
        $this->user = $this->getUserByTokenRequestOrFail($request);
        $this->review = Review::findOrFail($review_id);
        $acceptApply = $this->review->acceptUser($request->applyUserId);
        return response()->success(["giveStock"=>$this->review->give_stock]);
    }
}
