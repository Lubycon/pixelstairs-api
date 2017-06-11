<?php

namespace App\Jobs\Mails;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Log;
use Password;
use App\Models\User;
use Illuminate\Mail\Message;

class PasswordReMinderSendMailJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $subject = '잃어버린 비밀번호를 찾아서';
        $data = [
            "email" => $this->user->email
        ];
        
        Password::sendResetLink($data, function (Message $message) use($subject) {
            $message->subject($subject);
        });
    }
}
