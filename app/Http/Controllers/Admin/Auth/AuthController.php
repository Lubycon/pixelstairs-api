<?php

namespace App\Http\Controllers\Admin\Auth;

// Global
use Log;
use Auth;
use Abort;

// Models
use App\Models\User;

// Require
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;

// Requests

// Jobs

class AuthController extends Controller
{
    use ThrottlesLogins;

    public $user;
    public $uploader;

    public function __construct()
    {
        $this->user = User::class;
    }


    protected function signin(Request $request)
    {
        if(!Auth::once(User::bindSigninData($request))) Abort::Error('0061');
        $this->user = Auth::user();
        if( $this->user->isAdmin()){
            $this->user->insertAccessToken();
            return response()->success([
                'token' => $this->user->token,
                'grade' => $this->user->grade,
                'status' => $this->user->status,
            ]);
        }else{
            Abort::Error('0043');
        }
    }

    protected function signup(Request $request)
    {
        $signupData = User::bindSignupData($request);

        if( $this->user = User::create($signupData)){
            $token = $this->user->insertAccessToken();
            return response()->success([
                "token" => $token
            ]);
        }
        return Abort::Error('0040');
    }

    protected function signdrop(Request $request,$id)
    {
        $this->user = User::findOrFail($id);
        if($this->user->delete()){
            return response()->success();
        }
        return Abort::Error('0040');
    }
}
