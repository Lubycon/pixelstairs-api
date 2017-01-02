<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ActivityController extends Controller
{
    var $pusher;
    var $user;

    public function __construct()
    {
        $this->pusher = App::make('pusher');
    }

    public function getIndex(){
        return view('test');
    }

    public function postNotifiy(){
        $this->pusher->trigger('my_channel', 'my_event', array('message' => 'hello world') );
    }

}
