<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Country;
use App\Models\Occupation;

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
      $countryName = Country::where('alpha2Code','=',$data['country'])->value('id');
      if(!isset($countryName)) Abort::Error('0040','Check Reqeust country Alpha2Code');
      
      $credential = [
          'nickname' => $data['nickname'],
          'email' => $data['email'],
          'password' => bcrypt($data['password']),
          'sns_code' => $data['snsCode'],
          'country_id' => $countryName,
          'status' => 'inactive',
          'newsletter' => $data['newsletter']
      ];
      return $credential;

    }
}
