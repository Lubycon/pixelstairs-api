<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ContentImageTest extends TestCase
{
    public $prefix = "/v1/contents/";
    public $prefix2 = "/v1/contents/";
    public $wrongPrefix = "/v1/contents/";
    public $testContent;
    public $testContent2;
    public $user;
    public $anotherUser;
    public $anotherUserHeaders;
    public $token;
    public $headers;
    public $testUserHeaders;
    public $invalidHeaders;
    public $testCommentId;
    public $basicJpgFile;
    public $bigJpgFile;
    public $basicPngFile;
    public $bigPngFile;

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

        $this->json('POST', 'v1/contents' , [
            "title" => "Test Title",
            "description" => "test description",
            "licenseCode" => "1011",
            "hashTags" => ['test','hash','tag'],
        ],$this->testUserHeaders)
            ->assertResponseStatus(200);
        Auth::logout();

        $this->testContent = App\Models\Content::orderBy('id','desc')->first();

        $this->json('POST', 'v1/contents' , [
            "title" => "Test Title",
            "description" => "test description",
            "licenseCode" => "1011",
            "hashTags" => ['test','hash','tag'],
        ],$this->testUserHeaders)
            ->assertResponseStatus(200);
        Auth::logout();

        $this->testContent2 = App\Models\Content::orderBy('id','desc')->first();

        $this->prefix = $this->prefix.$this->testContent->id.'/image/';
        $this->prefix2 = $this->prefix2.$this->testContent2->id.'/image/';
        $this->wrongPrefix = $this->wrongPrefix.'99999999/image/';

        $filePath = base_path()."/tests/images/basic.png";
        $this->basicPngFile = new UploadedFile(
            $filePath, //path image
            'basic.png',
            'image/png',
            filesize($filePath), // file size
            null,
            true
        );
        $filePath = base_path()."/tests/images/big.png";
        $this->bigPngFile = new UploadedFile(
            $filePath, //path image
            'big.png',
            'image/png',
            filesize($filePath), // file size
            null,
            true
        );
        $filePath = base_path()."/tests/images/basic.jpg";
        $this->basicJpgFile = new UploadedFile(
            $filePath, //path image
            'basic.jpg',
            'image/jpg',
            filesize($filePath), // file size
            null,
            true
        );
        $filePath = base_path()."/tests/images/big.jpg";
        $this->bigJpgFile = new UploadedFile(
            $filePath, //path image
            'big.jpg',
            'image/jpg',
            filesize($filePath), // file size
            null,
            true
        );
    }
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->__setup();

        // TODO big size image
        $this->postInvalid();
        $this->postNotfound();
        $this->postWhenGhost();
        $this->postWhenInActiveUser();
        $this->postWhenAnotherUser();
        $this->postSuccess(); // Success last
        $this->postOverlap();
    }

    public function postSuccess(){
        $this->json('POST', $this->prefix , [
            'file' => $this->basicPngFile
        ],$this->testUserHeaders)
            ->assertResponseStatus(200);
        Auth::logout();

        $this->json('POST', $this->prefix2 , [
            'file' => $this->basicJpgFile
        ],$this->testUserHeaders)
            ->assertResponseStatus(200);
        Auth::logout();

        // TODO Check file uploaded
//        $content = App\Models\Content::orderBy('id','desc')->first();
//        $url = $content->getGroupImageObject()['file'];
//        $this->assertTrue($url.'1920', true);

    }

    public function postOverlap(){
        $this->json('POST', $this->prefix , [
            'file' => $this->basicPngFile
        ],$this->testUserHeaders)
            ->assertResponseStatus(208);
        Auth::logout();
    }

    public function postInvalid(){

        // Image file too big
        $this->json('POST', $this->prefix , [
            'file' => $this->bigPngFile
        ],$this->testUserHeaders)
            ->assertResponseStatus(422);
        Auth::logout();

        $this->json('POST', $this->prefix , [
            'file' => $this->bigJpgFile
        ],$this->testUserHeaders)
            ->assertResponseStatus(422);
        Auth::logout();
    }

    public function postNotFound(){
        $this->json('POST', $this->wrongPrefix , [
            'file' => $this->basicPngFile
        ],$this->testUserHeaders)
            ->assertResponseStatus(404);
        Auth::logout();
    }

    public function postWhenGhost(){
        $this->json('POST', $this->prefix , [
            'file' => $this->basicPngFile
        ])
            ->assertResponseStatus(403);
        Auth::logout();
    }

    public function postWhenInActiveUser(){
        $this->json('POST', $this->prefix , [
            'file' => $this->basicPngFile
        ],$this->headers)
            ->assertResponseStatus(403);
        Auth::logout();
    }

    public function postWhenAnotherUser(){
        $this->json('POST', $this->prefix , [
            'file' => $this->basicPngFile
        ],$this->anotherUserHeaders)
            ->assertResponseStatus(403);
        Auth::logout();
    }
}
