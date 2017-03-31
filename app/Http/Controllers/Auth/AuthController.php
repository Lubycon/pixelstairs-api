<?php

namespace App\Http\Controllers\Auth;

use Auth;

use App\Models\User;
use Abort;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Classes\FileUpload;

use Illuminate\Foundation\Auth\ThrottlesLogins;

use App\Http\Requests\Auth\AuthSigninRequest;
use App\Http\Requests\Auth\AuthSignupRequest;
use App\Http\Requests\Auth\AuthSigndropRequest;
use App\Http\Requests\Auth\AuthPostRetrieveRequest;

use App\Jobs\LastSigninTimeCheckerJob;

use Log;

class AuthController extends Controller
{
    use ThrottlesLogins;

    public $user;

    public function __construct()
    {
        $this->user = User::class;
    }

    protected function signin(AuthSigninRequest $request)
    {
        if(!Auth::once(User::bindSigninData($request))) Abort::Error('0061');
        $this->user = Auth::getUser();

//        $this->dispatch(new LastSigninTimeCheckerJob($this->user));

        if($this->user->status == 'active') $this->user->insertAccessToken();

        return response()->success([
            'token' => $this->user->token,
            'grade' => $this->user->status,
        ]);
    }


    protected function signout(Request $request)
    {
        $this->user = User::getAccessUser();
        $this->user->dropToken();
        return response()->success();
    }

    protected function signup(AuthSignupRequest $request)
    {
        $signupData = User::bindSignupData($request);

        if( $this->user =  User::create($signupData)){
            $this->user->createSignupToken();
            $token = $this->user->insertAccessToken();
            return response()->success([
                "token" => $token
            ]);
        }
    }

    protected function signdrop(AuthSigndropRequest $request)
    {
        // TODO : save user sign drop reasons...
        $this->user = User::getAccessUser();
        if($this->user->delete()){
            return response()->success();
        }else{
            Abort::Error('0040');
        };
    }

    protected function simpleRetrieve(Request $request){
        $this->user = User::getAccessUser();
        $result = [
            "id" => $this->user->id,
            "email" => $this->user->email,
            "nickname" => $this->user->nickname,
            "profileImg" => $this->user->getImageObject(),
        ];
        return response()->success($result);
    }

    protected function getRetrieve(Request $request,$user_id)
    {
        $this->user = User::findOrFail($user_id);
        return response()->success([
            "id" => $this->user->id,
            "email" => $this->user->email,
            "nickname" => $this->user->nickname,
            "profileImg" => $this->user->getImageObject(),
            "newsletterAccepted" => $this->user->newsletters_accepted,
        ]);
    }
    public function postRetrieve(Request $request)
    {
        // TODO : image upload
        $this->user = User::getAccessUser();

        try{
            $this->user->update([
                "nickname" => $request->nickname,
                "profileImg" => null,
                "newsletterAccepted" => $request->newsletters_accepted,
            ]);
            return response()->success($this->user);
        }catch (\Exception $e){
            Abort::Error('0040');
        }
    }
}
