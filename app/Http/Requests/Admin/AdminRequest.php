<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Models\User;
use App\Traits\AuthorizesRequestsOverLoad;

use Log;

class AdminRequest extends Request {
    use AuthorizesRequestsOverLoad;

    public function authorize() {
        $is_admin = User::isAdmin();

        return $is_admin;
    }

    public function rules() {
        $requiredRule = [];
        return $requiredRule;
    }
}
