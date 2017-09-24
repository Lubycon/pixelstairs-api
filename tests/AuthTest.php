<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthTest extends TestCase
{
    private $prefix = "/v1/members/";
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

        // Signin
        $this->signinSuccess();
        $this->signinWhenUser();
        $this->signinInvalid();

        // Signout
        $this->signoutSuccess();
        $this->signoutWhenGhost();
        $this->signoutInvalid();
        $this->signoutWithoutToken();

        // Signup
        $this->signupSuccess();
        $this->signupWhenUser();
        $this->signupInvalid();


        // Signdrop
        $this->signdropWhenGhost();
        $this->signdropInvalid();
        $this->signdropSuccess();
    }

    public function signinSuccess(){
        $this->json('POST', $this->prefix."signin" , [
            'email' => 'test@pixelstairs.com',
            'password' => '1234qwer',
        ])
            ->assertResponseStatus(200);
        Auth::logout();
    }
    public function signinWhenUser(){
        $this->json('POST', $this->prefix."signin" , [
            'email' => 'test@pixelstairs.com',
            'password' => '1234qwer',
        ],$this->headers)
            ->assertResponseStatus(403);
        Auth::logout();
    }
    public function signinInvalid(){
        $this->json('POST', $this->prefix."signin" , [
            'email' => 'test@pixelstairs.com',
            'password' => 'helloworld',
        ])
            ->assertResponseStatus(401);
        Auth::logout();
    }


    public function signoutSuccess(){
        $this->json('PUT', $this->prefix."signout" , [
        ],$this->headers)
            ->assertResponseStatus(200);
        Auth::logout();
    }

    public function signoutWhenGhost(){
        $this->json('PUT', $this->prefix."signout" , [
        ])
            ->assertResponseStatus(403);
        Auth::logout();
    }

    public function signoutInvalid(){
        $this->json('PUT', $this->prefix."signout" , [
        ],$this->invalidHeaders)
            ->assertResponseStatus(401);
        Auth::logout();
    }


    public function signoutWithoutToken(){
        $this->json('PUT', $this->prefix."signout" , [
        ])
            ->assertResponseStatus(403);
        Auth::logout();
    }


    public function signupSuccess(){
        $rand = mt_rand(10000,20000);
        $this->json('POST', $this->prefix."signup" , [
              "email" => "nononoenofnd".$rand."@pixelstairs.com",
              "password" => "password1234!".$rand,
              "nickname" => "usernicks".$rand,
              "newsletterAccepted" => true,
              "termsOfServiceAccepted" => true
        ])
            ->assertResponseStatus(200);
        Auth::logout();
    }


    public function signupWhenUser(){
        $this->json('POST', $this->prefix."signup" , [
            "email" => "nononoenofnd@pixelstairs.com",
            "password" => "password1234!",
            "nickname" => "usernicks",
            "newsletterAccepted" => true,
            "termsOfServiceAccepted" => true
        ],$this->headers)
            ->assertResponseStatus(403);
        Auth::logout();
    }

    public function signupInvalid(){
        $this->json('POST', $this->prefix."signup" , [
            "email" => "nonopixelstairs.com",
            "password" => "pass!",
            "nickname" => "admin",
            "newsletterAccepted" => false,
            "termsOfServiceAccepted" => false,
        ])
            ->assertResponseStatus(422);
        Auth::logout();
    }


    public function signdropSuccess(){
        $this->json('DELETE', $this->prefix."signdrop" , [
            "answerIds" => [1,7]
        ],$this->headers)
            ->assertResponseStatus(200);
    }

    public function signdropWhenGhost(){
        $this->json('DELETE', $this->prefix."signdrop" , [
            "answerIds" => [1,7]
        ])
            ->assertResponseStatus(403);
    }

    public function signdropInvalid(){
        $this->json('DELETE', $this->prefix."signdrop" , [
        ],$this->headers)
            ->assertResponseStatus(422);
        
        $this->json('DELETE', $this->prefix."signdrop" , [
            "answerIds" => 100
        ],$this->headers)
            ->assertResponseStatus(422);
    }
}
