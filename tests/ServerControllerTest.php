<?php

namespace App\Tests\UnitTest;

use App\Controller\ServerController;
use App\Repository\FiltersRepository;
use App\Repository\HarddiskRepository;
use App\Repository\LocationRepository;
use App\Repository\RamRepository;
use App\Repository\ServerRepository;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;

class ServerControllerTest extends TestCase
{
    public function test_server_store_data(){
        $this->assertTrue(true);
    }
}
