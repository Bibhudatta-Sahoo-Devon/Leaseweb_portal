<?php

namespace App\Repository;

use App\Entity\Ram;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @method Ram|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ram|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ram[]    findAll()
 * @method Ram[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ram::class);
    }

    /**
     * @param $ramData
     * @return array
     * @throws Exception
     */
    public function storeRamDetails($ramData): array
    {
        try {
            $presentRecords = [];
            if (!empty($ramData)) {

                $flush = false;
                $records = $this->findBy(['name' => $ramData]);

                if (!empty($records)) {
                    foreach ($records as $data) {
                        $presentRecords[$data->getName()] = $data;
                    }
                }

                foreach ($ramData as $data) {

                    $temp = explode('GB', $data);

                    if (!array_key_exists($data, $presentRecords) && !empty($temp) && count($temp) > 1) {

                        $ram = new Ram();
                        $ram->setName($data);
                        $ram->setSize($temp[0]);
                        $ram->setType($temp[1]);

                        $this->_em->persist($ram);
                        $flush = true;
                    }
                }

                if ($flush) {

                    $this->_em->flush();
                    $presentRecords = [];
                    $records = $this->findBy(['name' => $ramData]);

                    if (!empty($records)) {
                        foreach ($records as $data) {
                            $presentRecords[$data->getName()] = $data;
                        }
                    }
                }
            }

            return $presentRecords;

        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @param $ramSizeArray
     * @return array
     * @throws Exception
     */
    public function getRamSearchIds($ramSizeArray): array
    {
        try {
            $parameters = $ids = [];
            $qb = $this->createQueryBuilder('r')->select('r.id');

            if (!empty($ramSizeArray)) {
                $qb->where('r.size in (:sizeArray)');
                $parameters['sizeArray'] = $ramSizeArray;
            }

            if (!empty($parameters)) {
                $qb->setParameters($parameters);
            }

            $result = $qb->getQuery()->execute();

            if (!empty($result)) {
                $ids = array_column($result, 'id');
            }

            return $ids;

        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
