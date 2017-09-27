<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MemberPasswordTest extends TestCase
{
    private $prefix = "/v1/members/password/";
    public $user;
    public $token;
    public $headers;
    public $invalidHeaders;

    public function __setup(){
        $this->user = factory(App\Models\User::class)->create();
        $this->token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($this->user);
        $this->headers = [
            'Authorization' => 'Bearer ' . $this->token
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

        $this->postTokenSuccess();
        $this->postTokenWhenGhost();

        $this->postMailSuccess();
        $this->postMailWhenUser();
        $this->postMailInvalid();
        $this->postMailNotFound();

        $this->resetWithTokenSuccess();
    }

    public function postTokenSuccess(){
        $this->json('POST', $this->prefix."token" , [
        ],$this->headers)
            ->assertResponseStatus(200)
            ->seeJsonStructure([
                'result' =>[
                    'token',
                ]
            ]);
        Auth::logout();
    }
    public function postTokenWhenGhost(){
        $this->json('POST', $this->prefix."token" , [
        ])
            ->assertResponseStatus(403);
        Auth::logout();
    }


    public function postMailSuccess(){
        $this->json('POST', $this->prefix."mail" , [
            "email" => $this->user->email
        ])
            ->assertResponseStatus(200);
        Auth::logout();
    }
    public function postMailWhenUser(){
        $this->json('POST', $this->prefix."mail" , [
            "email" => $this->user->email
        ],$this->headers)
            ->assertResponseStatus(403);
        Auth::logout();
    }
    public function postMailInvalid(){
        $this->json('POST', $this->prefix."mail" , [
        ])
            ->assertResponseStatus(422);
        Auth::logout();
    }
    public function postMailNotFound(){
        $this->json('POST', $this->prefix."mail" , [
            "email" => mt_rand(10000,20000)."@naver.com"
        ])
            ->assertResponseStatus(404);
        Auth::logout();
    }

    public function resetWithTokenSuccess(){
        $token = App\Models\PasswordReset::orderBy('id','desc')->first();
        $this->json('PUT', $this->prefix."reset" , [
            "code" => $token->token,
            "newPassword" => "password1234!"
        ])
            ->assertResponseStatus(200);
        Auth::logout();
    }
    public function resetWithTokenInvalid(){
        $token = App\Models\PasswordReset::orderBy('id','desc')->first();
        $this->json('PUT', $this->prefix."reset" , [
            "code" => "wrong!",
            "newPassword" => "password1234!"
        ])
            ->assertResponseStatus(422);
        $this->json('PUT', $this->prefix."reset" , [
            "code" => $token->token,
            "newPassword" => "12341234"
        ])
            ->assertResponseStatus(422);
        $this->json('PUT', $this->prefix."reset" , [
        ])
            ->assertResponseStatus(422);
    }

}
