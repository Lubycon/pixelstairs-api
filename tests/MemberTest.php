<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MemberTest extends TestCase
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

        // My Retrieve
        $this->meGetSuccess();
        $this->meGetWhenGhost();
        $this->mePutSuccess();
        $this->mePutWhenGhost();
        $this->mePutAnothorUser();
        $this->mePutInvalid();

        // Get Public Member Data
        $this->memberPublicRetrieveSuccess();
        $this->memberPublicRetrieveNotFound();
    }

    public function meGetSuccess(){
        $this->json('GET', $this->prefix."me" , [
        ],$this->headers)
            ->assertResponseStatus(200);
        Auth::logout();
    }

    public function meGetWhenGhost(){
        $this->json('GET', $this->prefix."me" , [
        ])
            ->assertResponseStatus(403);
        Auth::logout();
    }

    public function mePutSuccess(){
        $this->json('PUT', $this->prefix."me" , [
            "newsletterAccepted" => true,
            "nickname" => mt_rand(10000,20000),
        ],$this->headers)
            ->assertResponseStatus(200);
        Auth::logout();
    }
    public function mePutWhenGhost(){
        $this->json('PUT', $this->prefix."me" , [
            "newsletterAccepted" => true,
            "nickname" => "user_nickname",
        ])
            ->assertResponseStatus(403);
        Auth::logout();
    }
    public function mePutAnothorUser(){
        // TODO GET anothor users token
        $this->json('PUT', $this->prefix."me" , [
            "newsletterAccepted" => true,
            "nickname" => "user_nickname",
        ])
            ->assertResponseStatus(403);
        Auth::logout();
    }

    public function mePutInvalid(){
        $this->json('PUT', $this->prefix."me" , [
        ],$this->headers)
            ->assertResponseStatus(422);
        Auth::logout();
        $this->json('PUT', $this->prefix."me" , [
            "newsletterAccepted" => "HELLO_ERROR",
            "nickname" => "user_nickname",
        ],$this->headers)
            ->assertResponseStatus(422);
        Auth::logout();
        $this->json('PUT', $this->prefix."me" , [
            "newsletterAccepted" => true,
        ],$this->headers)
            ->assertResponseStatus(422);
        Auth::logout();
        $this->json('PUT', $this->prefix."me" , [
            "nickname" => "user_nickname",
        ],$this->headers)
            ->assertResponseStatus(422);
        Auth::logout();
    }

    public function memberPublicRetrieveSuccess(){
        $this->json('GET', $this->prefix."1" , [
        ])
            ->assertResponseStatus(200);
        Auth::logout();
    }

    public function memberPublicRetrieveNotFound(){
        $this->json('GET', $this->prefix."10000000000000" , [
        ])
            ->assertResponseStatus(404);
        Auth::logout();
    }
}
