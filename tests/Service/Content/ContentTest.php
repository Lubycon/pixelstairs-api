<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ContentTest extends TestCase
{
    private $prefix = "/v1/contents/";
    public $user;
    public $anotherUser;
    public $anotherUserHeaders;
    public $token;
    public $headers;
    public $testUserHeaders;
    public $invalidHeaders;
    public $testContentId;

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

        $this->getContentSuccess(); // check view data inserted
        $this->getContentWhenUser(); // check view data user_id
        $this->getContentNotFound();

        $this->postSuccess();
        $this->postInvalid();
        $this->postWhenGhost();
        $this->postWhenInActiveUser();

        $testContent = App\Models\Content::orderBy('id','desc')->first();
        $this->testContentId = $testContent->id;

        $this->putSuccess();
        $this->putInvalid();
        $this->putNotFound();
        $this->putWhenGhost();
        $this->putWhenInActiveUser();
        $this->putWhenAnotherUser();

        $this->deleteNotFound();
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

    public function getContentSuccess(){
        // TODO view count check
        $this->json('GET', $this->prefix."1" , [
        ])
            ->assertResponseStatus(200);
        Auth::logout();
    }

    public function getContentWhenUser(){
        // TODO view count check
        $this->json('GET', $this->prefix."1" , [
        ],$this->testUserHeaders)
            ->assertResponseStatus(200);
        Auth::logout();
    }

    public function getContentNotFound(){
        $this->json('GET', $this->prefix."9999999999999999999" , [
        ])
            ->assertResponseStatus(404);
        Auth::logout();
    }

    public function postSuccess(){
        $this->json('POST', $this->prefix , [
            "title" => "Test Title",
            "description" => "test description",
            "licenseCode" => "1011",
            "hashTags" => ['test','hash','tag'],
        ],$this->testUserHeaders)
            ->assertResponseStatus(200);
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
        $this->json('PUT', $this->prefix.$this->testContentId , [
            "title" => "Test Title",
            "description" => "test description",
            "licenseCode" => "1011",
            "hashTags" => ['test','hash','tag'],
        ],$this->testUserHeaders)
            ->assertResponseStatus(200);
        Auth::logout();
    }

    public function putInvalid(){
        $this->json('PUT', $this->prefix.$this->testContentId , [
        ],$this->testUserHeaders)
            ->assertResponseStatus(422);
        Auth::logout();
    }

    public function putNotFound(){
        $this->json('PUT', $this->prefix.'99999999999999' , [
            "title" => "Test Title",
            "description" => "test description",
            "licenseCode" => "1011",
            "hashTags" => ['test','hash','tag'],
        ],$this->testUserHeaders)
            ->assertResponseStatus(404);
        Auth::logout();
    }

    public function putWhenGhost(){
        $this->json('PUT', $this->prefix.'99999999999999' , [
            "title" => "Test Title",
            "description" => "test description",
            "licenseCode" => "1011",
            "hashTags" => ['test','hash','tag'],
        ])
            ->assertResponseStatus(403);
        Auth::logout();
    }

    public function putWhenInActiveUser(){
        $this->json('PUT', $this->prefix.'99999999999999' , [
            "title" => "Test Title",
            "description" => "test description",
            "licenseCode" => "1011",
            "hashTags" => ['test','hash','tag'],
        ],$this->headers)
            ->assertResponseStatus(403);
        Auth::logout();
    }

    public function putWhenAnotherUser(){
        $this->json('PUT', $this->prefix.'1' , [
            "title" => "Test Title",
            "description" => "test description",
            "licenseCode" => "1011",
            "hashTags" => ['test','hash','tag'],
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

    public function deleteWhenGhost(){
        $this->json('DELETE', $this->prefix.$this->testContentId , [
        ])
            ->assertResponseStatus(403);
        Auth::logout();
    }

    public function deleteWhenInActiveUser(){
        $this->json('DELETE', $this->prefix.$this->testContentId , [
        ],$this->headers)
            ->assertResponseStatus(403);
        Auth::logout();
    }

    public function deleteWhenAnotherUser(){
        $this->json('DELETE', $this->prefix.$this->testContentId , [
        ],$this->anotherUserHeaders)
            ->assertResponseStatus(403);
        Auth::logout();
    }

    public function deleteSuccess(){
        $this->json('DELETE', $this->prefix.$this->testContentId , [
        ],$this->testUserHeaders)
            ->assertResponseStatus(200);
        Auth::logout();
    }

}
