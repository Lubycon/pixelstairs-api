<?php

namespace App\Http\Controllers\Mail;

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
use App\Jobs\Mails\SignupMailSendJob;
use App\Jobs\Mails\PasswordReMinderSendMailJob;

// Request
use App\Http\Requests\Mail\MailRemindSignupRequest;

class MailSendController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->user = User::class;
    }

    public function sendSignup(MailRemindSignupRequest $request){
        $this->user = User::getAccessUser();
        $this->dispatch(new SignupMailSendJob($this->user));
        return response()->success();
    }
}
