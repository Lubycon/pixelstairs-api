<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ClientTest extends TestCase
{
    private $prefix = "/v1/client";

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->getInfo();
    }

    public function getInfo()
    {
        $this->json('GET', $this->prefix)
            ->assertResponseStatus(200)
            ->seeJsonStructure([
                "ip",
                "location",
                "language",
                "device" => [
                    "type",
                    "typeCode",
                    "device",
                    "os",
                    "browser",
                ]
            ]);
    }
}
