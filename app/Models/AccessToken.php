<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\User;

use Auth;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Str;

class AccessToken extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'token', 'expired_at',];
    protected $hidden = ['password'];
    protected $casts = [
        'id' => 'string',
    ];

    public static $randomLength = 50;

    public static function validToken($user_id, $token){
        return User::with('user')
            ->whereHas('user', function (Builder $query) use ($user_id) {
                $query->where('id', $user_id);
            })
            ->where('token', $token)
            ->exists();
    }

    public static function createToken(){
        $token = static::create([
            "user_id" => Auth::user()->id,
            "token" => static::generateToken(),
            "expired_at" => Carbon::now()->addHours(8),
        ]);
        return $token['token'];
    }

    public static function generateToken(){
        $device = app('request')->clientInfo['device']['typeCode'];
        $userId = Auth::user()->id;
        $deviceCode = $device;
        $randomStr = Str::random(static::$randomLength);
        return $deviceCode.$randomStr.$userId;
    }

    public function user() {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
