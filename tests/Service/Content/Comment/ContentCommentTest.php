<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ContentCommentTest extends TestCase
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
        $this->prefix = $this->prefix.$testContent->id.'/comments/';
        $this->wrongPrefix = $this->prefix.'99999999/comments/';
    }
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->__setup();

        // Get
        $this->getListSuccess();
        $this->getListNotFound();

        $this->postSuccess();
        $this->postInvalid();
        $this->postWhenGhost();
        $this->postWhenInActiveUser();

        $testComment = App\Models\Comment::orderBy('id','desc')->first();
        $this->testCommentId = $testComment->id;

        $this->putSuccess();
        $this->postNotFound();
        $this->putInvalid();
        $this->putNotFound();
        $this->putContentNotFound();
        $this->putWhenGhost();
        $this->putWhenInActiveUser();
        $this->putWhenAnotherUser();

        $this->deleteNotFound();
        $this->deleteContentNotFound();
        $this->deleteWhenGhost();
        $this->deleteWhenInActiveUser();
        $this->deleteWhenAnotherUser();
        $this->deleteSuccess(); // delete last
    }

    public function getListSuccess(){
        $this->json('GET', $this->prefix , [
        ])
            ->assertResponseStatus(200);
        Auth::logout();
    }

    public function getListNotFound(){
        $this->json('GET', $this->wrongPrefix , [
        ])
            ->assertResponseStatus(404);
        Auth::logout();
    }

    public function postSuccess(){
        $this->json('POST', $this->prefix , [
            "description" => "test description",
        ],$this->testUserHeaders)
            ->assertResponseStatus(200);
        Auth::logout();
    }

    public function postNotFound(){
        $this->json('POST', $this->wrongPrefix , [
            "description" => "test description",
        ],$this->testUserHeaders)
            ->assertResponseStatus(404);
        Auth::logout();
    }


    public function postInvalid(){
        $this->json('POST', $this->prefix , [
        ],$this->testUserHeaders)
            ->assertResponseStatus(422);
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

    public function putSuccess(){
        $this->json('PUT', $this->prefix.$this->testCommentId , [
            "description" => "test description",
        ],$this->testUserHeaders)
            ->assertResponseStatus(200);
        Auth::logout();
    }

    public function putInvalid(){
        $this->json('PUT', $this->prefix.$this->testCommentId , [
        ],$this->testUserHeaders)
            ->assertResponseStatus(422);
        Auth::logout();
    }

    public function putNotFound(){
        $this->json('PUT', $this->prefix.'99999999999999' , [
            "description" => "test description",
        ],$this->testUserHeaders)
            ->assertResponseStatus(404);
        Auth::logout();
    }

    public function putContentNotFound(){
        $this->json('PUT', $this->wrongPrefix.'99999999999999' , [
            "description" => "test description",
        ],$this->testUserHeaders)
            ->assertResponseStatus(404);
        Auth::logout();
    }

    public function putWhenGhost(){
        $this->json('PUT', $this->prefix.$this->testCommentId , [
            "description" => "test description",
        ])
            ->assertResponseStatus(403);
        Auth::logout();
    }

    public function putWhenInActiveUser(){
        $this->json('PUT', $this->prefix.$this->testCommentId , [
            "description" => "test description",
        ],$this->headers)
            ->assertResponseStatus(403);
        Auth::logout();
    }

    public function putWhenAnotherUser(){
        $this->json('PUT', $this->prefix.'1' , [
            "description" => "test description",
        ],$this->anotherUserHeaders)
            ->assertResponseStatus(403);
        Auth::logout();
    }


    public function deleteNotFound(){
        $this->json('DELETE', $this->prefix.'9999999999999999999' , [
        ],$this->testUserHeaders)
            ->assertResponseStatus(404);
        Auth::logout();
    }

    public function deleteContentNotFound(){
        $this->json('DELETE', $this->wrongPrefix.'9999999999999999999' , [
        ],$this->testUserHeaders)
            ->assertResponseStatus(404);
        Auth::logout();
    }

    public function deleteWhenGhost(){
        $this->json('DELETE', $this->prefix.$this->testCommentId , [
        ])
            ->assertResponseStatus(403);
        Auth::logout();
    }

    public function deleteWhenInActiveUser(){
        $this->json('DELETE', $this->prefix.$this->testCommentId , [
        ],$this->headers)
            ->assertResponseStatus(403);
        Auth::logout();
    }

    public function deleteWhenAnotherUser(){
        $this->json('DELETE', $this->prefix.$this->testCommentId , [
        ],$this->anotherUserHeaders)
            ->assertResponseStatus(403);
        Auth::logout();
    }

    public function deleteSuccess(){
        $this->json('DELETE', $this->prefix.$this->testCommentId , [
        ],$this->testUserHeaders)
            ->assertResponseStatus(200);
        Auth::logout();
    }

}
