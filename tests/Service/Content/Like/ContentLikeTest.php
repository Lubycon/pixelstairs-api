<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ContentLikeTest extends TestCase
{
    public $prefix = "/v1/contents/";
    public $wrongPrefix = "/v1/contents/";
    public $user;
    public $anotherUser;
    public $anotherUserHeaders;
    public $token;
    public $headers;
    public $testUserHeaders;
    public $invalidHeaders;
    public $testCommentId;

    public function __setup(){
        $this->user = factory(App\Models\User::class)->create();
        $this->user->update([
            "status" => "inactive",
        ]);
        $this->anotherUser = factory(App\Models\User::class)->create();
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($this->anotherUser);
        $this->anotherUserHeaders = [
            'Authorization' => 'Bearer ' . $token
        ];
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

        $testContent = App\Models\Content::orderBy('id','desc')->first();
        $this->prefix = $this->prefix.$testContent->id.'/like/';
        $this->wrongPrefix = $this->prefix.'99999999/like/';
    }
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->__setup();

        $this->postSuccess();
        $this->postNotfound();
        $this->postWhenGhost();
        $this->postWhenInActiveUser();

        $this->deleteNotFound();
        $this->deleteWhenGhost();
        $this->deleteWhenInActiveUser();
        $this->deleteSuccess(); // delete last
    }

    public function postSuccess(){
        $this->json('POST', $this->prefix , [
        ],$this->testUserHeaders)
            ->assertResponseStatus(200);
        Auth::logout();
    }

    public function postNotFound(){
        $this->json('POST', $this->wrongPrefix , [
        ],$this->testUserHeaders)
            ->assertResponseStatus(404);
        Auth::logout();
    }

    public function postWhenGhost(){
        $this->json('POST', $this->prefix , [
        ])
            ->assertResponseStatus(403);
        Auth::logout();
    }

    public function postWhenInActiveUser(){
        $this->json('POST', $this->prefix , [
        ],$this->headers)
            ->assertResponseStatus(403);
        Auth::logout();
    }

    public function deleteNotFound(){
        $this->json('DELETE', $this->wrongPrefix , [
        ],$this->testUserHeaders)
            ->assertResponseStatus(404);
        Auth::logout();
    }

    public function deleteWhenGhost(){
        $this->json('DELETE', $this->prefix , [
        ])
            ->assertResponseStatus(403);
        Auth::logout();
    }

    public function deleteWhenInActiveUser(){
        $this->json('DELETE', $this->prefix , [
        ],$this->headers)
            ->assertResponseStatus(403);
        Auth::logout();
    }

    public function deleteSuccess(){
        $this->json('DELETE', $this->prefix , [
        ],$this->testUserHeaders)
            ->assertResponseStatus(200);
        Auth::logout();
    }

}
