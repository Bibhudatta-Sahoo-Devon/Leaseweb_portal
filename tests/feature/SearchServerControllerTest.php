<?php

namespace App\Tests\feature;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SearchServerControllerTest extends WebTestCase
{
    /**
     * @test
     */
    public function search_without_any_filter()
    {
        $client =   $client = static::createClient();
        $client->request('GET', '/api/search/servers');

        $this->assertResponseStatusCodeSame(200);
    }

    /**
     * @test
     */
    public function search_with_all_filter()
    {
        $client =   $client = static::createClient();
        $client->request('GET', '/api/search/servers?storage_size_from=1TB&ram_size[]=32gb&hdd_type=sata&location=AmsterdamAMS-01&storage_size_to=8TB&ram_size[]=4gb&ram_size[]=8gb');

        $this->assertResponseStatusCodeSame(200);
    }

    /**
     * @test
     */
    public function search_with_ram_filter()
    {
        $client =   $client = static::createClient();
        $client->request('GET', '/api/search/servers?ram_size[]=32gb&ram_size[]=4gb&ram_size[]=8gb');

        $this->assertResponseStatusCodeSame(200);
    }

    /**
     * @test
     */
    public function search_with_harddisk_filter()
    {
        $client =   $client = static::createClient();
        $client->request('GET', '/api/search/servers?storage_size_from=1TB&hdd_type=sata&storage_size_to=8TB');

        $this->assertResponseStatusCodeSame(200);
    }
}