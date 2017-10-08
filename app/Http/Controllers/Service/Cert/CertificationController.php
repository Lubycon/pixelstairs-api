<?php

namespace App\Http\Controllers\Service\Cert;

// Global
use App\Models\SignupAllow;
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
use App\Http\Requests\Service\Cert\CertCheckCodeRequest;
use App\Http\Requests\Service\Cert\CertGetDiffTimeRequest;
use App\Http\Requests\Service\Cert\CertCheckAccessRequest;

class CertificationController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    /**
     * @SWG\Post(
     *   path="/certs/signup/code",
     *   summary="code",
     *   operationId="code",
     *   tags={"/Certs/Signup"},
     *     @SWG\Parameter(
     *      type="string",
     *      name="Authorization",
     *      in="header",
     *      default="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIyIiwiaXNzIjoiaHR0cDovL2FwaWxvY2FsLnBpeGVsc3RhaXJzLmNvbTo4MDgwL3YxL21lbWJlcnMvc2lnbmluIiwiaWF0IjoxNTA2MjQyNzU2LCJleHAiOjI0OTc3OTA1MTcwMTA5ODg3NTYsIm5iZiI6MTUwNjI0Mjc1NiwianRpIjoiNGFGVDV5VEtlaTdiVDVtWiJ9.AcYrVZBkvIepPi66IGUG0RMHDiv2b93kEEet3Ie0loU",
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
        if(User::isGhost()) $this->user = SignupAllow::whereToken($request->code)->firstOrFail()->user;

        $validity = $this->user->checkSignupCode($request->code);
        if($validity) {
            $this->user->update([
                'status' => 'active'
            ]);
        }
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
     *      name="Authorization",
     *      in="header",
     *      default="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIyIiwiaXNzIjoiaHR0cDovL2FwaWxvY2FsLnBpeGVsc3RhaXJzLmNvbTo4MDgwL3YxL21lbWJlcnMvc2lnbmluIiwiaWF0IjoxNTA2MjQyNzU2LCJleHAiOjI0OTc3OTA1MTcwMTA5ODg3NTYsIm5iZiI6MTUwNjI0Mjc1NiwianRpIjoiNGFGVDV5VEtlaTdiVDVtWiJ9.AcYrVZBkvIepPi66IGUG0RMHDiv2b93kEEet3Ie0loU",
     *      required=true
     *     ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    public function getDiffTime(CertGetDiffTimeRequest $request)
    {
        $diffTime = $this->user->getSignupDiffTime();
        $result = $diffTime > 0
            ? $diffTime
            : 0;
        return response()->success([
            'unit' => 'second',
            'time' => $result
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
     *      name="Authorization",
     *      in="header",
     *      default="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIyIiwiaXNzIjoiaHR0cDovL2FwaWxvY2FsLnBpeGVsc3RhaXJzLmNvbTo4MDgwL3YxL21lbWJlcnMvc2lnbmluIiwiaWF0IjoxNTA2MjQyNzU2LCJleHAiOjI0OTc3OTA1MTcwMTA5ODg3NTYsIm5iZiI6MTUwNjI0Mjc1NiwianRpIjoiNGFGVDV5VEtlaTdiVDVtWiJ9.AcYrVZBkvIepPi66IGUG0RMHDiv2b93kEEet3Ie0loU",
     *      required=true
     *     ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    public function checkAccessToken(CertCheckAccessRequest $request)
    {
        return response()->success([
            'validity' => !is_null($this->user)
        ]);
    }
}