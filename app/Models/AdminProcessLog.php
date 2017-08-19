<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Log;

use App\Models\User;
use Auth;

/**
 * App\Models\AdminProcessLog
 *
 * @property int $id
 * @property int $user_id
 * @property string $user_ip
 * @property string $url
 * @property string $request_method
 * @property string $request_json
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminProcessLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminProcessLog whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminProcessLog whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminProcessLog whereRequestJson($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminProcessLog whereRequestMethod($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminProcessLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminProcessLog whereUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminProcessLog whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminProcessLog whereUserIp($value)
 * @mixin \Eloquent
 */
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
                "user_id" => Auth::id(),
                "user_ip" => $request->clientInfo['ip'],
                "url" => $request->fullUrl(),
                "request_method" => $method,
                "request_json" => json_encode($request->json()->all()),
            ]);
        }
        return true;
    }

}