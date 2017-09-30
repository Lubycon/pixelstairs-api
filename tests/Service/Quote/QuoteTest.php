<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class QuoteTest extends TestCase
{
    private $prefix = "/v1/quotes/";
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->mistake();
        $this->success();
    }

    public function mistake(){
        $this->json('GET', $this->prefix."mistake")
            ->assertResponseStatus(200);
    }
    public function success(){
        $this->json('GET', $this->prefix."success")
            ->assertResponseStatus(200);
    }
}
