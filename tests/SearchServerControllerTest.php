<?php


namespace App\Tests\UnitTest;


use App\Controller\SearchController;
use App\Repository\HarddiskRepository;
use App\Repository\LocationRepository;
use App\Repository\RamRepository;
use App\Repository\ServerRepository;
use PHPUnit\Framework\TestCase;

class SearchServerControllerTest extends TestCase
{


    public function teat_memory_size_refactor(){

        $this->assertEquals(32,32);
    }
}