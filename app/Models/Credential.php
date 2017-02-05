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
          'haitao_user_id' => isset($data['haitaoUserId']) ? $data['haitaoUserId'] : NULL ,
          'phone' => $data['phone'],
          'email' => $data['email'],
          'name' => $data['name'],
          'nickname' => isset($data['nickname']) ? $data['nickname'] : NULL,
          'password' => bcrypt($data['password']),
          'grade' => 'normal',
          'position' => isset($data['position']) ? $data['position'] : NULL ,
      ];
      return $credential;

    }

    public static function isAdmin($data){
        return strpos($data['id'],'@');
    }
}
