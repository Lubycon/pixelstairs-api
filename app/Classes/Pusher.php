<?php
namespace App\Classes;

use App\Models\User;
use App\Events\NotificationEvent;
use Log;

// Developing Pusher Class
// $pusher = new Pusher('personal','mailSendFail',Auth::user(),[]);

class Pusher{

    private $eventNamespace = 'App\Events\\';

    public $type;
    public $channel;
    public $event;

    public $user;
    public $data;

    public function __construct($type,$event,User $user,$data){
        $this->type = $type;
        $this->user = $user;

        $this->channel = $this->getChannel($this->type);
        $this->event = $this->getEvent($event);
        $this->data = $data;

        $this->eventFire();
    }

    public function getChannel($type){
        switch ($type) {
            case 'personal' : $result = 'lubycon-user-'.$this->user->id ; break;
            case 'public' : $result = 'lubycon-public'; break;
            default : $result = "error" ; break;
        }
        return $result;
    }
    public function getEvent($event){
        switch($event){
            case 'mailSendFail' : $result = 'NotificationEvent'; break;
            default : $result = 'error'; break;
        }
        return $result;
    }

    public function eventFire(){
        $className = $this->eventNamespace.$this->event;
        event(new $className(
            $this->channel,
            $this->event,
            $this->user,
            $this->data
        ));
    }


    // public function signin($user){
    //     event(new NotificationEvent($user,[
    //         "type" => "signin",
    //         "msg" => "User Signin Success!"
    //     ]));
    // }
}
