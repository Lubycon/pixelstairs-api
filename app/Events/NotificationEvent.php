<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use Log;

use App\Models\User;

class NotificationEvent extends Event implements ShouldBroadcast
{
    use SerializesModels;

    public $user;
    public $msg;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user,$msg)
    {
        $this->user = $user;
        $this->msg = $msg;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['lubycon-user-'.$this->user->id];
    }
    public function broadcastAs()
    {
        return 'notifications';
    }
}
