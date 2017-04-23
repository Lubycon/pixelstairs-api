<?php

namespace App\Http\Controllers\Auth;

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
use App\Http\Requests\Auth\AuthSigninRequest;
use App\Http\Requests\Auth\AuthSignupRequest;
use App\Http\Requests\Auth\AuthSigndropRequest;
use App\Http\Requests\Auth\AuthSignoutRequest;
use App\Http\Requests\Auth\AuthIsExistRequest;

// Jobs
use App\Jobs\LastSigninTimeCheckerJob;
use App\Jobs\Mails\SignupMailSendJob;


class AuthController extends Controller
{
    use ThrottlesLogins;

    public $user;
    public $uploader;

    public function __construct()
    {
        $this->user = User::class;
    }

    protected function signin(AuthSigninRequest $request)
    {
        if(!Auth::once(User::bindSigninData($request))) Abort::Error('0061');
        $this->user = Auth::getUser();

        $this->dispatch(new LastSigninTimeCheckerJob($this->user));

        if($this->user->status == 'active') $this->user->insertAccessToken();

        return response()->success([
            'token' => $this->user->token,
            'grade' => $this->user->status,
        ]);
    }


    protected function signout(AuthSignoutRequest $request)
    {
        $this->user = User::getAccessUser();
        $this->user->dropToken();
        return response()->success();
    }

    protected function signup(AuthSignupRequest $request)
    {
        $signupData = User::bindSignupData($request);

        if( $this->user = User::create($signupData)){
            $token = $this->user->insertAccessToken();
            $this->dispatch(new SignupMailSendJob($this->user));
            return response()->success([
                "token" => $token
            ]);
        }
        return Abort::Error('0040');
    }

    protected function signdrop(AuthSigndropRequest $request)
    {
        // TODO : save user sign drop reasons...
        $this->user = User::getAccessUser();
        if($this->user->delete()){
            return response()->success();
        }
        return Abort::Error('0040');
    }


    protected function isExist(AuthIsExistRequest $request)
    {
        try{
            $this->user = User::getFromEmail($request->email);
        }catch(\Exception $e){
            return response()->success(false);
        }
        return response()->success(true);
    }
}
