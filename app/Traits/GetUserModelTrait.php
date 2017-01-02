<?php
namespace App\Traits;

use App\Models\User;
use App\Models\SignupAllow;
use Log;

trait GetUserModelTrait{
    function getUserToken($request){
        return $request->header('X-lubycon-token');
    }

    function getUserByTokenRequestOrFail($request){
        $userId = $this->findUserIdByToken($this->getUserToken($request));
        $user = $this->getUserModelOrFail($userId);

        return $user;
    }

    function getUserByToken($token){
        $userId = $this->findUserIdByToken($token);
        $user = $this->getUserModel($userId);

        return $user;
    }

    function getUserByTokenOrFail($token){
        $userId = $this->findUserIdByToken($token);
        $user = $this->getUserModelOrFail($userId);

        return $user;
    }

    function findUserIdByToken($token){
        $tokenData = (object)array(
            "device" => substr($token, 0, 1),
            "token" => substr($token, 1, 30),
            "id" => substr($token, 31),
        );

        return $tokenData->id;
    }
    function getUserModel($userId){
        $user = User::find($userId);
        return $user;
    }

    function getUserModelOrFail($userId){
        $user = User::findOrFail($userId);
        return $user;
    }

    function getUserModelByEmailOrFail($email){
        $user = User::whereemail($email)->firstOrFail();
        return $user;
    }

    function getSignupToken($email){
        return SignupAllow::whereEmail($email)->value('token');
    }
}
?>
