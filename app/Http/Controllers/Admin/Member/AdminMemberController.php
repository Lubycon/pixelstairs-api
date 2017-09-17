<?php

namespace App\Http\Controllers\Admin\Member;

// Global
use Log;
use Abort;

// Models
use App\Models\User;
use App\Models\BlackUser;

// Request
use App\Http\Requests\Service\Member\MemberPutRetrieveRequest;

// Require
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// Class
use App\Classes\Pager\Pager;


class AdminMemberController extends Controller {
    public $user;
    public $blackUser;

    public function __construct() {
        $this->user = User::class;
        $this->blackUser = BlackUser::class;
    }

    protected function getList(Request $request) {
        $modeling = new Pager( User::with(['image','blackUser']) );
        $collection = $modeling
            ->setQueryObject($request->query())
            ->setQuery()
            ->getCollection();
        $result = $modeling->getPageInfo();
        foreach($collection as $user) {
            $result->users[] = $user->getDetailInfoByAdmin();
        };

        if(!empty($result->users)) {
            return response()->success($result);
        }
        else {
            return response()->success();
        }
    }

    protected function getBlackUserList(Request $request) {
        $modeling = new Pager( BlackUser::with(['user','user.image','user.blackUser']) );
        $collection = $modeling
            ->setQueryObject($request->query())
            ->setQuery()
            ->getCollection();
        $result = $modeling->getPageInfo();
        foreach($collection as $blackUser) {
            $user = $blackUser->user;
            $result->blackUsers[] = [
                "user" => $user->getDetailInfoByAdmin(),
                "blackInfo" => $user->getBlackInfo()
            ];
        };

        if(!empty($result->blackUsers)) {
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

    public function putRetrieve(MemberPutRetrieveRequest $request, $user_id)
    {
        $this->user = User::findOrFail($user_id);
        $result = null;

        try {
            $this->user->update([
                "nickname" => $request->nickname,
                "status" => $request->status,
                "grade" => $request->grade
            ]);

            $this->user = $request->isBlackUser
                ? $this->user->setToBlackList()
                : $this->user->removeFromBlackList();

            $result = $this->user->getDetailInfo();
        } catch (\Exception $e){
            Abort::Error('0040');
        }
        return response()->success($result);
    }
}
