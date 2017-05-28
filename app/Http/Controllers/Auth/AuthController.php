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


    /**
     * @SWG\Post(
     *   path="/members/signin",
     *   summary="signin",
     *   operationId="signin",
     *   tags={"/Members/Auth"},
     *     @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     description="Sign in into web site",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/auth/signin")
     *   ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function signin(AuthSigninRequest $request)
    {
        if(!Auth::once(User::bindSigninData($request))) Abort::Error('0061');
        $this->user = Auth::getUser();

        $this->dispatch(new LastSigninTimeCheckerJob($this->user));
        $this->user->insertAccessToken();

        return response()->success([
            'token' => $this->user->token,
            'grade' => $this->user->grade,
            'status' => $this->user->status,
        ]);
    }

    /**
     * @SWG\Put(
     *   path="/members/signout",
     *   summary="signout",
     *   operationId="signout",
     *   tags={"/Members/Auth"},
     *     @SWG\Parameter(
     *      type="string",
     *      name="X-pixel-token",
     *      in="header",
     *      default="wtesttesttesttesttesttesttestte2",
     *      required=true
     *     ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function signout(AuthSignoutRequest $request)
    {
        $this->user = User::getAccessUser();
        $this->user->dropToken();
        return response()->success();
    }

    /**
     * @SWG\Post(
     *   path="/members/signup",
     *   summary="signup",
     *   operationId="signup",
     *   tags={"/Members/Auth"},
     *     @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     description="Sign up into web site",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/auth/signup")
     *   ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
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

    /**
     * @SWG\Get(
     *   path="/members/signup/evan",
     *   summary="mail test",
     *   operationId="mail test",
     *   tags={"/Test"},
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function signupTest(Request $request)
    {
        $this->user = User::findOrFail(3);
        $token = $this->user->insertAccessToken();
        $this->dispatch(new SignupMailSendJob($this->user));
        return response()->success([
            "user" => $this->user,
            "token" => $token
        ]);
    }

    /**
     * @SWG\Delete(
     *   path="/members/signdrop",
     *   summary="signdrop",
     *   operationId="signdrop",
     *   tags={"/Members/Auth"},
     *     @SWG\Parameter(
     *      type="string",
     *      name="X-pixel-token",
     *      in="header",
     *      default="wtesttesttesttesttesttesttestte2",
     *      required=true
     *     ),
     *     @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     description="Sign drop into web site",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/auth/signdrop")
     *     ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function signdrop(AuthSigndropRequest $request)
    {
        // TODO : save user sign drop reasons...
        $this->user = User::getAccessUser();
        if($this->user->delete()){
            return response()->success();
        }
        return Abort::Error('0040');
    }

    /**
     * @SWG\Post(
     *   path="/members/isexist",
     *   summary="isexist",
     *   operationId="isexist",
     *   tags={"/Members/Auth"},
     *     @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     description="Sign in into web site",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/auth/isexist")
     *   ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function isExist(AuthIsExistRequest $request)
    {
        try{
            $this->user = User::getFromEmail($request->email);
        }catch(\Exception $e){
            return response()->success(false);
        }
        return response()->success(true);
    }

    /**
     * @SWG\Post(
     *   path="/test/testerReset",
     *   summary="testerReset",
     *   operationId="testerReset",
     *   tags={"/Test"},
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function testerReset(Request $request)
    {
        $testUserId = 2;
        $this->user = User::find($testUserId);
        if( is_null($this->user) ){
            $this->user = User::onlyTrashed()->where('id', $testUserId)->first();
            $this->user->restore();
        }
        $this->user->insertAccessToken('wtesttesttesttesttesttesttestte2');
        return response()->success(true);
    }
}
