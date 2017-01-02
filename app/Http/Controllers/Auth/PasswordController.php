<?php

namespace App\Http\Controllers\Auth;

use Log;
use Abort;
use DB;
use Validator;
use App\Http\Controllers\MailSendController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

use App\Traits\GetUserModelTrait;
use App\Jobs\PasswordReMinderSendMailJob;

use App\Http\Requests\Password\PasswordPostMailRequest;
use App\Http\Requests\Password\PasswordResetRequest;

class PasswordController extends Controller
{
    use GetUserModelTrait;

    public function postEmail(PasswordPostMailRequest $request)
    {
        $email =  $request->only('email');
        $user = $this->getUserModelByEmailOrFail($email);
        $res = $this->dispatch(new PasswordReMinderSendMailJob($user));

        return response()->success();
    }

    public function postReset(PasswordResetRequest $request)
    {
        $data = $request->json()->all();

        $credentials = array(
            "email" => DB::table('password_resets')->where('token','=',$data['code'])->value('email'),
            "password" => $data['newPassword'],
            "password_confirmation" => $data['newPassword'],
            "token" => $data['code']
        );

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
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->password = bcrypt($password);
        $user->save();
    }
}
