<?php

namespace App\Repository;

use App\Entity\Filters;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @method Filters|null find($id, $lockMode = null, $lockVersion = null)
 * @method Filters|null findOneBy(array $criteria, array $orderBy = null)
 * @method Filters[]    findAll()
 * @method Filters[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FiltersRepository extends ServiceEntityRepository
{
    private $locationRepository;

    public function __construct(ManagerRegistry $registry, LocationRepository $locationRepository)
    {
        parent::__construct($registry, Filters::class);
        $this->locationRepository = $locationRepository;
    }

    /**
     * @param $filterData
     * @return array
     * @throws Exception
     */
    public function storeFilterDetails($filterData): array
    {
        try {
            $flush = false;
            $presentRecords = [];
            $records = $this->findAll();

            if (!empty($records)) {
                foreach ($records as $data) {
                    $presentRecords[$data->getName()] = $data->getId();
                }
            }

            foreach ($filterData as $data) {

                $name = strtolower($data['name']);

                if ($name == "location") {
                    $locations = $this->locationRepository->findAll();
                    $locations = array_map(function ($data) {
                        return $data->getAddress();
                    }, $locations);
                    $value = json_encode($locations);
                } else
                    $value = json_encode(explode(',', $data['value']));

                $filter = (array_key_exists($name, $presentRecords)) ? $this->find($presentRecords[$name]) : new Filters();
                $filter->setName($name);
                $filter->setType($data['type']);
                $filter->setValue($value);


                $this->_em->persist($filter);
                $flush = true;
            }
            if ($flush) {
                $this->_em->flush();
            }

            return $presentRecords;

        }catch (Exception $exception){
            throw $exception;
        }
    }
}
