<?php

namespace App\Http\Controllers\Member;

// Global
use Log;
use Abort;

// Models
use App\Models\User;

// Require
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// Class
use App\Classes\FileUpload;

// Requests
use App\Http\Requests\Member\MemberPostRetrieveRequest;

class MemberController extends Controller
{
    public $user;
    public $uploader;

    public function __construct()
    {
        $this->user = User::class;
        $this->uploader = new FileUpload();
    }

    protected function simpleRetrieve(Request $request){
        $this->user = User::getAccessUser();
        $result = $this->user->getSimpleInfo();
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
    public function postRetrieve(MemberPostRetrieveRequest $request)
    {
        $this->user = User::getAccessUser();
        try{
            $this->user->update([
                "nickname" => $request->nickname,
                "image_id" => $this->uploader->upload(
                    $this->user,
                    $request->profileImg
                )->getId(),
                "newsletters_accepted" => $request->newsletterAccepted,
            ]);
        }catch (\Exception $e){
            Abort::Error('0040');
        }
        return response()->success($this->user);
    }
}