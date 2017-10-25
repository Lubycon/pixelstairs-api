<?php

namespace App\Http\Controllers\Admin\Auth;

// Global
use Log;
use Auth;
use Abort;
use Tymon\JWTAuth\Facades\JWTAuth;

// Models
use App\Models\User;
use App\Models\RefreshToken;

// Require
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;

// Requests
use App\Http\Requests\Admin\Auth\AuthSignupRequest;

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
        $credentials = User::bindSigninData($request);

        if (! $access_token = JWTAuth::attempt($credentials)) {
            return Abort::Error('0061');
        }

        $this->user = Auth::user();
        $refresh_token = RefreshToken::createToken($access_token);
        if( $this->user->isAdmin()){
            return response()->success([
                'access_token' => $access_token,
                'refresh_token' => $refresh_token,
                'grade' => $this->user->grade,
                'status' => $this->user->status,
            ]);
        }else{
            Abort::Error('0043');
        }
    }

    protected function signup(AuthSignupRequest $request)
    {
        $signupData = User::bindSignupData($request);

        if( $this->user = User::create($signupData)){
            $access_token = JWTAuth::fromUser($this->user);
            $auth = JWTAuth::setToken($access_token)->authenticate();
            $refresh_token = RefreshToken::createToken($access_token);
            return response()->success([
                'access_token' => $access_token,
                'refresh_token' => $refresh_token,
            ]);
        }
        return Abort::Error('0040');
    }

    protected function signdrop(Request $request,$id)
    {
        $this->user = User::findOrFail($id);
        if($this->user->delete()){
            JWTAuth::invalidate();
            return response()->success();
        }
        return Abort::Error('0040');
    }
}
