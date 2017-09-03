<?php

namespace App\Http\Controllers\Service\Mail;

// Global
use Log;
use Auth;
use Abort;

// Models
use App\Models\User;

// Require
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

//Jobs
use App\Jobs\Mails\SignupReminderMailSendJob;

// Request
use App\Http\Requests\Service\Mail\MailRemindSignupRequest;

class MailSendController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    /**
     * @SWG\Post(
     *   path="/certs/signup/mail",
     *   summary="remind",
     *   operationId="remind",
     *   tags={"/Certs/Signup"},
     *     @SWG\Parameter(
     *      type="string",
     *      name="X-pixel-token",
     *      in="header",
     *      default="wQWERQWERQWERQWERQWERQWERQWERQWERQWERQWERQWERQWERQW2",
     *      required=true
     *     ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    public function resendSignup(MailRemindSignupRequest $request){
        $this->dispatch(new SignupReminderMailSendJob($this->user));
        return response()->success();
    }
    /**
     * @SWG\Post(
     *   path="/test/mail/signup/remind",
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
     *   ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    // this test method not validate token
    public function resendSignupTest(Request $request){
        $this->user = User::getFromEmail($request->email);
        $this->dispatch(new SignupReminderMailSendJob($this->user));
        return response()->success();
    }
}
