<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use Auth;

use App\Models\User;
use App\Models\Interest;
use App\Models\Credential;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Classes\FileUpload;

use Illuminate\Foundation\Auth\ThrottlesLogins;

use App\Http\Requests\Auth\AuthSigninRequest;
use App\Http\Requests\Auth\AuthSignupRequest;
use App\Http\Requests\Auth\AuthSigndropRequest;
use App\Http\Requests\Auth\AuthPostRetrieveRequest;
use Abort;

use App\Traits\GetUserModelTrait;
use App\Traits\InterestControllTraits;

use App\Jobs\LastSigninTimeCheckerJob;

use Log;

class AuthController extends Controller
{
    use ThrottlesLogins,
        GetUserModelTrait,
        InterestControllTraits;

    protected function signin(AuthSigninRequest $request)
    {
        $data = $request->json()->all();
        $credentials = Credential::signin($data);

        if(!Auth::once($credentials)) Abort::Error('0040','Login Failed, check email,password');

        if( $request->getHost() == env('APP_PROVISION_ADMIN_URL') ){
            if( Auth::user()->grade == 'normal' ) Abort::Error('0043');
        }

        $this->dispatch(new LastSigninTimeCheckerJob(Auth::getUser()));

        if (Auth::user()->status == 'inactive'){
            return response()->success([
                'token' => Auth::user()->remember_token,
            ]);
        }

        if(Auth::user()->status == 'active') CheckContoller::insertRememberToken(Auth::user()->id);


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

        if( $user =  User::create($credentialSignup)){
            $id = $user->getAuthIdentifier();
            $token = CheckContoller::insertRememberToken($id);
            return response()->success([
                "token" => $token
            ]);
        }
    }

    protected function signdrop(AuthSigndropRequest $request)
    {
        $user = User::findOrFail($request->memberId);

        if($user->delete()){
            return response()->success();
        }else{
            Abort::Error('0040');
        };
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
                "phone" => $findUser->phone,
                "grade" => $findUser->grade,
                "gender" => $findUser->gender_id,
                "profileImg" => $findUser->getImageObject($findUser),
                "birthday" => $findUser->birthday
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
                "phone" => $findUser->phone,
                "grade" => $findUser->grade,
                "gender" => $findUser->gender_id,
                "location" => [
                    "city" => $findUser->city,
                    "address1" => $findUser->address1,
                    "address2" => $findUser->address2,
                    "postCode" => $findUser->post_code,
                ],
                "likeCategory" => $findUser->getInterest(),
                "profileImg" => $findUser->getImageObject($findUser),
                "birthday" => $findUser->birthday
            ]);
        }else{
            Abort::Error('0040');
        }
    }
    public function postRetrieve(AuthPostRetrieveRequest $request,$id)
    {
        $data = $request->json()->all();
        $tokenData = CheckContoller::checkToken($request);
        $findUser = User::find($tokenData->id);
        $userExist = CheckContoller::checkUserExistById($tokenData->id);

        if($userExist && $id == $findUser->id){
            $findUser->email = $data['email'];
            $findUser->name = $data['name'];
            $findUser->password = bcrypt($data['password']);
            $findUser->city = $data['location']['city'];
            $findUser->address1 = $data['location']['address1'];
            $findUser->address2 = $data['location']['address2'];
            $findUser->post_code = $data['location']['postCode'];
            $findUser->gender_id = $data['gender'];
            $findUser->birthday = $data['birthday'];
            Interest::firstOrCreate($this->setNewInterest($findUser,$request['likeCategory']));
            $fileUpload = new FileUpload( $findUser,$data['profileImg'] ,'image' );
            $findUser->image_id = $fileUpload->getResult();
            if($findUser->save()){
                return response()->success($findUser);
            }
        }else{
            Abort::Error('0040');
        }
    }
}
