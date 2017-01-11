<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use DB;
use Auth;

use App\Models\User;
use App\Models\Credential;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;

use App\Http\Requests\Auth\AuthSigninRequest;
use App\Http\Requests\Auth\AuthSignupRequest;
use App\Http\Requests\Auth\AuthSigndropRequest;
use App\Http\Requests\Auth\AuthRetrieveRequest;
use Abort;

use App\Traits\GetUserModelTrait;

use App\Jobs\LastSigninTimeCheckerJob;

use Log;

class AuthController extends Controller
{
    use ThrottlesLogins,
        GetUserModelTrait;

    protected function signin(AuthSigninRequest $request)
    {
        $data = $request->json()->all();
        $credentials = Credential::signin($data);

        if(!Auth::once($credentials)){
            Abort::Error('0040','Login Failed, check email,password');
        }

        $this->dispatch(new LastSigninTimeCheckerJob(Auth::getUser()));

        if (Auth::user()->status == 'inactive'){
            return response()->success([
                'token' => Auth::user()->remember_token,
            ]);
        }

        if(Auth::user()->status == 'active'){
            CheckContoller::insertRememberToken(Auth::user()->id);
        }

        return response()->success([
            'token' => Auth::user()->remember_token,
            'grade' => Auth::user()->grade,
        ]);
    }


    protected function signout()
    {
        // need somthing other logic
    }

    protected function signup(AuthSignupRequest $request)
    {
        $data = $request->json()->all();
        $credentialSignup = Credential::signup($data);
        $credentialSignin = Credential::signin($data);

        if(User::create($credentialSignup)){
            if(Auth::once($credentialSignin)){
                $id = Auth::user()->getAuthIdentifier();
                $rememberToken = CheckContoller::insertRememberToken($id);
            }
            return response()->success([
                "token" => $rememberToken
            ]);
        }
    }

    protected function signdrop(AuthSigndropRequest $request)
    {
        $tokenData = CheckContoller::checkToken($request);

        $user = User::findOrFail($tokenData->id);
        $userExist = CheckContoller::checkUserExistById($tokenData->id);

        if($userExist){
            $user->delete();
            return response()->success();
        }else{
            Abort::Error('0040');
        };
    }

    protected function getList()
    {
        $list = User::all();
        $responseData = [];

        if(!is_null($list)){
            foreach ($list as $key => $value) {
                $responseData[] = (object)array(
                    "id" => $value->id,
                    "email" => $value->email,
                    "name" => $value->name,
                    "nickname" => $value->nickname,
                    "position" => $value->position,
                    "grade" => $value->grade
                );
            }
            return response()->success($responseData);
        }else{
            Abort::Error('0040');
        }
    }

    protected function simpleRetrieve(Request $request){
        $tokenData = CheckContoller::checkToken($request);

        $findUser = User::findOrFail($tokenData->id);
        $userExist = CheckContoller::checkUserExistById($tokenData->id);

        if($userExist){
            $result = (object)array(
                "id" => $findUser->id,
                "email" => $findUser->email,
                "name" => $findUser->name,
                "nickname" => $findUser->nickname,
                "position" => $findUser->position,
                "grade" => $findUser->grade
            );
            return response()->success($result);
        }else{
            Abort::Error('0040');
        }
    }

    protected function getRetrieve($id)
    {
        $findUser = User::findOrFail($id);
        $userExist = CheckContoller::checkUserExistById($id);

        if($userExist){
            return response()->success([
                "id" => $findUser->id,
                "email" => $findUser->email,
                "name" => $findUser->name,
                "nickname" => $findUser->nickname,
                "position" => $findUser->position,
                "grade" => $findUser->grade
            ]);
        }else{
            Abort::Error('0040');
        }
    }
    public function postRetrieve(AuthRetrieveRequest $request,$id)
    {
        $data = $request->json()->all();
        $tokenData = CheckContoller::checkToken($request);

        $findUser = User::find($tokenData->id);
        $userExist = CheckContoller::checkUserExistById($tokenData->id);

        if($userExist && $id == $findUser->id){
                $findUser->email = $data['email'];
                $findUser->nickname = $data['nickname'];
                $findUser->name = $data['name'];
                $findUser->password = bcrypt($data['password']);
                $findUser->position = $data['position'];
                $findUser->grade = $data['grade'];
                if($findUser->save()){
                    return response()->success($data);
                }
        }else{
            Abort::Error('0040');
        }
    }
}
