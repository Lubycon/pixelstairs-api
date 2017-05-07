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

// Request
use App\Http\Requests\Cert\CertCheckCodeRequest;
use App\Http\Requests\Cert\CertGetDiffTimeRequest;

class CertificationController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->user = User::class;
    }

    /**
     * @SWG\Post(
     *   path="/certs/signup/code",
     *   summary="code",
     *   operationId="code",
     *   tags={"/Certs/Signup"},
     *     @SWG\Parameter(
     *      type="string",
     *      name="X-pixel-token",
     *      in="header",
     *      default="wtesttesttesttesttesttesttestte2",
     *      required=true
     *     ),
     *     @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/certs/signup/checkCode")
     *   ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    public function checkCode(CertCheckCodeRequest $request)
    {
        $this->user = User::getAccessUser();
        $validity = $this->user->checkSignupCode($request->code);
        return response()->success([
            'validity' => $validity
        ]);
    }

    /**
     * @SWG\Post(
     *   path="/certs/signup/time",
     *   summary="code",
     *   operationId="code",
     *   tags={"/Certs/Signup"},
     *     @SWG\Parameter(
     *      type="string",
     *      name="X-pixel-token",
     *      in="header",
     *      default="wtesttesttesttesttesttesttestte2",
     *      required=true
     *     ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    public function getDiffTime(CertGetDiffTimeRequest $request)
    {
        $this->user = User::getAccessUser();
        $diffTime = $this->user->getSignupDiffTime();
        return response()->success([
            'unit' => 'second',
            'time' => $diffTime
        ]);
    }

    /**
     * @SWG\Post(
     *   path="/certs/token",
     *   summary="code",
     *   operationId="code",
     *   tags={"/Certs/Token"},
     *     @SWG\Parameter(
     *      type="string",
     *      name="X-pixel-token",
     *      in="header",
     *      default="wtesttesttesttesttesttesttestte2",
     *      required=true
     *     ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
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