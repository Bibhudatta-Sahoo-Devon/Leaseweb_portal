<?php

namespace App\Tests\unit;

use App\Entity\Harddisk;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class HarddiskRepositoryTest extends KernelTestCase
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
    public function insert_a_record_to_harddisk_table()
    {
        $harddiskObj = new Harddisk();
        $harddiskObj->setName('4x2TBSATA2');
        $harddiskObj->setType('SATA');
        $harddiskObj->setSize(8000);

        $this->entityManager->persist($harddiskObj);
        $this->entityManager->flush();

        $harddiskRepository = $this->entityManager->getRepository(Harddisk::class);
        $harddiskRecord = $harddiskRepository->findOneBy(['name' => '4x2TBSATA2']);

        $this->assertEquals('4x2TBSATA2', $harddiskRecord->getName());
        $this->assertEquals('SATA', $harddiskRecord->getType());
        $this->assertEquals(8000, $harddiskRecord->getSize());
    }

    /**
     * @test
     */
    public function store_multiple_records_with_storeHardDiskDetails_method()
    {
        $harddiskDataArray = ['4x2TBSATA2', '2x2TBSATA2', '2x500GBSSD', '2x1TBSATA2', '1x1TBSSD'];

        $harddiskRepository = $this->entityManager->getRepository(Harddisk::class);
        $harddiskRecord = $harddiskRepository->storeHardDiskDetails($harddiskDataArray);

        $this->assertEquals(5, count($harddiskRecord));
    }

    /**
     * @depends store_multiple_records_with_storeHardDiskDetails_method
     * @test
     */
    public function search_harddisk_record_ids_with_getHardDiskSearchIds_method()
    {

        $harddiskRepository = $this->entityManager->getRepository(Harddisk::class);
        $harddiskRecordIds = $harddiskRepository->getHardDiskSearchIds(['type' => 'ssd']);

        $this->assertEquals(2, count($harddiskRecordIds));
    }

    /**
     * @depends store_multiple_records_with_storeHardDiskDetails_method
     * @test
     */
    public function search_harddisk_record_ids_for_size_with_getHardDiskSearchIds_method()
    {
        $harddiskRepository = $this->entityManager->getRepository(Harddisk::class);
        $harddiskRecordIds = $harddiskRepository->getHardDiskSearchIds(['size' => ['form'=>'1000','to'=>'8000']]);

        $this->assertEquals(5, count($harddiskRecordIds));
    }
}