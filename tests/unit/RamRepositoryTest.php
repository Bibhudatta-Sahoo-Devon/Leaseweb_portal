<?php

namespace App\Tests\unit;

use App\Entity\Ram;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RamRepositoryTest extends KernelTestCase
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
    }

    protected function tearDown(): void
    {
        $this->entityManager->close();
        $this->entityManager = null;
    }

    /**
     * @test
     */
    public function insert_a_record_to_ram_table(){

        $ramObj = new Ram();
        $ramObj->setName('4GBDDR3');
        $ramObj->setType('DDR3');
        $ramObj->setSize(4);

        $this->entityManager->persist($ramObj);
        $this->entityManager->flush();

        $ramRepository = $this->entityManager->getRepository(Ram::class);
        $ramRecord = $ramRepository->findOneBy(['name'=>'4GBDDR3']);

        $this->assertEquals('4GBDDR3',$ramRecord->getName());
        $this->assertEquals('DDR3',$ramRecord->getType());
        $this->assertEquals(4,$ramRecord->getSize());
    }

    /**
     * @test
     */
    public function store_multiple_records_with_storeRamDetails_method(){
        $ramDataArray = ['4GBDDR3','2GBDDR3','8GBDDR4','32GBDDR3'];

        $ramRepository = $this->entityManager->getRepository(Ram::class);
        $ramRecords = $ramRepository->storeRamDetails($ramDataArray);

        $this->assertEquals(4,count($ramRecords));
    }

    /**
     * @depends store_multiple_records_with_storeRamDetails_method
     * @test
     */
    public function search_ram_record_ids_with_getRamSearchIds_method(){
        $ramRepository = $this->entityManager->getRepository(Ram::class);
        $ramIds = $ramRepository->getRamSearchIds(8);

        $this->assertEquals(1,count($ramIds));
    }
}