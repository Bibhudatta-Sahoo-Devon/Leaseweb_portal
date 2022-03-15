<?php

namespace App\Tests\FeatureTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SearchServerTest extends WebTestCase
{
    public function test_search_server_without_filters(): void
    {

        $client = static::createClient();

        $client->request('GET', 'http://localhost:8000/api/search/servers');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
    }

    public function test_search_server_with_ram_filters(): void
    {

        $client = static::createClient();

        $client->request('GET', 'http://localhost:8000/api/search/servers?ram_size[]=4gb&ram_size[]=8gb');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
    }

    public function test_search_server_with_hdd_filters(): void
    {

        $client = static::createClient();

        $client->request('GET', 'http://localhost:8000/api/search/servers?hdd_type=sata');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
    }

    public function test_search_server_with_location_filters(): void
    {

        $client = static::createClient();

        $client->request('GET', 'http://localhost:8000/api/search/servers?location=AmsterdamAMS-01');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
    }


    public function test_search_server_with_all_filters(): void
    {

        $client = static::createClient();

        $client->request('GET', 'http://localhost:8000/api/search/servers?ram_size[]=32gb&hdd_type=sata&location=AmsterdamAMS-01&storage_size_to=8TB&ram_size[]=4gb&ram_size[]=8gb');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
    }
}
