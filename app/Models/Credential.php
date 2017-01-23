<?php

namespace App\Models;

use Log;
use Abort;

class Credential
{
    protected static function signin($data){

      $credential = [
          'email'    => $data['email'],
          'password' => $data['password']
      ];

      return $credential;
    }

    protected static function signup($data){
      $credential = [
          'email' => $data['email'],
          'name' => $data['name'],
          'nickname' => $data['nickname'],
          'password' => bcrypt($data['password']),
          'grade' => 'normal',
          'position' => $data['position'],
      ];
      return $credential;

    }
}
