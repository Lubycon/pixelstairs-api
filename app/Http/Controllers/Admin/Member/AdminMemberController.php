<?php

namespace App\Http\Controllers\Admin\Member;

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

    protected function getRetrieve(Request $request, $user_id)
    {
        $this->user = User::findOrFail($user_id);
        $result = $this->user->getDetailInfoByAdmin();
        return response()->success($result);
    }

    public function putRetrieve(Request $request, $user_id)
    {
        $this->user = User::findOrFail($user_id);
        $result = null;

        try {
            $this->user->update([
                "nickname" => $request->nickname,
                "status" => $request->status,
                "grade" => $request->grade
            ]);
            $result = $this->user->getDetailInfo();
        } catch (\Exception $e){
            Abort::Error('0040');
        }
        return response()->success($result);
    }
}
