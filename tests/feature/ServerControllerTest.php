<?php

namespace App\Tests\feature;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ServerControllerTest extends WebTestCase
{
    /**
     * @test
     */
    public function store_server_file_details_to_database(){
        $client = static::createClient();
        $client->request('POST', '/api/server/store');
        $this->assertResponseStatusCodeSame(201);
    }
}
