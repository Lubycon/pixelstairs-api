<?php

namespace App\Http\Controllers\Service\Auth;

// Global
use Log;
use Mockery\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use Auth;
use Abort;

// Models
use App\Models\User;
use App\Models\AccessToken;

// Require
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;

// Requests
use App\Http\Requests\Service\Auth\AuthSigninRequest;
use App\Http\Requests\Service\Auth\AuthSignupRequest;
use App\Http\Requests\Service\Auth\AuthSigndropRequest;
use App\Http\Requests\Service\Auth\AuthSignoutRequest;
use App\Http\Requests\Service\Auth\AuthEmailExistRequest;
use App\Http\Requests\Service\Auth\AuthNicknameExistRequest;

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
        $this->user = Auth::user();
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
        $credentials = User::bindSigninData($request);

        if (! $token = JWTAuth::attempt($credentials)) {
            return Abort::Error('0061');
        }

        $this->user = Auth::user();
        $this->dispatch(new LastSigninTimeCheckerJob($this->user));

        return response()->success([
            'token' => $token,
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
     *      name="Authorization",
     *      in="header",
     *      default="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIyIiwiaXNzIjoiaHR0cDovL2FwaWxvY2FsLnBpeGVsc3RhaXJzLmNvbTo4MDgwL3YxL21lbWJlcnMvc2lnbmluIiwiaWF0IjoxNTA1NTQxMDE5LCJleHAiOjE1MDU1NDQ2MTksIm5iZiI6MTUwNTU0MTAxOSwianRpIjoiekFwOWlUSmdjTlBOYnRociJ9.NdK7NHJ98U3nMqSraJMpnr10cd1cz3EbZHyaFLWMlKc",
     *      required=true
     *     ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function signout(AuthSignoutRequest $request)
    {
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
            Auth::onceUsingId($this->user->id);
            $token = $this->user->insertAccessToken();
            $this->dispatch(new SignupMailSendJob($this->user));
            return response()->success([
                "token" => $token
            ]);
        }
        return Abort::Error('0040');
    }

    /**
     * @SWG\Delete(
     *   path="/members/signdrop",
     *   summary="signdrop",
     *   operationId="signdrop",
     *   tags={"/Members/Auth"},
     *     @SWG\Parameter(
     *      type="string",
     *      name="Authorization",
     *      in="header",
     *      default="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIyIiwiaXNzIjoiaHR0cDovL2FwaWxvY2FsLnBpeGVsc3RhaXJzLmNvbTo4MDgwL3YxL21lbWJlcnMvc2lnbmluIiwiaWF0IjoxNTA1NTQxMDE5LCJleHAiOjE1MDU1NDQ2MTksIm5iZiI6MTUwNTU0MTAxOSwianRpIjoiekFwOWlUSmdjTlBOYnRociJ9.NdK7NHJ98U3nMqSraJMpnr10cd1cz3EbZHyaFLWMlKc",
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
        $signdrop = $this->user->signdrop()->create([]);

        foreach( $request->answerIds as $answerId ){
            $signdrop->signdropSurvey()->create([
                'signdrop_answer_id' => $answerId
            ]);
        }

        if($this->user->delete()){
            return response()->success();
        }
        return Abort::Error('0040');
    }

    /**
     * @SWG\Post(
     *   path="/members/exists/email",
     *   summary="emailExist",
     *   operationId="emailExist",
     *   tags={"/Members/Auth"},
     *     @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     description="Sign in into web site",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/auth/emailExist")
     *   ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function emailExist(AuthEmailExistRequest $request)
    {
        $result = User::isAvailableEmail($request->email) === false;
        return response()->success($result);
    }

    /**
     * @SWG\Post(
     *   path="/members/exists/nickname",
     *   summary="nicknameExist",
     *   operationId="nicknameExist",
     *   tags={"/Members/Auth"},
     *     @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     description="Sign in into web site",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/auth/nicknameExist")
     *   ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function nicknameExist(AuthNicknameExistRequest $request)
    {
        $result = User::isAvailableNickname($request->nickname) === false;
        return response()->success($result);
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

        Auth::onceUsingId($testUserId);
        AccessToken::createToken();
        Auth::user()->token()->first()->update([
            "token" => "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIyIiwiaXNzIjoiaHR0cDovL2FwaWxvY2FsLnBpeGVsc3RhaXJzLmNvbTo4MDgwL3YxL21lbWJlcnMvc2lnbmluIiwiaWF0IjoxNTA1NTQxMDE5LCJleHAiOjE1MDU1NDQ2MTksIm5iZiI6MTUwNTU0MTAxOSwianRpIjoiekFwOWlUSmdjTlBOYnRociJ9.NdK7NHJ98U3nMqSraJMpnr10cd1cz3EbZHyaFLWMlKc",
        ]);
        return response()->success(true);
    }

    /**
     * @SWG\Post(
     *   path="/test/mail/signup",
     *   summary="mail test",
     *   operationId="mail test",
     *   tags={"/Test"},
     *   @SWG\Parameter(
     *         name="email",
     *         in="body",
     *         description="write your email",
     *         required=true,
     *         @SWG\Schema(
     *           @SWG\Property(property="email",default="bboydart91@gmail.com")
     *         )
     *     ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function signupTest(Request $request)
    {
        $this->user = User::getFromEmail($request->email);
        $token = $this->user->insertAccessToken();
        $this->dispatch(new SignupMailSendJob($this->user));
        return response()->success([
            "user" => $this->user,
            "token" => $token
        ]);
    }
}
