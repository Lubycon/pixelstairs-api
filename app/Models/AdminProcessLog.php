<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Log;

use App\Models\User;

class AdminProcessLog extends Model 
{
    use SoftDeletes;

    public $timestamps = true;
    protected $dates = ['deleted_at'];
    protected $fillable = ['user_id', 'user_ip', 'url', 'request_method','request_json'];


    public static function createLog($request){
        $method = $request->method();
        if( $method !== 'GET' ){
            return static::create([
                "user_id" => User::getAccessUser()->id,
                "user_ip" => $request->clientInfo['ip'],
                "url" => $request->fullUrl(),
                "request_method" => $method,
                "request_json" => json_encode($request->json()->all()),
            ]);
        }
        return true;
    }

}