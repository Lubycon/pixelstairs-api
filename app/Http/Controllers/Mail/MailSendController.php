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
use App\Jobs\Mails\SignupReminderMailSendJob;
use App\Jobs\Mails\PasswordReMinderSendMailJob;

class MailSendController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->user = User::class;
    }

    public function resendSignup(Request $request){
        $this->dispatch(new SignupReminderMailSendJob($this->user));
        return response()->success();
    }
}
