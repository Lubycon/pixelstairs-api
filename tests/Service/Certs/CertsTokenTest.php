<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CertsTokenTest extends TestCase
{
    private $prefix = "/v1/certs/token";
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

        // Token
        $this->certsTokenSuccess();
        $this->certsTokenWhenGhost();
        $this->certsTokenWhenActiveUser();
        // Invalid token can not touched at controller...
        // $this->certsTokenInvalid();
    }

    public function certsTokenSuccess(){
        $this->json('POST', $this->prefix , [
        ],$this->headers)
            ->assertResponseStatus(200)
            ->seeJsonEquals([
                "status" => [
                    "code" => "0000",
                    "devMsg" => "",
                    "msg" => "success"
                ],
                "result" => [
                    "validity" => true
                ],
            ]);
        Auth::logout();
    }

    public function certsTokenWhenGhost(){
        $this->json('POST', $this->prefix , [
        ])
            ->assertResponseStatus(403);
        Auth::logout();
    }

    public function certsTokenWhenActiveUser(){
        $this->json('POST', $this->prefix , [
        ],$this->testUserHeaders)
            ->assertResponseStatus(403);
        Auth::logout();
    }
}
