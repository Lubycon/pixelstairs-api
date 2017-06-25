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

// Requests
use App\Http\Requests\Admin\AdminRequest;

class AdminMemberController extends Controller {
    public $user;
    public $pager;

    public function __construct() {
        $this->user = User::class;
        $this->pager = new Pager();
    }

    protected function getList(AdminRequest $request) {
        $is_admin = User::isAdmin();
        $collection = $this->pager
            ->search('user', $request->query())
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
