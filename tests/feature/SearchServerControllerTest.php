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


}