<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\User;
use App\Traits\GetUserModelTrait;
use Mail;
use Log;

class SignupMailSendJob extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue,
        SerializesModels,
        GetUserModelTrait;

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle()
    {
        Log::info('mail send start');

        $to = $this->user->email;
        $subject = 'Account Success to Lubycon';
        $data = [
            'user' => $this->user,
            'token' => $this->getSignupToken($this->user->email)
        ];

        Mail::send("emails.signup", $data, function($message) use($to, $subject) {
            $message->to($to)->subject($subject);
        });

        Log::info('mail sended');
    }
}
