<?php

namespace App\Http\Controllers\Member;

// Global
use Log;
use Abort;

// Models
use App\Models\User;

// Require
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// Class
use App\Classes\Pager;


class AdminMemberController extends Controller {
    public $user;
    public $pager;

    public function __construct() {
        $this->user = User::class;
        $this->pager = new Pager();
    }

    /**
     * @SWG\Get(
     *   path="/admin/members",
     *   summary="get member list",
     *   operationId="members",
     *   tags={"/Admin/Members"},
     *     @SWG\Parameter(
     *      type="string",
     *      name="X-pixel-token",
     *      in="header",
     *      default="need admin token!",
     *      required=true
     *     ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function getList(Request $request) {
        $collection = $this->pager
            ->search(new $this->user, $request->query())
            ->getCollection();
        $result = $this->pager->getPageInfo();
        foreach($collection as $user) {
            $user = User::findOrFail($user->id);
            $result->users[] = $user->getDetailInfo();
        };

        if(!empty($result->users)) {
            return response()->success($result);
        }
        else {
            return response()->success();
        }
    }
}
