<?php

namespace App\Models;

use Log;
use Abort;
use \Carbon\Carbon;

class Credential
{
    public static function signin($data){
        if( Credential::isAdmin($data) ){
            $credential = [
                'email'    => $data['id'],
                'password' => $data['password']
            ];
        }else{
            $credential = [
                'phone'    => $data['id'],
                'password' => $data['password']
            ];
        }
      return $credential;
    }

    public static function signup($data){
      $credential = [
          'phone' => $data['phone'],
          'email' => $data['email'],
          'name' => $data['name'],
          'password' => bcrypt(env('COMMON_PASSWORD')),
          'grade' => 'admin',
          'gender_id' => $data['gender'],
          'position' => isset($data['position']) ? $data['position'] : NULL ,
          'birthday' => Carbon::parse($data['birthday'])->timezone(config('app.timezone'))->toDateTimeString(),
      ];
      return $credential;
    }


    public static function isAdmin($data){
        return strpos($data['id'],'@');
    }
}
