<?php

namespace App\Http\Controllers\Cert;

// Global
use Log;
use Auth;
use Abort;

// Models
use App\Models\User;

// Require
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class CertificationController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->user = User::class;
    }

    public function checkCode(Request $request)
    {
        $this->user = User::getAccessUser();
        $validity = $this->user->checkSignupCode($request->code);
        return response()->success([
            'validity' => $validity
        ]);
    }

    public function getDiffTime(Request $request)
    {
        $this->user = User::getAccessUser();
        $diffTime = $this->user->getSignupDiffTime();
        return response()->success([
            'unit' => 'second',
            'time' => $diffTime
        ]);
    }

    public function checkAccessToken(Request $request)
    {
        try{
            $this->user = User::getAccessUser();
            $validity = true;
        }catch(\Exception $e){
            $validity = false;
        }
        return response()->success([
            'validity' => $validity
        ]);
    }
}