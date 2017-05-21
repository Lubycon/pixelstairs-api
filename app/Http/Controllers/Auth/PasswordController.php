<?php

namespace App\Http\Controllers\Auth;

// Global
use Log;
use Abort;

// Requeire
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

// Models
use App\Models\User;
use App\Models\PasswordReset;

// Jobs
use App\Jobs\Mails\PasswordReMinderSendMailJob;

// Reqeust
use App\Http\Requests\Auth\Password\PasswordPostMailRequest;
use App\Http\Requests\Auth\Password\PasswordResetRequest;
use App\Http\Requests\Auth\Password\PasswordCheckCodeRequest;
use App\Http\Requests\Auth\Password\PasswordGetDiffTimeRequest;
use App\Http\Requests\Auth\Password\PasswordChangePasswordRequest;


class PasswordController extends Controller
{
    public $user;
    public $passwordReset;

    public function __construct()
    {
        $this->user = User::class;
        $this->passwordReset = PasswordReset::class;
    }

    /**
     * @SWG\Put(
     *   path="/mail/password",
     *   summary="mail",
     *   operationId="mail",
     *   tags={"/Mail"},
     *     @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/mail/password/postMail")
     *   ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    public function postMail(PasswordPostMailRequest $request)
    {
        $this->user = User::getFromEmail($request->email);
        $this->dispatch(new PasswordReMinderSendMailJob($this->user));
        return response()->success();
    }

    /**
     * @SWG\Put(
     *   path="/members/password/reset",
     *   summary="mail",
     *   operationId="mail",
     *   tags={"/Members/Password"},
     *     @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/password/reset")
     *   ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    public function reset(PasswordResetRequest $request)
    {
        $this->passwordReset = PasswordReset::getByToken($request->code);
        $this->passwordReset->expiredCheck();
        $this->user = $this->passwordReset->user;
        $credentials = [
            "email" => $this->user->email,
            "password" => $request->newPassword,
            "password_confirmation" => $request->newPassword,
            "token" => $request->code
        ];

        $response = Password::reset($credentials, function ($user, $password) {
            $this->resetPassword($user, $password);
        });

        switch ($response) {
            case Password::PASSWORD_RESET:
                return response()->success();
            default:
                Abort::Error('0040');
        }
    }

    /**
     * @SWG\Post(
     *   path="/certs/password/time",
     *   summary="mail",
     *   operationId="mail",
     *   tags={"/Certs/Password"},
     *     @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/password/getDiffTime")
     *   ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function getDiffTime(PasswordGetDiffTimeRequest $request){
        $this->passwordReset = PasswordReset::getByEmail($request->email);
        $diffTime = $this->passwordReset->getDiffTime();
        return response()->success([
            "time" => $diffTime
        ]);
    }

    /**
     * @SWG\Post(
     *   path="/certs/password/code",
     *   summary="mail",
     *   operationId="mail",
     *   tags={"/Certs/Password"},
     *     @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/password/checkCode")
     *   ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function checkCode(PasswordCheckCodeRequest $request){
        $this->passwordReset = PasswordReset::getByToken($request->code);
        $this->passwordReset->expiredCheck();

        return response()->success([
            "validity" => $request->code === $this->passwordReset->token
        ]);
    }

    /**
     * @SWG\Post(
     *   path="/certs/password",
     *   summary="mail",
     *   operationId="mail",
     *   tags={"/Certs/Password"},
     *     @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/password/checkPassword")
     *   ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function checkPassword(PasswordChangePasswordRequest $request){
        $this->user = User::getAccessUser();

        $credentials = [
            'email'    => $this->user->email,
            'password' => $request->password
        ];

        return response()->success([
            "validity" => Auth::once($credentials)
        ]);
    }

    protected function resetPassword($user, $password)
    {
        $user->password = bcrypt($password);
        $user->save();
    }
}
