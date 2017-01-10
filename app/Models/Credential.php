<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Log;
use Abort;

class Credential extends Model
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
          'grade' => 'user',
          'position' => $data['position']
      ];
      return $credential;

    }
}
