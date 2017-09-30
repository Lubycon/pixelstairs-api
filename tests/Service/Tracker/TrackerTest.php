<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TrackerTest extends TestCase
{
    private $prefix = "/v1/tracker";
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->success();
        $this->invalid();
    }

    public function success(){
        $this->json('POST', $this->prefix,[
            "uuid" => "b8956a577a-9e799bcdb6-715542d849",
            "currentUrl" => "/signin",
            "prevUrl" => "/contents/detail/1",
            "action" => "0",
        ])
            ->assertResponseStatus(200);
    }
    public function invalid(){
        $this->json('POST', $this->prefix,[

        ])
            ->assertResponseStatus(422);
    }
}
