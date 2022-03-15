<?php

namespace App\Repository;

use App\Entity\Location;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @method Location|null find($id, $lockMode = null, $lockVersion = null)
 * @method Location|null findOneBy(array $criteria, array $orderBy = null)
 * @method Location[]    findAll()
 * @method Location[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Location::class);
    }

    /**
     * @param $locationData
     * @return array
     * @throws Exception
     */
    public function storeLocationDetails($locationData): array
    {
        try {
            $presentRecords = [];

            if (!empty($locationData)) {

                $flush = false;
                $records = $this->findBy(['address' => $locationData]);

                if (!empty($records)) {
                    foreach ($records as $data) {
                        $presentRecords[$data->getAddress()] = $data;
                    }
                }

                foreach ($locationData as $data) {

                    if (!array_key_exists($data, $presentRecords)) {

                        $location = new Location();
                        $location->setAddress($data);
                        $this->_em->persist($location);
                        $flush = true;
                    }
                }

                if ($flush){

                    $this->_em->flush();
                    $presentRecords = [];
                    $returnData = $this->findBy(['address' => $locationData]);

                    if (!empty($returnData)) {
                        foreach ($returnData as $data) {
                            $presentRecords[$data->getAddress()] = $data;
                        }
                    }
                }
            }

            return $presentRecords;

        }catch (Exception $exception){
            throw $exception;
        }
    }

    /**
     * @param $locationName
     * @return array
     * @throws Exception
     */
    public function getLocationSearchId($locationName): array
    {
        try {

            $parameters = $ids = [];
            $qb = $this->createQueryBuilder('l')->select('l.id');

            if(!empty($locationName)){
                $qb->where('l.address = (:address)');
                $parameters['address'] = $locationName;
            }

            if (!empty($parameters)){
                $qb->setParameters($parameters);
            }

            $result = $qb->getQuery()->execute();

            if (!empty($result)){
                $ids = array_column($result,'id');
            }

            return $ids;

        }catch (Exception $exception){
            throw $exception;
        }
    }
}
