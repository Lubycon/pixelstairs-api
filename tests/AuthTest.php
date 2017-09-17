<?php

use Log;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthTest extends TestCase
{
    private $prefix = "/v1/members/";
    public $user;
    public $token;
    public $headers;

    public function __setup(){
        $this->user = factory(App\Models\User::class)->create();
        $this->token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($this->user);
        $this->headers = [
            'Authorization' => 'Bearer ' . $this->token
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
        $this->signinSuccess();
        $this->signinFail();
//        $this->signout();
    }

    public function signinSuccess(){
        $this->json('POST', $this->prefix."signin" , [
            'email' => 'test@pixelstairs.com',
            'password' => '1234qwer',
        ])
            ->assertResponseStatus(200)
            ->seeJsonStructure([
                'result' => [
                    'token',
                    'grade',
                    'status',
                ],
            ]);
        Auth::logout();
    }
    public function signinFail(){
        $this->json('POST', $this->prefix."signin" , [
            'email' => 'test@pixelstairs.com',
            'password' => 'helloworld',
        ])
            ->assertResponseStatus(401);
        Auth::logout();
    }


    public function signout(){
        Log::info($this->headers);
        $this->json('PUT', $this->prefix."signout" , [
        ],$this->headers)
            ->assertResponseStatus(200)
            ->seeJsonStructure([
            ]);
        Auth::logout();
    }
}
