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

    public function postMail(PasswordPostMailRequest $request)
    {
        $this->user = User::getFromEmail($request->email);
        $this->dispatch(new PasswordReMinderSendMailJob($this->user));
        return response()->success();
    }

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

    protected function getDiffTime(PasswordGetDiffTimeRequest $request){
        $this->passwordReset = PasswordReset::getByEmail($request->email);
        $diffTime = $this->passwordReset->getDiffTime();
        return response()->success([
            "time" => $diffTime
        ]);
    }

    protected function checkCode(PasswordCheckCodeRequest $request){
        $this->passwordReset = PasswordReset::getByToken($request->code);
        $this->passwordReset->expiredCheck();

        return response()->success([
            "validity" => $request->code === $this->passwordReset->token
        ]);
    }

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
