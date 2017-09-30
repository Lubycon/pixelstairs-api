<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CertsSignupTest extends TestCase
{
    private $prefix = "/v1/certs/signup/";
    public $user;
    public $token;
    public $headers;
    public $testUserHeaders;
    public $invalidHeaders;

    public function __setup(){
        $this->user = factory(App\Models\User::class)->create();
        $this->user->update([
            "status" => "inactive",
        ]);
        $this->token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($this->user);
        $this->headers = [
            'Authorization' => 'Bearer ' . $this->token
        ];
        $this->testUserHeaders = [
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIyIiwiaXNzIjoiaHR0cDovL2FwaWxvY2FsLnBpeGVsc3RhaXJzLmNvbTo4MDgwL3YxL21lbWJlcnMvc2lnbmluIiwiaWF0IjoxNTA2MjQyNzU2LCJleHAiOjI0OTc3OTA1MTcwMTA5ODg3NTYsIm5iZiI6MTUwNjI0Mjc1NiwianRpIjoiNGFGVDV5VEtlaTdiVDVtWiJ9.AcYrVZBkvIepPi66IGUG0RMHDiv2b93kEEet3Ie0loU',
        ];
        $this->invalidHeaders = [
            'Authorization' => 'Bearer InvalidToken'
        ];
    }
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->__setup();

        // Signup Resend Mail
        $this->certsSignupResendMailSuccess();
        $this->certsSignupResendMailWhenGhost();
        $this->certsSignupResendMailWhenActiveUser();

        // Get limit time
        $this->certsSignupTimeSuccess();
        $this->certsSignupTimeWhenGhost();
        $this->certsSignupTimeWhenActive();

        // Signup Certs code
        $this->certsSignupCodeInvalid();
        $this->certsSignupCodeWhenActiveUser();
        $this->certsSignupCodeSuccessWhenGhost($this->getSignupCode());
        $this->certsSignupCodeSuccessWhenInactiveUser($this->getSignupCode());
    }

    public function certsSignupResendMailSuccess(){
        $this->json('POST', $this->prefix."mail",[
        ],$this->headers)
            ->assertResponseStatus(200);
        Auth::logout();
    }

    public function certsSignupResendMailWhenGhost(){
        $this->json('POST', $this->prefix."mail",[
        ])
            ->assertResponseStatus(403);
        Auth::logout();
    }

    public function certsSignupResendMailWhenActiveUser(){
        $this->json('POST', $this->prefix."mail",[
        ],$this->testUserHeaders)
            ->assertResponseStatus(403);
        Auth::logout();
    }

    public function certsSignupCodeWhenActiveUser(){
        $this->json('POST', $this->prefix."code",[
            "code" => "I dont care this code~"
        ],$this->testUserHeaders)
            ->assertResponseStatus(403);
        Auth::logout();
    }

    public function certsSignupCodeInvalid(){
        // Wrong code
        $this->json('POST', $this->prefix."code",[
            "code" => "hihi"
        ],$this->headers)
            ->assertResponseStatus(200)
            ->seeJsonEquals([
                "status" => [
                    "code" => "0000",
                    "devMsg" => "",
                    "msg" => "success"
                ],
                "result" => [
                    "validity" => false
                ],
            ]);
        Auth::logout();

        // None code
        $this->json('POST', $this->prefix."code",[
        ],$this->headers)
            ->assertResponseStatus(422);
        Auth::logout();
    }

    public function certsSignupCodeSuccessWhenGhost($tokens){
        $this->json('POST', $this->prefix."code",[
            "code" => $tokens->signup_token
        ])
            ->assertResponseStatus(200);
        Auth::logout();
    }

    public function certsSignupCodeSuccessWhenInactiveUser($tokens){
        $this->json('POST', $this->prefix."code",[
            "code" => $tokens->signup_token
        ],['Authorization' => 'Bearer ' . $tokens->access_token])
            ->assertResponseStatus(200);
        Auth::logout();
    }

    public function getSignupCode(){
        $rand = mt_rand(10000,20000000);
        $this->json('POST', "/v1/members/signup" , [
              "email" => "nononoenofnd".$rand."@pixelstairs.com",
              "password" => "password1234!".$rand,
              "nickname" => "usernicks".$rand,
              "newsletterAccepted" => true,
              "termsOfServiceAccepted" => true
        ])
            ->assertResponseStatus(200);
        $user = App\Models\User::orderBy('id','desc')->first();
        $authToken = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);
        return (object)[
            'access_token' => $authToken,
            'signup_token' => $user->signupAllow->token,
        ];
    }


    public function certsSignupTimeSuccess(){
        $this->json('GET', $this->prefix."time",[
        ],$this->headers)
            ->assertResponseStatus(200);
        Auth::logout();
    }

    public function certsSignupTimeWhenGhost(){
        $this->json('GET', $this->prefix."time",[
        ])
            ->assertResponseStatus(403);
        Auth::logout();
    }

    public function certsSignupTimeWhenActive(){
        $this->json('GET', $this->prefix."time",[
        ],$this->testUserHeaders)
            ->assertResponseStatus(403);
        Auth::logout();
    }

}
