<?php

namespace App\Http\Controllers;

use Abort;
use DB;
use App\Http\Controllers\Auth\CheckContoller;
use Auth;
use Carbon\Carbon;

use App\Models\User;
use App\Models\SignupAllow;

use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\Certs\CertsSignupTokenRequest;
use App\Http\Requests\Certs\CertsPasswordLimitTimeRequest;
use App\Http\Requests\Certs\CertsPasswordTokenRequest;
use App\Http\Requests\Certs\CertsPasswordRequest;

class CertificateController extends Controller
{
    public function __construct()
    {
        $user;
    }

    protected function certToken(Request $request){
        $token = $request->header('X-lubycon-token');
        $this->user = User::where('remember_token','=',$token)->get();

        if(!$this->user->isempty()){
            return response()->success([
                "validity" => true
            ]);
        }else{
            return response()->success([
                "validity" => false
            ]);
        }
    }
    protected function certTokenTimeCheck(Request $request){
        $data = CheckContoller::checkToken($request);

        $createTime = SignupAllow::findOrFail($data->id)->created_at;
        $minutes = 360;
        $diffTime = $this->checkDiffTime($createTime,$minutes);

        return response()->success([
            "time" => $diffTime
        ]);
    }

    protected function certPasswordTimeCheck(CertsPasswordLimitTimeRequest $request){
        $data = $request->json()->all();

        $createTime = new Carbon(DB::table('password_resets')->where('email','=',$data['email'])->value('created_at'));

        $minutes = 30;
        $diffTime = $this->checkDiffTime($createTime,$minutes);

        return response()->success([
            "time" => $diffTime
        ]);
    }

    protected function checkDiffTime($createTime,$minutes){
        $nowTime = Carbon::now();
        $createTimeParse = $createTime->addMinutes($minutes);

        if($nowTime < $createTimeParse){
            return 0;
        }
        return $nowTime->diffInSeconds($createTimeParse);
    }

    protected function certSignupToken(CertsSignupTokenRequest $request){
        $code = $request->only('code');
        $validateToken = SignupAllow::wheretoken($code['code'])->first();

        if(!is_null($validateToken)){
            $user = User::whereemail($validateToken->email)->firstOrFail();
            $this->activeUser($user);
            $validateToken->delete();

            return response()->success([
                "validity" => true
            ]);
        }
        return response()->success([
            "validity" => false
        ]);
    }

    protected function certPasswordToken(CertsPasswordTokenRequest $request){ // edit
        $code = $request->only('code');
        $codeCheck = DB::table('password_resets')->where('token','=',$code['code'])->first();

        return var_dump($codeCheck);

        if(!is_null($codeCheck)){
            return response()->success([
                "validity" => true
            ]);
        }
        return response()->success([
            "validity" => false
        ]);
    }

    protected function certPassword(CertsPasswordRequest $request){
        $data = $request->json()->all();

        $dataToken = CheckContoller::checkToken($request);
        $user = User::find($dataToken->id);

        $credentials = [
            'email'    => $user->email,
            'password' => $data['password'],
            'remember_token' => $request->header('X-lubycon-token')
        ];

        if (Auth::once($credentials)) {
            return response()->success([
                "validity" => true
            ]);
        }
        return response()->success([
            "validity" => false
        ]);
    }

    protected function activeUser($user){
        $user->status = 'active';
        $user->save();
    }
}
