<?php

namespace App\Jobs\Mails;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\User;
use Mail;
use Log;

class SignupMailSendJob extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue,
        SerializesModels;

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle()
    {
        Log::info('mail send start');

        $this->user->createSignupToken();

        $to = $this->user->email;
        $subject = 'Account Success to Pixelstairs';
        $data = [
            'user' => $this->user,
            'token' => $this->user->getSignupToken()
        ];

        Mail::send("emails.signup", $data, function($message) use($to, $subject) {
            $message->to($to)->subject($subject);
        });

        Log::info('mail sended');
    }
}
