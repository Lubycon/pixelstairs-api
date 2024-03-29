<?php

namespace App\Http\Controllers\Service\Auth;

// Global
use App\Models\RefreshToken;
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

// Exceptions
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

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

        if (! $access_token = JWTAuth::attempt($credentials)) {
            return Abort::Error('0061');
        }

        $this->user = Auth::user();
        $this->dispatch(new LastSigninTimeCheckerJob($this->user));
        $refresh_token = RefreshToken::createToken($access_token);

        return response()->success([
            'access_token' => $access_token,
            'refresh_token' => $refresh_token,
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
     *      default="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIyIiwiaXNzIjoiaHR0cDovL2FwaWxvY2FsLnBpeGVsc3RhaXJzLmNvbTo4MDgwL3YxL21lbWJlcnMvc2lnbmluIiwiaWF0IjoxNTA2MjQyNzU2LCJleHAiOjI0OTc3OTA1MTcwMTA5ODg3NTYsIm5iZiI6MTUwNjI0Mjc1NiwianRpIjoiNGFGVDV5VEtlaTdiVDVtWiJ9.AcYrVZBkvIepPi66IGUG0RMHDiv2b93kEEet3Ie0loU",
     *      required=true
     *     ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function signout(AuthSignoutRequest $request)
    {
        JWTAuth::invalidate();
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
            $access_token = JWTAuth::fromUser($this->user);
            $auth = JWTAuth::setToken($access_token)->authenticate();
            $refresh_token = RefreshToken::createToken($access_token);
            $this->dispatch(new SignupMailSendJob($this->user));
            return response()->success([
                'access_token' => $access_token,
                'refresh_token' => $refresh_token,
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
     *      default="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIyIiwiaXNzIjoiaHR0cDovL2FwaWxvY2FsLnBpeGVsc3RhaXJzLmNvbTo4MDgwL3YxL21lbWJlcnMvc2lnbmluIiwiaWF0IjoxNTA2MjQyNzU2LCJleHAiOjI0OTc3OTA1MTcwMTA5ODg3NTYsIm5iZiI6MTUwNjI0Mjc1NiwianRpIjoiNGFGVDV5VEtlaTdiVDVtWiJ9.AcYrVZBkvIepPi66IGUG0RMHDiv2b93kEEet3Ie0loU",
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
            JWTAuth::invalidate();
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
     * @SWG\Get(
     *   path="/members/token/refresh",
     *   summary="Refresh access token",
     *   operationId="refreshAccessToken",
     *   tags={"/Members/Auth"},
     *     @SWG\Parameter(
     *      type="string",
     *      name="Authorization",
     *      in="header",
     *      default="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIyIiwiaXNzIjoiaHR0cDovL2FwaWxvY2FsLnBpeGVsc3RhaXJzLmNvbTo4MDgwL3YxL21lbWJlcnMvc2lnbmluIiwiaWF0IjoxNTA2MjQyNzU2LCJleHAiOjI0OTc3OTA1MTcwMTA5ODg3NTYsIm5iZiI6MTUwNjI0Mjc1NiwianRpIjoiNGFGVDV5VEtlaTdiVDVtWiJ9.AcYrVZBkvIepPi66IGUG0RMHDiv2b93kEEet3Ie0loU",
     *      required=true
     *     ),
     *     @SWG\Parameter(
     *      type="string",
     *      name="x-pixel-refresh-token",
     *      in="header",
     *      default="dpMqeFNmM1x1g0akHDsGYyWcKM5kfpYnHxi2r9KsSSQThrD3E9l2",
     *      required=true
     *     ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function refreshAccessToken(Request $request)
    {
        $new_access_token = null;
        $refresh_token = null;

        try {
            if (!$this->user = JWTAuth::parseToken()->authenticate()) Abort::Error('0054');
        } catch (TokenExpiredException $e) {
            // pass!
        } catch (TokenInvalidException $e) {
            Abort::Error('0061',$e);
        } catch (JWTException $e) {
            Abort::Error('0061',$e);
        }

        try {
            $expired_access_token = RefreshToken::getPureJWTToken();
            $payload = RefreshToken::getJWTTokenPayload($expired_access_token);
            $sub = $payload['sub'];
            $this->user = User::findOrFail($sub);
            $new_access_token = JWTAuth::refresh($expired_access_token);
            $refresh_token = RefreshToken::getValidToken();
        } catch (\Exception $e) {
            Abort::Error('0061',$e);
        }
        if( !is_null($refresh_token) ){
            $refresh_token->update([
                "jwt_token" => $new_access_token
            ]);
            return response()->success($new_access_token);
        }
        return Abort::Error('0062');
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
        $access_token = JWTAuth::fromUser($this->user);
        $auth = JWTAuth::setToken($access_token)->authenticate();
        $refresh_token = RefreshToken::createToken($access_token);
        $this->dispatch(new SignupMailSendJob($this->user));
        return response()->success([
            'access_token' => $access_token,
            'refresh_token' => $refresh_token,
        ]);
    }
}
